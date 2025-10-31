<?php
namespace App\Models;

class BrandingSetting extends Model
{
    protected string $table = 'branding_settings';

    public function getSettings(): array
    {
        $settings = [];
        foreach ($this->all() as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }

    public function set(string $key, string $value): void
    {
        $existing = $this->db->prepare("SELECT id FROM branding_settings WHERE `key` = :key");
        $existing->execute(['key' => $key]);
        if ($existing->fetch()) {
            $this->db->prepare("UPDATE branding_settings SET value = :value WHERE `key` = :key")
                ->execute(['value' => $value, 'key' => $key]);
        } else {
            $this->create(['key' => $key, 'value' => $value]);
        }
    }
}
