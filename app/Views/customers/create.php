
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link  rel="stylesheet" href="<?= base_url('assets/css/pos.css') ?>">
</head>
<body class="bg-light">
<?= $this->include('layouts/sidebar') ?>

<main class="pos-main">
<div class="container py-5" style="max-width: 700px;">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h2 class="mb-4">Add Customer</h2>

            <?php foreach ((array) session()->getFlashdata('errors') as $error): ?>
                <div class="alert alert-danger">
                    <?= esc($error) ?>
                </div>
            <?php endforeach; ?>

            <form action="<?= site_url('customers/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" id="full_name" name="full_name"
                           class="form-control"
                           value="<?= esc(old('full_name')) ?>"
                           minlength="2" maxlength="150" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email"
                           class="form-control"
                           value="<?= esc(old('email')) ?>"
                           maxlength="150">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" id="phone" name="phone"
                           class="form-control"
                           value="<?= esc(old('phone')) ?>"
                           maxlength="30">
                </div>

                <button type="submit" class="btn btn-primary">
                    Save Customer
                </button>

                <a href="<?= site_url('customers') ?>"
                   class="btn btn-secondary">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>
</main>
</body>
</html>
