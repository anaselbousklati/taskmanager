<?php


class Category
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }


    public function getAllByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, COUNT(t.id) AS task_count
             FROM categories c
             LEFT JOIN tasks t ON c.id = t.category_id AND t.status != 'gedaan'
             WHERE c.user_id = :user_id
             GROUP BY c.id
             ORDER BY c.name ASC"
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }


    public function create(int $userId, string $name, string $color): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO categories (user_id, name, color) VALUES (:user_id, :name, :color)"
        );
        return $stmt->execute([
            ':user_id' => $userId,
            ':name'    => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            ':color'   => $color,
        ]);
    }

 
    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM categories WHERE id = :id AND user_id = :user_id"
        );
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
}
