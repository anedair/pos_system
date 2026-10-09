<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Product Management</h2>
        <a href="<?= site_url('products/create') ?>"
           class="btn btn-primary">+ Add Product</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <?php if ($product['image']): ?>
                                    <img src="<?= base_url('uploads/products/' . rawurlencode($product['image'])) ?>"
                                         width="65" height="65"
                                         style="object-fit:cover"
                                         alt="Product image">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($product['name']) ?></td>
                            <td>₱<?= number_format((float) $product['price'], 2) ?></td>
                            <td><?= esc($product['stock']) ?></td>
                            <td>
                                <a href="<?= site_url('products/edit/' . $product['id']) ?>"
                                   class="btn btn-sm btn-warning">Edit</a>

                                <form action="<?= site_url('products/archive/' . $product['id']) ?>"
                                      method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Archive this product?')">
                                        Archive
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No active products found.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
