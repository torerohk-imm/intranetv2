<?php
namespace App\Models;

class QuickLink extends Model
{
    protected string $table = 'quick_links';

    public function forUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM quick_links WHERE visibility = 'global' OR (visibility = 'personal' AND user_id = :user) ORDER BY category, name");
        $stmt->execute(['user' => $userId]);
        return $stmt->fetchAll();
    }
}
