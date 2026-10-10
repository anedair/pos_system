
<?php
/**
 * @var array<int, array<string, mixed>> $sales
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales History | Starline POS</title>

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
                <h1 class="pos-page-title">Sales History</h1>
                <p class="pos-subtitle">
                    Review recorded transactions and their totals.
                </p>
            </div>

            <a href="<?= site_url('sales/create') ?>" class="btn btn-pastel">
                + Record Sale
            </a>
        </header>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <section class="pastel-card p-3 p-md-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div>
                    <h2 class="h5 fw-bold mb-1">Transactions</h2>
                    <p class="pos-subtitle">
                        <?= count($sales) ?> sale(s) recorded
                    </p>
                </div>
            </div>

            <div class="pos-table-wrap">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Sale ID</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Staff</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (empty($sales)): ?>
                        <tr>
                            <td colspan="8" class="pos-empty-state">
                                <div class="pos-empty-icon">◷</div>
                                <h3 class="h5">No sales recorded yet</h3>
                                <p>Completed transactions will appear here.</p>
                                <a href="<?= site_url('sales/create') ?>"
                                   class="btn btn-pastel">
                                    Record Your First Sale
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sales as $sale): ?>
                            <tr>
                                <td class="fw-bold">
                                    #<?= esc($sale['id']) ?>
                                </td>

                                <td>
                                    <?= esc(
                                        $sale['created_at']
                                            ? date(
                                                'M d, Y h:i A',
                                                strtotime($sale['created_at'])
                                            )
                                            : '—'
                                    ) ?>
                                </td>

                                <td>
                                    <?= esc($sale['product_name'] ?? 'Product unavailable') ?>
                                </td>

                                <td>
                                    <?= esc($sale['customer_name'] ?? 'Customer unavailable') ?>
                                </td>

                                <td>
                                    <?= esc($sale['staff_name'] ?? 'Staff unavailable') ?>
                                </td>

                                <td><?= esc($sale['quantity']) ?></td>

                                <td>
                                    ₱<?= number_format((float) $sale['unit_price'], 2) ?>
                                </td>

                                <td class="fw-bold">
                                    ₱<?= number_format((float) $sale['total_price'], 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="pos-footer">
            Starline POS · Sales History
        </footer>

    </div>
</main>

</body>
</html>
