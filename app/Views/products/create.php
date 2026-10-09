
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5" style="max-width:700px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h3 class="mb-4">Add Product</h3>

            <?php foreach ((array) session()->getFlashdata('errors') as $error): ?>
                <div class="alert alert-danger"><?= esc($error) ?></div>
            <?php endforeach; ?>

            <form action="<?= site_url('products/store') ?>"
                  method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name"
                           class="form-control"
                           value="<?= esc(old('name')) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control" rows="3"><?= esc(old('description')) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price (₱)</label>
                    <input type="number" name="price"
                           class="form-control" min="0" step="0.01"
                           value="<?= esc(old('price')) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="stock"
                           class="form-control" min="0" step="1"
                           value="<?= esc(old('stock')) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <input type="file" name="image"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp">
                    <small class="text-muted">JPEG, PNG, or WebP. Maximum 2 MB.</small>
                </div>

                <button type="submit" class="btn btn-primary">
                    Save Product
                </button>
                <a href="<?= site_url('products') ?>"
                   class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
