<?php
namespace App\Models;

class UserPreference extends Model
{
    protected string $table = 'user_preferences';

    public function forUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM user_preferences WHERE user_id = :user LIMIT 1");
        $stmt->execute(['user' => $userId]);
        $result = $stmt->fetch();
        return $result ? json_decode($result['preferences'], true) : [];
    }

    public function store(int $userId, array $preferences): void
    {
        $existing = $this->db->prepare("SELECT id FROM user_preferences WHERE user_id = :user");
        $existing->execute(['user' => $userId]);
        $payload = json_encode($preferences, JSON_UNESCAPED_UNICODE);
        if ($existing->fetch()) {
            $this->db->prepare("UPDATE user_preferences SET preferences = :prefs WHERE user_id = :user")
                ->execute(['prefs' => $payload, 'user' => $userId]);
        } else {
            $this->create(['user_id' => $userId, 'preferences' => $payload]);
        }
    }
}
