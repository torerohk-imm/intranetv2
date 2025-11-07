<?php
$config = require __DIR__ . '/../config/config.php';

$timezone = $config['timezone'] ?? 'America/Mexico_City';
date_default_timezone_set($timezone);

$sessionLifetime = (int)($config['security']['session_lifetime'] ?? 120) * 60;
$sessionOptions = [
    'cookie_lifetime' => $sessionLifetime,
    'cookie_secure' => !empty($config['security']['https']),
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'use_strict_mode' => true,
];

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start($sessionOptions);
}

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
