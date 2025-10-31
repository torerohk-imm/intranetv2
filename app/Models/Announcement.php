<?php
namespace App\Models;

class Announcement extends Model
{
    protected string $table = 'announcements';

    public function latest(int $limit = 6): array
    {
        $stmt = $this->db->prepare("SELECT * FROM announcements ORDER BY published_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
