<?php


class Task
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
     
    public function getAllByUser(int $userId, string $status = '', string $priority = '', string $sort = 'deadline'): array
    {
        $sql = "SELECT t.*, c.name AS category_name, c.color AS category_color
                FROM tasks t
                LEFT JOIN categories c ON t.category_id = c.id
                WHERE t.user_id = :user_id";

        $params = [':user_id' => $userId];

        if (!empty($status)) {
            $sql .= " AND t.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($priority)) {
            $sql .= " AND t.priority = :priority";
            $params[':priority'] = $priority;
        }

        $allowedSorts = ['deadline', 'priority', 'created_at', 'title'];
        $sortColumn   = in_array($sort, $allowedSorts) ? $sort : 'deadline';
        $sql .= " ORDER BY $sortColumn ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id, int $userId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT t.*, c.name AS category_name
             FROM tasks t
             LEFT JOIN categories c ON t.category_id = c.id
             WHERE t.id = :id AND t.user_id = :user_id"
        );
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO tasks (user_id, category_id, title, description, priority, status, deadline)
             VALUES (:user_id, :category_id, :title, :description, :priority, :status, :deadline)"
        );

        return $stmt->execute([
            ':user_id'     => $data['user_id'],
            ':category_id' => !empty($data['category_id']) ? $data['category_id'] : null,
            ':title'       => $data['title'],
            ':description' => $data['description'] ?? null,
            ':priority'    => $data['priority'] ?? 'normaal',
            ':status'      => $data['status'] ?? 'open',
            ':deadline'    => !empty($data['deadline']) ? $data['deadline'] : null,
        ]);
    }

    public function update(int $id, int $userId, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE tasks
             SET category_id = :category_id,
                 title       = :title,
                 description = :description,
                 priority    = :priority,
                 status      = :status,
                 deadline    = :deadline
             WHERE id = :id AND user_id = :user_id"
        );

        return $stmt->execute([
            ':id'          => $id,
            ':user_id'     => $userId,
            ':category_id' => !empty($data['category_id']) ? $data['category_id'] : null,
            ':title'       => $data['title'],
            ':description' => $data['description'] ?? null,
            ':priority'    => $data['priority'] ?? 'normaal',
            ':status'      => $data['status'] ?? 'open',
            ':deadline'    => !empty($data['deadline']) ? $data['deadline'] : null,
        ]);
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM tasks WHERE id = :id AND user_id = :user_id"
        );
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }

    public function getStats(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                COUNT(*)                                        AS totaal,
                SUM(status = 'open')                           AS open,
                SUM(status = 'bezig')                          AS bezig,
                SUM(status = 'gedaan')                         AS gedaan,
                SUM(priority = 'hoog' AND status != 'gedaan')  AS urgent,
                SUM(deadline < CURDATE() AND status != 'gedaan') AS verlopen
             FROM tasks
             WHERE user_id = :user_id"
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch();
    }
}
