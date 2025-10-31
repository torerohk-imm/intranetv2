<?php
namespace App\Models;

class Role extends Model
{
    protected string $table = 'roles';

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
