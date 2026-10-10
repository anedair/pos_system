
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/pos.css') ?>">
</head>
<body class="bg-light">
<?= $this->include('layouts/sidebar') ?>

<main class="pos-main">
<div class="container py-5" style="max-width:700px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h3 class="mb-4">Edit Product</h3>

            <?php foreach ((array) session()->getFlashdata('errors') as $error): ?>
                <div class="alert alert-danger"><?= esc($error) ?></div>
            <?php endforeach; ?>

            <form action="<?= site_url('products/update/' . $product['id']) ?>"
                  method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name"
                           class="form-control"
                           value="<?= esc(old('name', $product['name'])) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control" rows="3"><?= esc(old('description', $product['description'])) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price (₱)</label>
                    <input type="number" name="price"
                           class="form-control" min="0" step="0.01"
                           value="<?= esc(old('price', $product['price'])) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="stock"
                           class="form-control" min="0" step="1"
                           value="<?= esc(old('stock', $product['stock'])) ?>" required>
                </div>

                <?php if ($product['image']): ?>
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div>
                            <img src="<?= base_url('uploads/products/' . rawurlencode($product['image'])) ?>"
                                 width="120" alt="Current product image">
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Replace Image</label>
                    <input type="file" name="image"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp">
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Product
                </button>
                <a href="<?= site_url('products') ?>"
                   class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
</main>
</body>
</html>
