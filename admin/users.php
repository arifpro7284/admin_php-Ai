<?php
require __DIR__ . '/../includes/config.php';
require_admin();
$users = $pdo->query("SELECT id,name,email,role,created_at FROM users ORDER BY created_at DESC")->fetchAll();
$pageTitle = 'Manage users';
require __DIR__ . '/../includes/header.php';
?><div class="admin-shell"><div class="row g-0"><aside class="col-lg-2 admin-side"><div class="eyebrow">Control room</div><h2 class="h4 mt-2">Nexa admin</h2><nav class="mt-4"><a href="index.php">Overview</a><a href="products.php">Products</a><a href="categories.php">Categories</a><a href="orders.php">Orders</a><a class="active" href="users.php">Users</a></nav></aside><section class="col-lg-10 p-4 p-lg-5"><h1 class="h2 mb-4">Users</h1><div class="panel table-responsive"><table class="table"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead><tbody><?php foreach ($users as $user): ?><tr><td><?= e($user['name']) ?></td><td><?= e($user['email']) ?></td><td><span class="badge text-bg-light"><?= e($user['role']) ?></span></td><td><?= date('M j, Y', strtotime($user['created_at'])) ?></td></tr><?php endforeach; ?></tbody></table></div></section></div></div><?php require __DIR__ . '/../includes/footer.php'; ?>