<?php
$isEdit = isset($task);
$title  = $isEdit ? 'Taak bewerken' : 'Nieuwe taak';
$action = $isEdit
    ? "index.php?page=tasks&action=update&id={$task['id']}"
    : "index.php?page=tasks&action=store";

$v = $old ?? $task ?? [];

require APP . '/views/layouts/header.php';
?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="index.php?page=tasks" class="btn btn-secondary">← Terug</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul style="margin:0;padding-left:1.2rem;">
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card" style="max-width:600px;">
    <form method="POST" action="<?= $action ?>">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div class="form-group">
            <label for="title">Titel <span class="required">*</span></label>
            <input type="text"
                   id="title"
                   name="title"
                   class="form-control"
                   value="<?= htmlspecialchars($v['title'] ?? '') ?>"
                   maxlength="255"
                   required>
        </div>

        <div class="form-group">
            <label for="description">Beschrijving</label>
            <textarea id="description"
                      name="description"
                      class="form-control"
                      rows="3"><?= htmlspecialchars($v['description'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category_id">Categorie</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="">Geen categorie</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= (($v['category_id'] ?? null) == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="priority">Prioriteit</label>
                <select id="priority" name="priority" class="form-control">
                    <option value="laag"    <?= (($v['priority'] ?? '') === 'laag'    ? 'selected' : '') ?>>Laag</option>
                    <option value="normaal" <?= (($v['priority'] ?? 'normaal') === 'normaal' ? 'selected' : '') ?>>Normaal</option>
                    <option value="hoog"   <?= (($v['priority'] ?? '') === 'hoog'    ? 'selected' : '') ?>>Hoog</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="open"   <?= (($v['status'] ?? 'open') === 'open'   ? 'selected' : '') ?>>Open</option>
                    <option value="bezig"  <?= (($v['status'] ?? '') === 'bezig'  ? 'selected' : '') ?>>Bezig</option>
                    <option value="gedaan" <?= (($v['status'] ?? '') === 'gedaan' ? 'selected' : '') ?>>Gedaan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date"
                       id="deadline"
                       name="deadline"
                       class="form-control"
                       value="<?= htmlspecialchars($v['deadline'] ?? '') ?>">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-full">
                <?= $isEdit ? 'Wijzigingen opslaan' : 'Taak aanmaken' ?>
            </button>
            <a href="index.php?page=tasks" class="btn btn-secondary btn-full">Annuleren</a>
        </div>
    </form>
</div>

<?php require APP . '/views/layouts/footer.php'; ?>
