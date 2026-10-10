
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login | Starline POS</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">

    <link rel="stylesheet" href="<?= base_url('assets/css/pos.css') ?>">
</head>

<body>
<main class="pos-auth-page">
    <section class="pos-auth-card">

        <div class="pos-auth-logo">S</div>

        <div class="pos-eyebrow">Starline POS</div>
        <h1 class="pos-auth-title">Welcome back!</h1>
        <p class="pos-auth-subtitle">
            Sign in to manage your products, customers, and sales.
        </p>

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

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('login') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    value="<?= esc(old('username', '')) ?>"
                    maxlength="100"
                    autocomplete="username"
                    required
                    autofocus>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    maxlength="255"
                    autocomplete="current-password"
                    required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                Sign In
            </button>
        </form>

        <div class="pos-footer">
            Starline POS · Inventory and sales management
        </div>

    </section>
</main>
</body>
</html>
