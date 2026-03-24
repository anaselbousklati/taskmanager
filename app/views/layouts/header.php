<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskManager</title>
    <link rel="stylesheet" href="<?= rtrim(getenv('APP_URL'), '/') ?>/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-brand">&#10003; TaskManager</div>
    <?php if (isLoggedIn()): ?>
        <div class="nav-links">
            <span class="nav-user">&#128100; <?= htmlspecialchars($_SESSION['user_name']) ?></span>
            <a href="index.php?page=tasks" class="nav-link">Mijn taken</a>
            <a href="index.php?page=settings" class="nav-link">Instellingen</a>
            <a href="index.php?page=logout" class="nav-link nav-logout">Uitloggen</a>
        </div>
    <?php endif; ?>
</nav>

<main class="container">

    <?php
    $flash = getFlash();
    if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?>">
            <?= htmlspecialchars($flash['msg']) ?>
        </div>
    <?php endif; ?>
