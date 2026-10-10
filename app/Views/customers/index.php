<?php
/** @var array<int, array<string, mixed>> $customers */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Management</title>
    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('assets/css/pos.css') ?>">
</head>

<body class="bg-light">
      <?= $this->include('layouts/sidebar') ?>

<main class="pos-main">
    <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Customer Management</h2>
        <a href="<?= site_url('customers/create') ?>" class="btn btn-primary">
            + Add Customer
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= esc($customer['id']) ?></td>
                        <td><?= esc($customer['full_name']) ?></td>
                        <td><?= esc($customer['email'] ?? '') ?></td>
                        <td><?= esc($customer['phone'] ?? '') ?></td>
                        <td>
                            <a href="<?= site_url('customers/edit/' . $customer['id']) ?>"
                               class="btn btn-warning btn-sm">Edit</a>

                            <form action="<?= site_url('customers/delete/' . $customer['id']) ?>"
                                  method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this customer?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No customers found. Add your first customer.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>
</body>
</html>
