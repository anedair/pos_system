<?php

// Get the current URL path and remove index.php if present.
$currentPath = trim(service('uri')->getPath(), '/');
$currentPath = preg_replace('#^index\.php/?#i', '', $currentPath);
$currentPath = trim($currentPath, '/');

// Navigation items for the POS system.
$managementNavigation = [
    [
        'label' => 'Products',
        'url' => 'products',
        'icon' => '▦',
    ],
    [
        'label' => 'Customers',
        'url' => 'customers',
        'icon' => '♙',
    ],
    [
        'label' => 'Staff Management',
        'url' => 'users',
        'icon' => '♧',
    ],
];

$transactionNavigation = [
    [
        'label' => 'Record Sale',
        'url' => 'sales/create',
        'icon' => '🛒',
    ],
    [
        'label' => 'Sales History',
        'url' => 'sales',
        'icon' => '◷',
    ],
];

// Determine whether a navigation link is active.
$isActiveLink = static function (string $url) use ($currentPath): bool {
    $url = trim($url, '/');

    // Sales pages must match exactly so they don't highlight each other.
    if ($url === 'sales' || $url === 'sales/create') {
        return $currentPath === $url;
    }

    // Management pages remain active on their create and edit routes.
    return $currentPath === $url
        || strpos($currentPath, $url . '/') === 0;
};

?>

<aside class="pos-sidebar">

    <!-- Starline Brand -->
    <a href="<?= site_url('products') ?>" class="pos-brand">
        <div class="pos-brand-icon">S</div>

        <div>
            <div class="pos-brand-name">STARLINE</div>
            <div class="pos-brand-caption">Point-of-Sale System</div>
        </div>
    </a>

    <!-- Management Navigation -->
    <div class="pos-nav-heading">Management</div>

    <nav class="pos-nav" aria-label="Management navigation">

        <?php foreach ($managementNavigation as $item): ?>
            <a
                href="<?= site_url($item['url']) ?>"
                class="pos-nav-link <?= $isActiveLink($item['url']) ? 'active' : '' ?>"
                <?= $isActiveLink($item['url']) ? 'aria-current="page"' : '' ?>
            >
                <span class="pos-nav-icon">
                    <?= esc($item['icon']) ?>
                </span>

                <span><?= esc($item['label']) ?></span>
            </a>
        <?php endforeach; ?>

    </nav>

    <!-- Transaction Navigation -->
    <div class="pos-nav-heading">Transactions</div>

    <nav class="pos-nav" aria-label="Transaction navigation">

        <?php foreach ($transactionNavigation as $item): ?>
            <a
                href="<?= site_url($item['url']) ?>"
                class="pos-nav-link <?= $isActiveLink($item['url']) ? 'active' : '' ?>"
                <?= $isActiveLink($item['url']) ? 'aria-current="page"' : '' ?>
            >
                <span class="pos-nav-icon">
                    <?= esc($item['icon']) ?>
                </span>

                <span><?= esc($item['label']) ?></span>
            </a>
        <?php endforeach; ?>

    </nav>

    <!-- Sidebar Footer and Logout -->
    <div class="pos-sidebar-footer">

        <strong>Starline POS</strong><br>
        Inventory and sales management

        <?php if (session()->get('user_id')): ?>

            <div class="mt-3 mb-2">
                <div class="pos-muted">Signed in as</div>

                <strong>
                    <?= esc(session()->get('full_name')) ?>
                </strong>
            </div>

            <form
                action="<?= site_url('logout') ?>"
                method="post"
            >
                <?= csrf_field() ?>

                <button
                    type="submit"
                    class="btn btn-outline-danger btn-sm w-100"
                >
                    Log Out
                </button>
            </form>

        <?php endif; ?>

    </div>

</aside>
