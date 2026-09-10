<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Products</title><link rel="stylesheet" href="<?= site_url('assets/style.css') ?>"></head>
<body><main class="container">
    <header><h1>Products</h1><nav><a class="button" href="<?= site_url('products/create') ?>">Add product</a> <a href="<?= site_url('logout') ?>">Log out</a></nav></header>
    <?php if ($message): ?><div class="alert success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <table><thead><tr><th>Image</th><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th><th>Created</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($products as $product): ?><tr>
        <td><?php if (!empty($product['image_url'])): ?><img class="product-thumb" src="<?= htmlspecialchars($product['image_url'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?>"><?php else: ?><span class="no-image">—</span><?php endif; ?></td>
        <td><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= nl2br(htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8')) ?></td>
        <td><?= number_format((float) $product['price'], 2) ?></td><td><?= (int) $product['quantity'] ?></td><td><?= htmlspecialchars($product['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><a href="<?= site_url('products/edit/' . (int) $product['id']) ?>">Edit</a>
            <form class="inline" method="post" action="<?= site_url('products/delete/' . (int) $product['id']) ?>"><button class="link danger-text" type="submit" onclick="return confirm('Delete this product?')">Delete</button></form></td>
    </tr><?php endforeach; ?>
    <?php if (!$products): ?><tr><td colspan="7">No products yet.</td></tr><?php endif; ?>
    </tbody></table>
</main></body></html>
