
<?php
/**
 * @var array<int, array<string, mixed>> $users
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Management | Starline POS</title>

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
                <h1 class="pos-page-title">Staff Management</h1>
                <p class="pos-subtitle">
                    Manage staff accounts and profile avatars.
                </p>
            </div>

            <a href="<?= site_url('users/create') ?>"
               class="btn btn-pastel">
                + Add Staff
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
                    <h2 class="h5 fw-bold mb-1">Staff Accounts</h2>
                    <p class="pos-subtitle">
                        <?= count($users) ?> staff account(s)
                    </p>
                </div>
            </div>

            <div class="pos-table-wrap">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="4" class="pos-empty-state">
                                <div class="pos-empty-icon">♙</div>
                                <h3 class="h5">No staff accounts yet</h3>
                                <p>Add your first staff member to get started.</p>
                                <a href="<?= site_url('users/create') ?>"
                                   class="btn btn-pastel">
                                    Add Staff
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                    <?php if (!empty($user['avatar'])): ?>
                        <img
                           class="staff-avatar"
                           src="<?= esc(base_url(
                           'uploads/avatars/' . rawurlencode($user['avatar'])
                           )) ?>"
                           alt="<?= esc($user['full_name']) ?> avatar">
                    <?php else: ?>
                        <img
                           class="staff-avatar staff-avatar-default"
                           src="<?= base_url('assets/images/placeholder.svg') ?>"
                           alt="Default staff avatar">
                    <?php endif; ?>
                                </td>

                                <td class="username">
                                    <?= esc($user['username']) ?>
                                </td>

                                <td><?= esc($user['full_name']) ?></td>

                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a
                                            href="<?= site_url('users/edit/' . $user['id']) ?>"
                                            class="btn btn-sm btn-edit">
                                            Edit
                                        </a>

                                        <form
                                            action="<?= site_url('users/delete/' . $user['id']) ?>"
                                            method="post"
                                            class="m-0">
                                            <?= csrf_field() ?>

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Delete this staff account?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="pos-footer">
            Starline POS · Inventory and sales management
        </footer>

    </div>
</main>

</body>
</html>
