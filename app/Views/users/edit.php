<?php
/**
 * @var array<string, mixed> $user
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Staff | Starline POS</title>

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

        <!-- Page header -->
        <header class="pos-topbar">
            <div>
                <div class="pos-eyebrow">
                    Starline POS / Administration
                </div>

                <h1 class="pos-page-title">Edit Staff</h1>

                <p class="pos-subtitle">
                    Update staff account information and profile avatar.
                </p>
            </div>

            <a href="<?= site_url('users') ?>" class="btn btn-light">
                Back to Staff
            </a>
        </header>

        <!-- Success message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <!-- General error message -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <!-- Validation errors -->
        <?php foreach ((array) session()->getFlashdata('errors') as $error): ?>
            <div class="alert alert-danger" role="alert">
                <?= esc($error) ?>
            </div>
        <?php endforeach; ?>

        <!-- Edit staff card -->
        <section class="card form-card">
            <div class="card-body">

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="pos-brand-icon">♙</div>

                    <div>
                        <h2 class="h5 fw-bold mb-1">
                            Staff Information
                        </h2>

                        <p class="pos-subtitle">
                            Editing <?= esc($user['full_name']) ?>
                        </p>
                    </div>
                </div>

                <!-- Current avatar or default placeholder -->
                <div class="mb-4">
                    <label class="form-label d-block">
                        Current Avatar
                    </label>

                    <?php if (!empty($user['avatar'])): ?>
                        <img
                            class="staff-avatar"
                            src="<?= esc(base_url(
                                'uploads/avatars/' .
                                rawurlencode($user['avatar'])
                            )) ?>"
                            alt="Avatar of <?= esc($user['full_name']) ?>"
                            style="width: 90px; height: 90px;">
                    <?php else: ?>
                        <img
                            class="staff-avatar staff-avatar-default"
                            src="<?= base_url('assets/images/placeholder.svg') ?>"
                            alt="Default staff avatar"
                            style="width: 90px; height: 90px;">
                    <?php endif; ?>
                </div>

                <!-- Update form -->
                <form
                    action="<?= site_url('users/update/' . $user['id']) ?>"
                    method="post"
                    enctype="multipart/form-data">

                    <?= csrf_field() ?>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            Username
                        </label>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control"
                            value="<?= esc(old(
                                'username',
                                $user['username']
                            )) ?>"
                            minlength="3"
                            maxlength="100"
                            autocomplete="username"
                            required>
                    </div>

                    <!-- Full name -->
                    <div class="mb-3">
                        <label for="full_name" class="form-label">
                            Full Name
                        </label>

                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            class="form-control"
                            value="<?= esc(old(
                                'full_name',
                                $user['full_name']
                            )) ?>"
                            minlength="2"
                            maxlength="150"
                            required>
                    </div>

                    <!-- Optional new password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            New Password (Optional)
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            minlength="8"
                            maxlength="255"
                            autocomplete="new-password">

                        <div class="form-text">
                            Leave blank to keep the current password.
                        </div>
                    </div>

                    <!-- Optional avatar replacement -->
                    <div class="mb-4">
                        <label for="avatar" class="form-label">
                            Replace Avatar (Optional)
                        </label>

                        <input
                            type="file"
                            id="avatar"
                            name="avatar"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp">

                        <div class="form-text">
                            Accepted formats: JPEG, PNG, or WebP.
                            Maximum size: 2 MB.
                            Leave empty to keep the current avatar.
                        </div>
                    </div>

                    <!-- Form actions -->
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            Update Staff
                        </button>

                        <a
                            href="<?= site_url('users') ?>"
                            class="btn btn-secondary">
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
