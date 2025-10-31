<?php
namespace App\Models;

class Contact extends Model
{
    protected string $table = 'contacts';

    public function search(string $term): array
    {
        $stmt = $this->db->prepare("SELECT * FROM contacts WHERE name LIKE :term OR position LIKE :term OR email LIKE :term ORDER BY name");
        $stmt->execute(['term' => "%{$term}%"]);
        return $stmt->fetchAll();
    }
}
