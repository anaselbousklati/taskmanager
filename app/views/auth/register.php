<?php require APP . '/views/layouts/header.php'; ?>

<div class="auth-wrapper">
    <div class="card auth-card">
        <h2 class="auth-title">✓ TaskManager</h2>
        <p class="auth-subtitle">Account aanmaken</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul style="margin:0;padding-left:1.2rem;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=register">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div class="form-group">
                <label for="name">Naam</label>
                <input type="text"
                       id="name"
                       name="name"
                       class="form-control"
                       value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                       minlength="2"
                       maxlength="100"
                       required>
            </div>

            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control"
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                       autocomplete="email"
                       required>
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord <small>(minimaal 8 tekens)</small></label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control"
                       minlength="8"
                       autocomplete="new-password"
                       required>
            </div>

            <div class="form-group">
                <label for="password_confirm">Wachtwoord bevestigen</label>
                <input type="password"
                       id="password_confirm"
                       name="password_confirm"
                       class="form-control"
                       minlength="8"
                       autocomplete="new-password"
                       required>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Registreren</button>
        </form>

        <p class="auth-link">Al een account? <a href="index.php?page=login">Inloggen</a></p>
    </div>
</div>

<?php require APP . '/views/layouts/footer.php'; ?>
