<?php
namespace App\Models;

use PDO;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT u.id, u.name, u.email, u.password, u.role_id, r.name AS role_name, r.slug AS role_slug FROM users u JOIN roles r ON r.id = u.role_id WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function allWithRoles(): array
    {
        $stmt = $this->db->query("SELECT u.id, u.name, u.email, u.role_id, r.name AS role_name, r.slug AS role_slug FROM users u JOIN roles r ON r.id = u.role_id ORDER BY u.name");
        return $stmt->fetchAll();
    }
}
