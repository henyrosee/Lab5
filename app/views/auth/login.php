<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login - Product Manager</title><link rel="stylesheet" href="<?= site_url('assets/style.css') ?>"></head>
<body><main class="card narrow">
    <h1>Product Manager</h1><p>Sign in to manage products.</p>
    <?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <form method="post" action="<?= site_url('login') ?>">
        <label>Email<input type="email" name="email" required></label>
        <label>Password<input type="password" name="password" required></label>
        <button type="submit">Log in</button>
    </form>
</main></body></html>
