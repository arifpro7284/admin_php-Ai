<?php
require __DIR__ . '/../includes/config.php';
require_admin();

$categories = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = trim($_POST['image'] ?? '');
    if (!empty($_FILES['image_file']['name'])) {
        $extension = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($extension, $allowed, true) || $_FILES['image_file']['size'] > 4 * 1024 * 1024) {
            $error = 'Please upload a JPG, PNG, WEBP, or GIF image smaller than 4MB.';
        } else {
            $filename = uniqid('product_', true) . '.' . $extension;
            $destination = __DIR__ . '/../img/' . $filename;
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $destination)) {
                $image = 'img/' . $filename;
            } else {
                $error = 'The image could not be uploaded.';
            }
        }
    }

    if (!$error) {
        $statement = $pdo->prepare('INSERT INTO products (name,slug,category_id,description,price,compare_price,image,tags,stock,status) VALUES (?,?,?,?,?,?,?,?,?,?)');
        $statement->execute([
            trim($_POST['name']), trim($_POST['slug']), $_POST['category_id'] ?: null,
            trim($_POST['description']), (float)($_POST['price'] ?: 0),
            $_POST['compare_price'] !== '' ? (float)$_POST['compare_price'] : null,
            $image, trim($_POST['tags']), (int)($_POST['stock'] ?: 0), $_POST['status']
        ]);
        flash('success', 'Product created successfully.');
        redirect('admin/products.php');
    }
}

$pageTitle = 'Create product';
require __DIR__ . '/../includes/header.php';
?>
<div class="admin-shell"><div class="row g-0">
<aside class="col-lg-2 admin-side"><div class="eyebrow">Control room</div><h2 class="h4 mt-2">Nexa admin</h2><nav class="mt-4"><a href="index.php">Overview</a><a class="active" href="products.php">Products</a><a href="categories.php">Categories</a><a href="orders.php">Orders</a><a href="users.php">Users</a></nav></aside>
<section class="col-lg-10 p-4 p-lg-5"><div class="d-flex justify-content-between align-items-center mb-4"><div><div class="eyebrow">Catalog</div><h1 class="h2 mb-0">Create product</h1></div><a class="btn btn-outline-dark" href="products.php">Back to products</a></div>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="panel row g-3"><div class="col-md-8"><label class="form-label">Product name</label><input required name="name" class="form-control"></div><div class="col-md-4"><label class="form-label">Slug</label><input required name="slug" class="form-control" placeholder="product-name"></div><div class="col-md-4"><label class="form-label">Category</label><select name="category_id" class="form-select"><option value="">Uncategorised</option><?php foreach ($categories as $category): ?><option value="<?= $category['id'] ?>"><?= e($category['name']) ?></option><?php endforeach; ?></select></div><div class="col-md-4"><label class="form-label">Price</label><input required type="number" step=".01" min="0" name="price" class="form-control"></div><div class="col-md-4"><label class="form-label">Compare price</label><input type="number" step=".01" min="0" name="compare_price" class="form-control"></div><div class="col-md-6"><label class="form-label">Upload product image</label><input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp,.gif" class="form-control"></div><div class="col-md-6"><label class="form-label">Or image path / URL</label><input name="image" class="form-control" placeholder="img/product.jpg"></div><div class="col-md-4"><label class="form-label">Stock</label><input type="number" min="0" name="stock" value="0" class="form-control"></div><div class="col-md-8"><label class="form-label">Tags</label><input name="tags" class="form-control" placeholder="home, new, featured"></div><div class="col-12"><label class="form-label">Description</label><textarea required name="description" rows="4" class="form-control"></textarea></div><div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="draft">Draft</option></select></div><div class="col-12"><button class="btn btn-accent">Create product</button></div></form></section></div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
