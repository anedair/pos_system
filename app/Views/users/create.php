
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Staff | Starline POS</title>

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
                <div class="pos-eyebrow">Starline POS / Administration</div>
                <h1 class="pos-page-title">Add Staff</h1>
                <p class="pos-subtitle">Create a new staff account.</p>
            </div>

            <a href="<?= site_url('users') ?>" class="btn btn-light">
                Back to Staff
            </a>
        </header>

        <?php foreach ((array) session()->getFlashdata('errors') as $error): ?>
            <div class="alert alert-danger" role="alert">
                <?= esc($error) ?>
            </div>
        <?php endforeach; ?>

        <section class="card form-card">
            <div class="card-body">

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="pos-brand-icon">♙</div>
                    <div>
                        <h2 class="h5 fw-bold mb-1">Staff Information</h2>
                        <p class="pos-subtitle">Enter the details below.</p>
                    </div>
                </div>

                <form
                    action="<?= site_url('users/store') ?>"
                    method="post"
                    enctype="multipart/form-data">

                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control"
                            value="<?= esc(old('username', '')) ?>"
                            minlength="3"
                            maxlength="100"
                            autocomplete="username"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            class="form-control"
                            value="<?= esc(old('full_name', '')) ?>"
                            minlength="2"
                            maxlength="150"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            minlength="8"
                            maxlength="255"
                            autocomplete="new-password"
                            required>
                        <div class="form-text">
                            Use at least 8 characters.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="avatar" class="form-label">
                            Profile Avatar (Optional)
                        </label>
                        <input
                            type="file"
                            id="avatar"
                            name="avatar"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">
                            Accepted formats: JPEG, PNG, or WebP. Maximum size: 2 MB.
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            Save Staff
                        </button>
                        <a href="<?= site_url('users') ?>" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </section>

        <footer class="pos-footer">
            Starline POS · Staff Administration
        </footer>

    </div>
</main>

</body>
</html>
