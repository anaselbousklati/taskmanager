<?php

class Tag
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM tags ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByTask(int $taskId): array
    {
        $stmt = $this->db->prepare(
            "SELECT t.* FROM tags t
             INNER JOIN task_tag tt ON t.id = tt.tag_id
             WHERE tt.task_id = :task_id"
        );
        $stmt->execute([':task_id' => $taskId]);
        return $stmt->fetchAll();
    }

    public function syncTaskTags(int $taskId, array $tagIds): void
    {
        $stmt = $this->db->prepare("DELETE FROM task_tag WHERE task_id = :task_id");
        $stmt->execute([':task_id' => $taskId]);

        if (empty($tagIds)) {
            return;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO task_tag (task_id, tag_id) VALUES (:task_id, :tag_id)"
        );

        foreach ($tagIds as $tagId) {
            $stmt->execute([':task_id' => $taskId, ':tag_id' => (int)$tagId]);
        }
    }

    public function create(string $name, string $color): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO tags (name, color) VALUES (:name, :color)"
        );
        return $stmt->execute([
            ':name'  => htmlspecialchars(trim($name), ENT_QUOTES, 'UTF-8'),
            ':color' => $color,
        ]);
    }
}
