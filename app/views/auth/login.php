<?php require APP . '/views/layouts/header.php'; ?>

<div class="auth-wrapper">
    <div class="card auth-card">
        <h2 class="auth-title">✓ TaskManager</h2>
        <p class="auth-subtitle">Inloggen</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=login">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       autocomplete="email"
                       required>
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control"
                       autocomplete="current-password"
                       required>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Inloggen</button>
        </form>

        <p class="auth-link">Nog geen account? <a href="index.php?page=register">Registreren</a></p>

        <div class="demo-hint">
            <strong>Demo account:</strong><br>
            demo@example.com / demo1234
        </div>
    </div>
</div>

<?php require APP . '/views/layouts/footer.php'; ?>
