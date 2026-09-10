<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= $product ? 'Edit' : 'Add' ?> Product</title><link rel="stylesheet" href="<?= site_url('assets/style.css') ?>"></head>
<body><main class="card">
    <h1><?= $product ? 'Edit' : 'Add' ?> Product</h1>
    <?php if ($errors): ?><div class="alert error"><ul><?php foreach ($errors as $item): ?><li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post" action="<?= $form_action ?>">
        <label>Product name<input name="product_name" maxlength="100" required value="<?= htmlspecialchars($product['product_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></label>
        <label>Description<textarea name="description" required><?= htmlspecialchars($product['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></label>
        <label>Product image URL <span class="hint">(optional)</span><input type="url" name="image_url" placeholder="https://example.com/product.jpg" value="<?= htmlspecialchars($product['image_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></label>
        <label>Price<input type="number" name="price" min="0" step="0.01" required value="<?= htmlspecialchars($product['price'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></label>
        <label>Quantity<input type="number" name="quantity" min="0" step="1" required value="<?= htmlspecialchars($product['quantity'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></label>
        <button type="submit">Save</button> <a href="<?= site_url('products') ?>">Cancel</a>
    </form>
</main></body></html>
