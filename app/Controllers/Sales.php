<?php

namespace App\Controllers;

use Throwable;
use CodeIgniter\Database\BaseConnection;
use App\Models\SaleModel;

class Sales extends BaseController
{
    public function index()
    {
        $db = db_connect();

        $sales = $db->table('sales s')
            ->select(
                's.id, s.quantity, s.unit_price, s.created_at,
                 p.name AS product_name,
                 c.full_name AS customer_name,
                 u.full_name AS staff_name,
                 (s.quantity * s.unit_price) AS total_price'
            )
            ->join('products p', 'p.id = s.product_id', 'left')
            ->join('customers c', 'c.id = s.customer_id', 'left')
            ->join('users u', 'u.id = s.user_id', 'left')
            ->orderBy('s.created_at', 'DESC')
            ->orderBy('s.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('sales/index', ['sales' => $sales]);
    }

    public function create()
    {
        $db = db_connect();

        $products = $db->table('products')
            ->select('id, name, price, stock')
            ->where('is_active', 1)
            ->where('stock >', 0)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        $customers = $db->table('customers')
            ->select('id, full_name')
            ->orderBy('full_name', 'ASC')
            ->get()
            ->getResultArray();

        return view('sales/create', [
            'products' => $products,
            'customers' => $customers,
        ]);
    }

    /**
     * Validate input and coordinate the sale workflow.
     */
    public function store()
    {
        $response = null;

        $rules = [
            'product_id' => 'required|is_natural_no_zero',
            'customer_id' => 'required|is_natural_no_zero',
            'quantity' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            $response = redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        } else {
            $userId = (int) session()->get('user_id');

            if ($userId <= 0) {
                $response = redirect()->to(site_url('login'))
                    ->with('error', 'Please log in before recording a sale.');
            } else {
                $result = $this->processSale(
                    (int) $this->request->getPost('product_id'),
                    (int) $this->request->getPost('customer_id'),
                    (int) $this->request->getPost('quantity'),
                    $userId
                );

                if ($result['success']) {
                    $response = redirect()->to(site_url('sales'))
                        ->with('success', $result['message']);
                } else {
                    $response = redirect()->back()
                        ->withInput()
                        ->with('error', $result['message']);
                }
            }
        }

        return $response ?? redirect()->to(site_url('sales'));
    }

    /**
     * Execute sale creation and inventory reduction atomically.
     *
     * @return array{success: bool, message: string}
     */
    private function processSale(
        int $productId,
        int $customerId,
        int $quantity,
        int $userId
    ): array {
        $db = db_connect();
        $db->transBegin();

        try {
            $product = $this->lockProduct($db, $productId);

            if (!$product || (int) $product['is_active'] !== 1) {
                return $this->rollbackSale(
                    $db,
                    'The selected product is unavailable.'
                );
            }

            if ($quantity > (int) $product['stock']) {
                return $this->rollbackSale(
                    $db,
                    'Insufficient stock. Available quantity: '
                    . (int) $product['stock'] . '.'
                );
            }

            if (!$this->recordExists($db, 'customers', $customerId)) {
                return $this->rollbackSale(
                    $db,
                    'Please select a valid customer.'
                );
            }

            if (!$this->recordExists($db, 'users', $userId)) {
                return $this->rollbackSale(
                    $db,
                    'Your staff session is invalid. Please log in again.'
                );
            }

            $saleId = $this->insertSale(
                $productId,
                $customerId,
                $userId,
                $quantity,
                (float) $product['price']
            );

            if (!$saleId) {
                return $this->rollbackSale(
                    $db,
                    'The sale record could not be saved.'
                );
            }

            $this->updateStock(
                $db,
                $productId,
                (int) $product['stock'] - $quantity
            );

            if ($db->transStatus() === false) {
                return $this->rollbackSale(
                    $db,
                    'The sale or stock update failed.'
                );
            }

            $db->transCommit();

            return [
                'success' => true,
                'message' => 'Sale #' . $saleId . ' recorded successfully.',
            ];
        } catch (Throwable $e) {
            $db->transRollback();

            log_message(
                'error',
                'Record Sale failed: ' . $e->getMessage()
            );

            return [
                'success' => false,
                'message' => 'The sale could not be completed. Please try again.',
            ];
        }
    }

    /**
     * Lock a product row during the transaction.
     */
    private function lockProduct(
        BaseConnection $db,
        int $productId
    ): ?array {
        $product = $db->query(
            'SELECT id, name, price, stock, is_active
             FROM products
             WHERE id = ?
             FOR UPDATE',
            [$productId]
        )->getRowArray();

        return $product ?: null;
    }

    /**
     * Check whether the referenced customer or staff account exists.
     */
    private function recordExists(
        BaseConnection $db,
        string $table,
        int $id
    ): bool {
        $allowedTables = ['customers', 'users'];

        if (!in_array($table, $allowedTables, true)) {
            return false;
        }

        return $db->table($table)
            ->where('id', $id)
            ->countAllResults() > 0;
    }

    /**
     * Save the sale using the product price from the database.
     */
    private function insertSale(
        int $productId,
        int $customerId,
        int $userId,
        int $quantity,
        float $unitPrice
    ): int {
        $saleModel = new SaleModel();

        $saleId = $saleModel->insert([
            'product_id' => $productId,
            'customer_id' => $customerId,
            'user_id' => $userId,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return is_numeric($saleId) ? (int) $saleId : 0;
    }

    /**
     * Update inventory inside the current database transaction.
     */
    private function updateStock(
        BaseConnection $db,
        int $productId,
        int $newStock
    ): void {
        $db->table('products')
            ->where('id', $productId)
            ->update(['stock' => $newStock]);
    }

    /**
     * Roll back the transaction and return a business error.
     *
     * @return array{success: bool, message: string}
     */
    private function rollbackSale(
        BaseConnection $db,
        string $message
    ): array {
        $db->transRollback();

        return [
            'success' => false,
            'message' => $message,
        ];
    }
}
