<?php
namespace App\Models;

class Document extends Model
{
    protected string $table = 'documents';

    public function forFolder(?int $folderId): array
    {
        if ($folderId) {
            $stmt = $this->db->prepare("SELECT * FROM documents WHERE folder_id = :folder ORDER BY name");
            $stmt->execute(['folder' => $folderId]);
        } else {
            $stmt = $this->db->query("SELECT * FROM documents WHERE folder_id IS NULL ORDER BY name");
        }
        return $stmt->fetchAll();
    }
}
