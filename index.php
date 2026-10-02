<?php $pageTitle = 'A considered collection';
require __DIR__ . '/includes/header.php';
$products = $pdo ? $pdo->query("SELECT p.*, c.name category FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.status='active' ORDER BY p.id DESC")->fetchAll() : []; ?>
<section class="hero">
    <div class="container hero-copy">
        <div class="eyebrow mb-3">Nexa / seasonal edit</div>
        <h1>Good things,<br>made to last.</h1>
        <p class="lead mt-4" style="max-width:480px">Objects, apparel, and tools with a quieter point of view.</p><a class="btn btn-accent mt-3 px-4 py-2" href="#collection">Explore collection</a>
    </div>
</section>
<section class="container py-5" id="collection">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <div class="eyebrow">Curated for now</div>
            <h2 class="section-title mb-0">The collection</h2>
        </div><a class="text-dark" href="<?= url('category.php') ?>">Shop by category →</a>
    </div>
    <div class="row g-4"><?php foreach ($products as $product): ?><div class="col-sm-6 col-lg-3">
                <article class="card product-card"><a href="<?= url('product.php?id=' . $product['id']) ?>"><img class="card-img-top product-img" src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>"></a>
                    <div class="card-body">
                        <div class="tag mb-2"><?= e($product['category'] ?? 'Collection') ?></div>
                        <h3 class="h5"><a class="text-decoration-none text-dark" href="<?= url('product.php?id=' . $product['id']) ?>"><?= e($product['name']) ?></a></h3>
                        <div class="price mt-3">$<?= number_format((float)$product['price'], 2) ?></div>
                        <form method="post" action="<?= url('product.php?id=' . $product['id']) ?>" class="d-grid gap-2 mt-3"><input type="hidden" name="quantity" value="1"><button name="action" value="cart" class="btn btn-dark btn-sm">Add to cart</button><button name="action" value="buy" class="btn btn-accent btn-sm">Buy now</button></form>
                    </div>
                </article>
            </div><?php endforeach; ?></div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>