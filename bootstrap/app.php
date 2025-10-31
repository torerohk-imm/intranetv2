<?php
session_start([
    'cookie_lifetime' => 60 * 60 * 2,
    'cookie_secure' => false,
    'cookie_httponly' => true,
    'use_strict_mode' => true,
]);

date_default_timezone_set('America/Mexico_City');

$config = require __DIR__ . '/../config/config.php';
$GLOBALS['config'] = $config;

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

require_once __DIR__ . '/../app/Support/helpers.php';

use App\Support\Database;

Database::init($config['database']);

try {
    $stmt = Database::connection()->query('SELECT `key`, `value` FROM branding_settings');
    $branding = [];
    foreach ($stmt->fetchAll() as $row) {
        $branding[$row['key']] = $row['value'];
    }
    if ($branding) {
        $config['branding'] = array_merge($config['branding'], $branding);
        $GLOBALS['config'] = $config;
    }
} catch (\Throwable $th) {
    // la tabla aún no existe, continuar con valores por defecto
}

return $config;
