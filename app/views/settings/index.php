<?php require APP . '/views/layouts/header.php'; ?>

<div class="page-header">
    <h1>Instellingen</h1>
</div>

<div class="card" style="max-width:500px;">
    <form method="POST" action="index.php?page=settings&action=update">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div class="form-group">
            <label for="theme">Thema</label>
            <select id="theme" name="theme" class="form-control">
                <option value="light" <?= ($settings['theme'] === 'light' ? 'selected' : '') ?>>Licht</option>
                <option value="dark"  <?= ($settings['theme'] === 'dark'  ? 'selected' : '') ?>>Donker</option>
            </select>
        </div>

        <div class="form-group">
            <label for="language">Taal</label>
            <select id="language" name="language" class="form-control">
                <option value="nl" <?= ($settings['language'] === 'nl' ? 'selected' : '') ?>>Nederlands</option>
                <option value="en" <?= ($settings['language'] === 'en' ? 'selected' : '') ?>>Engels</option>
            </select>
        </div>

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                <input type="checkbox"
                       name="notifications"
                       value="1"
                       <?= ($settings['notifications'] ? 'checked' : '') ?>>
                Meldingen inschakelen
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Opslaan</button>
            <a href="index.php?page=tasks" class="btn btn-secondary">Annuleren</a>
        </div>
    </form>
</div>

<?php require APP . '/views/layouts/footer.php'; ?>
