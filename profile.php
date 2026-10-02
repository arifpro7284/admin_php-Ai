<?php require __DIR__ . '/includes/config.php';
require_login();
$pageTitle = 'Profile';
require __DIR__ . '/includes/header.php'; ?><section class="container py-5">
    <div class="eyebrow">Account</div>
    <h1 class="display-5 mb-4">Your profile</h1>
    <div class="panel col-lg-7">
        <p class="tag">Name</p>
        <p class="h4 mb-4"><?= e($_SESSION['user']['name']) ?></p>
        <p class="tag">Email</p>
        <p class="h4 mb-4"><?= e($_SESSION['user']['email']) ?></p><a class="btn btn-dark" href="orders.php">View orders</a> <a class="btn btn-outline-dark" href="logout.php">Sign out</a>
    </div>
</section><?php require __DIR__ . '/includes/footer.php'; ?>