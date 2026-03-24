<?php

class UserSettings
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM user_settings WHERE user_id = :user_id LIMIT 1"
        );
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch();

        if (!$result) {
            $this->createDefault($userId);
            return $this->getByUser($userId);
        }

        return $result;
    }

    public function update(int $userId, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE user_settings
             SET theme = :theme, language = :language, notifications = :notifications
             WHERE user_id = :user_id"
        );
        return $stmt->execute([
            ':user_id'       => $userId,
            ':theme'         => in_array($data['theme'] ?? '', ['light', 'dark']) ? $data['theme'] : 'light',
            ':language'      => in_array($data['language'] ?? '', ['nl', 'en']) ? $data['language'] : 'nl',
            ':notifications' => isset($data['notifications']) ? 1 : 0,
        ]);
    }

    private function createDefault(int $userId): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO user_settings (user_id, theme, language, notifications)
             VALUES (:user_id, 'light', 'nl', 1)"
        );
        $stmt->execute([':user_id' => $userId]);
    }
}
