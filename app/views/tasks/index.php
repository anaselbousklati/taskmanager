<?php require APP . '/views/layouts/header.php'; ?>

<div class="page-header">
    <h1>Mijn taken</h1>
    <a href="index.php?page=tasks&action=create" class="btn btn-primary">+ Nieuwe taak</a>
</div>

<!-- Statistieken dashboard -->
<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-number"><?= (int)$stats['totaal'] ?></span>
        <span class="stat-label">Totaal</span>
    </div>
    <div class="stat-card stat-open">
        <span class="stat-number"><?= (int)$stats['open'] ?></span>
        <span class="stat-label">Open</span>
    </div>
    <div class="stat-card stat-bezig">
        <span class="stat-number"><?= (int)$stats['bezig'] ?></span>
        <span class="stat-label">Bezig</span>
    </div>
    <div class="stat-card stat-gedaan">
        <span class="stat-number"><?= (int)$stats['gedaan'] ?></span>
        <span class="stat-label">Gedaan</span>
    </div>
    <?php if ($stats['verlopen'] > 0): ?>
    <div class="stat-card stat-urgent">
        <span class="stat-number"><?= (int)$stats['verlopen'] ?></span>
        <span class="stat-label">Verlopen</span>
    </div>
    <?php endif; ?>
</div>

<!-- Filterbar -->
<div class="filter-bar">
    <form method="GET" action="index.php" class="filter-form">
        <input type="hidden" name="page" value="tasks">

        <select name="status" onchange="this.form.submit()">
            <option value="">Alle statussen</option>
            <option value="open"   <?= ($status === 'open'   ? 'selected' : '') ?>>Open</option>
            <option value="bezig"  <?= ($status === 'bezig'  ? 'selected' : '') ?>>Bezig</option>
            <option value="gedaan" <?= ($status === 'gedaan' ? 'selected' : '') ?>>Gedaan</option>
        </select>

        <select name="priority" onchange="this.form.submit()">
            <option value="">Alle prioriteiten</option>
            <option value="hoog"   <?= ($priority === 'hoog'    ? 'selected' : '') ?>>Hoog</option>
            <option value="normaal" <?= ($priority === 'normaal' ? 'selected' : '') ?>>Normaal</option>
            <option value="laag"   <?= ($priority === 'laag'    ? 'selected' : '') ?>>Laag</option>
        </select>

        <select name="sort" onchange="this.form.submit()">
            <option value="deadline"   <?= ($sort === 'deadline'   ? 'selected' : '') ?>>Sorteren: Deadline</option>
            <option value="priority"   <?= ($sort === 'priority'   ? 'selected' : '') ?>>Sorteren: Prioriteit</option>
            <option value="created_at" <?= ($sort === 'created_at' ? 'selected' : '') ?>>Sorteren: Aangemaakt</option>
            <option value="title"      <?= ($sort === 'title'      ? 'selected' : '') ?>>Sorteren: Titel</option>
        </select>
    </form>
</div>

<!-- Takentabel -->
<?php if (empty($tasks)): ?>
    <div class="empty-state">
        <p>Geen taken gevonden.</p>
        <a href="index.php?page=tasks&action=create" class="btn btn-primary">Maak je eerste taak aan</a>
    </div>
<?php else: ?>
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Taak</th>
                    <th>Categorie</th>
                    <th>Prioriteit</th>
                    <th>Status</th>
                    <th>Deadline</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                <tr class="<?= ($task['status'] === 'gedaan') ? 'row-done' : '' ?>
                           <?= isOverdue($task['deadline'], $task['status']) ? 'row-overdue' : '' ?>">
                    <td>
                        <strong><?= htmlspecialchars($task['title']) ?></strong>
                        <?php if (!empty($task['description'])): ?>
                            <br><small class="text-muted"><?= htmlspecialchars(mb_strimwidth($task['description'], 0, 60, '...')) ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($task['category_name']): ?>
                            <span class="category-dot" style="background:<?= htmlspecialchars($task['category_color']) ?>"></span>
                            <?= htmlspecialchars($task['category_name']) ?>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge <?= priorityBadge($task['priority']) ?>"><?= ucfirst($task['priority']) ?></span></td>
                    <td><span class="badge <?= statusBadge($task['status']) ?>"><?= ucfirst($task['status']) ?></span></td>
                    <td class="<?= isOverdue($task['deadline'], $task['status']) ? 'text-danger' : '' ?>">
                        <?= formatDate($task['deadline']) ?>
                        <?= isOverdue($task['deadline'], $task['status']) ? ' ⚠️' : '' ?>
                    </td>
                    <td class="actions">
                        <a href="index.php?page=tasks&action=edit&id=<?= $task['id'] ?>" class="btn btn-sm btn-secondary">Bewerk</a>
                        <form method="POST" action="index.php?page=tasks&action=delete&id=<?= $task['id'] ?>"
                              style="display:inline"
                              onsubmit="return confirm('Taak verwijderen?')">
                            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Verwijder</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require APP . '/views/layouts/footer.php'; ?>
