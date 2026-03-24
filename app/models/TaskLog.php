<?php

class TaskLog
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function log(int $taskId, string $oldStatus, string $newStatus): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO task_logs (task_id, old_status, new_status)
             VALUES (:task_id, :old_status, :new_status)"
        );
        $stmt->execute([
            ':task_id'    => $taskId,
            ':old_status' => $oldStatus,
            ':new_status' => $newStatus,
        ]);
    }

    public function getByTask(int $taskId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM task_logs WHERE task_id = :task_id ORDER BY changed_at ASC"
        );
        $stmt->execute([':task_id' => $taskId]);
        return $stmt->fetchAll();
    }
}
