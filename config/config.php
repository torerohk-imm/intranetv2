<?php
return [
    'name' => env('APP_NAME', 'Intranet Corporativa Modular'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'locale' => 'es',
    'fallback_locale' => 'es',
    'timezone' => 'America/Mexico_City',
    'branding' => [
        'primary_color' => '#051223',
        'secondary_color' => '#e41f0d',
        'accent_color' => '#ffffff',
        'background_color' => '#ffffff',
        'font_family' => '"Poppins", "Segoe UI", sans-serif',
        'logo_path' => '/storage/uploads/branding/logo.png',
    ],
    'database' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'intranet'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    'security' => [
        'session_lifetime' => 120,
        'https' => env('APP_HTTPS', true),
    ],
];

function env(string $key, $default = null)
{
    $value = $_ENV[$key] ?? getenv($key);
    if ($value === false || $value === null) {
        return $default;
    }
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'null':
        case '(null)':
            return null;
    }
    return $value;
}
