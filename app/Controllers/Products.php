<?php
namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Products extends BaseController
{
    protected ProductModel $products;

    public function __construct()
    {
        $this->products = new ProductModel();
    }

    // DISPLAY ALL ACTIVE PRODUCTS
    public function index()
    {
        $data['products'] = $this->products
            ->where('is_active', 1)
            ->findAll();

        return view('products/index', $data);
    }

    // SHOW ADD PRODUCT FORM
    public function create()
    {
        return view('products/create');
    }

    // VALIDATION RULES
    private function productRules(): array
    {
        return [
            'name' => 'required|min_length[2]|max_length[150]',
            'price' => 'required|decimal|greater_than_equal_to[0]',
            'stock' => 'required|is_natural',
            'image' => 'permit_empty|is_image[image]|mime_in[image,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];
    }

    // GET PRODUCT FORM DATA
    private function getProductData(): array
    {
        return [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
        ];
    }

    // HANDLE PRODUCT IMAGE UPLOAD
    private function uploadImage(): ?string
    {
        $file = $this->request->getFile('image');

        if (!$file || !$file->isValid()) {
            return null;
        }

        $path = FCPATH . 'uploads/products';

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filename = $file->getRandomName();
        $file->move($path, $filename);

        return $filename;
    }

    // SAVE NEW PRODUCT
    public function store()
    {
        if (!$this->validate($this->productRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->getProductData();
        $data['is_active'] = 1;

        $image = $this->uploadImage();

        if ($image !== null) {
            $data['image'] = $image;
        }

        if (!$this->products->insert($data)) {
            if ($image !== null) {
                @unlink(FCPATH . 'uploads/products/' . $image);
            }

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->products->errors());
        }

        return redirect()->to('/products')
            ->with('success', 'Product added successfully.');
    }

    // SHOW EDIT PRODUCT FORM
    public function edit($id)
    {
        $product = $this->products->find($id);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('products/edit', [
            'product' => $product
        ]);
    }

    // UPDATE PRODUCT
    public function update($id)
    {
        $product = $this->products->find($id);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (!$this->validate($this->productRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->getProductData();

        $image = $this->uploadImage();

        if ($image !== null) {
            $data['image'] = $image;
        }

        if (!$this->products->update($id, $data)) {
            if ($image !== null) {
                @unlink(FCPATH . 'uploads/products/' . $image);
            }

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->products->errors());
        }

        return redirect()->to('/products')
            ->with('success', 'Product updated successfully.');
    }

    // ARCHIVE PRODUCT
    public function archive($id)
    {
        $product = $this->products->find($id);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound();
        }

        $this->products->update($id, [
            'is_active' => 0
        ]);

        return redirect()->to('/products')
            ->with('success', 'Product archived successfully.');
    }
}
