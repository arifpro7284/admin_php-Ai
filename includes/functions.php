<?php
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); }
function url(string $path = ''): string { return '/admin_php/' . ltrim($path, '/'); }
function redirect(string $path): never { header('Location: ' . url($path)); exit; }
function flash(string $type, string $message): void { $_SESSION['flash'][] = [$type, $message]; }
function flashes(): array { $items = $_SESSION['flash'] ?? []; unset($_SESSION['flash']); return $items; }
function is_logged_in(): bool { return isset($_SESSION['user']); }
function is_admin(): bool { return is_logged_in() && ($_SESSION['user']['role'] ?? '') === 'admin'; }
function require_login(): void { if (!is_logged_in()) { flash('warning', 'Please sign in to continue.'); redirect('login.php'); } }
function require_admin(): void { if (!is_admin()) { flash('danger', 'Admin access is required.'); redirect('login.php'); } }
function cart_count(): int { return array_sum($_SESSION['cart'] ?? []); }
function cart_items(PDO $pdo): array {
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) return [];
    $ids = array_keys($cart);
    $marks = implode(',', array_fill(0, count($ids), '?'));
    $statement = $pdo->prepare("SELECT * FROM products WHERE id IN ($marks) AND status = 'active'");
    $statement->execute($ids);
    $items = [];
    foreach ($statement as $item) { $item['quantity'] = (int)($cart[$item['id']] ?? 0); $items[] = $item; }
    return $items;
}
function cart_total(array $items): float { return array_reduce($items, fn(float $total, array $item) => $total + ((float)$item['price'] * $item['quantity']), 0.0); }