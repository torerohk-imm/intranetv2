<?php
namespace App\Models;

use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $columns = array_keys($data);
        $fields = implode(', ', $columns);
        $placeholders = implode(', ', array_map(fn($col) => ':' . $col, $columns));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})");
        $stmt->execute($data);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $assignments = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));
        $data['id'] = $id;
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$assignments} WHERE id = :id");
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
