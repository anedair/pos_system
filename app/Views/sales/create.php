
<?php
/**
 * @var array<int, array<string, mixed>> $products
 * @var array<int, array<string, mixed>> $customers
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Record Sale | Starline POS</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('assets/css/pos.css') ?>">
</head>
<body>

<?= $this->include('layouts/sidebar') ?>

<main class="pos-main">
    <div class="page-container">

        <header class="pos-topbar">
            <div>
                <div class="pos-eyebrow">Starline POS / Transactions</div>
                <h1 class="pos-page-title">Record Sale</h1>
                <p class="pos-subtitle">
                    Select a customer and product to record a transaction.
                </p>
            </div>

            <a href="<?= site_url('sales') ?>" class="btn btn-light">
                Sales History
            </a>
        </header>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php foreach ((array) session()->getFlashdata('errors') as $error): ?>
            <div class="alert alert-danger" role="alert">
                <?= esc($error) ?>
            </div>
        <?php endforeach; ?>

        <?php if (empty($products)): ?>
            <div class="alert alert-warning">
                There are no active products with stock available.
                Add a product or restock an existing product before recording a sale.
            </div>
        <?php endif; ?>

        <?php if (empty($customers)): ?>
            <div class="alert alert-warning">
                No customers are available. Add a customer before recording a sale.
            </div>
        <?php endif; ?>

        <section class="card form-card">
            <div class="card-body">

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="pos-brand-icon">₱</div>
                    <div>
                        <h2 class="h5 fw-bold mb-1">Transaction Details</h2>
                        <p class="pos-subtitle">
                            Prices and stock are checked by the server before saving.
                        </p>
                    </div>
                </div>

                <form action="<?= site_url('sales/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="customer_id" class="form-label">
                            Customer
                        </label>
                        <select
                            id="customer_id"
                            name="customer_id"
                            class="form-select"
                            required>
                            <option value="">Select a customer</option>

                            <?php foreach ($customers as $customer): ?>
                                <option
                                    value="<?= esc($customer['id']) ?>"
                                    <?= (string) old('customer_id', '') === (string) $customer['id'] ? 'selected' : '' ?>>
                                    <?= esc($customer['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="product_id" class="form-label">
                            Product
                        </label>
                        <select
                            id="product_id"
                            name="product_id"
                            class="form-select"
                            required>
                            <option value="">Select a product</option>

                            <?php foreach ($products as $product): ?>
                                <option
                                    value="<?= esc($product['id']) ?>"
                                    data-price="<?= esc($product['price']) ?>"
                                    data-stock="<?= esc($product['stock']) ?>"
                                    <?= (string) old('product_id', '') === (string) $product['id'] ? 'selected' : '' ?>>
                                    <?= esc($product['name']) ?>
                                    — ₱<?= number_format((float) $product['price'], 2) ?>
                                    (Stock: <?= esc($product['stock']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">
                                Quantity
                            </label>
                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                class="form-control"
                                min="1"
                                step="1"
                                value="<?= esc(old('quantity', '1')) ?>"
                                required>
                            <div id="stock-help" class="form-text">
                                Select a product to see available stock.
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="unit-price" class="form-label">
                                Unit Price
                            </label>
                            <input
                                type="text"
                                id="unit-price"
                                class="form-control"
                                value="₱0.00"
                                readonly>
                            <div class="form-text">
                                Loaded from the selected product.
                            </div>
                        </div>
                    </div>

                    <div class="pastel-card p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Estimated Total</span>
                            <strong id="sale-total" class="fs-4">₱0.00</strong>
                        </div>
                        <div class="form-text mt-1">
                            The server calculates the final amount from the database price.
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            <?= empty($products) || empty($customers) ? 'disabled' : '' ?>>
                            Record Sale
                        </button>

                        <a href="<?= site_url('sales') ?>" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </section>

        <footer class="pos-footer">
            Starline POS · Sales Transactions
        </footer>

    </div>
</main>

<script>
(() => {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const unitPriceInput = document.getElementById('unit-price');
    const totalOutput = document.getElementById('sale-total');
    const stockHelp = document.getElementById('stock-help');

    function updateEstimate() {
        const option = productSelect.selectedOptions[0];
        const price = Number(option?.dataset.price || 0);
        const stock = Number(option?.dataset.stock || 0);
        const quantity = Number(quantityInput.value || 0);

        unitPriceInput.value = '₱' + price.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        totalOutput.textContent = '₱' + (price * quantity).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        if (option && option.value !== '') {
            quantityInput.max = String(stock);
            stockHelp.textContent = 'Available stock: ' + stock + '.';

            if (quantity > stock) {
                stockHelp.textContent =
                    'Only ' + stock + ' item(s) are available. Reduce the quantity.';
                stockHelp.style.color = '#C85B67';
            } else {
                stockHelp.style.color = '';
            }
        } else {
            quantityInput.removeAttribute('max');
            stockHelp.textContent = 'Select a product to see available stock.';
            stockHelp.style.color = '';
        }
    }

    productSelect.addEventListener('change', updateEstimate);
    quantityInput.addEventListener('input', updateEstimate);

    updateEstimate();
})();
</script>

</body>
</html>
