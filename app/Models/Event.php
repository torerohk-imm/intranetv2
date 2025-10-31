<?php
namespace App\Models;

class Event extends Model
{
    protected string $table = 'events';

    public function upcoming(int $limit = 5): array
    {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
