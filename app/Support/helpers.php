<?php
use App\Support\Database;

function view(string $template, array $data = [])
{
    extract($data);
    $config = $GLOBALS['config'] ?? [];
    $viewFile = __DIR__ . '/../../resources/views/' . $template . '.php';
    if (!file_exists($viewFile)) {
        throw new RuntimeException("Vista {$template} no encontrada");
    }
    ob_start();
    include $viewFile;
    return ob_get_clean();
}

function render(string $template, array $data = []): void
{
    echo view($template, $data);
}

function redirect(string $route): void
{
    header('Location: ' . $route);
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function require_auth(): void
{
    if (!current_user()) {
        redirect('/login');
    }
}

function authorize(array $roles): void
{
    require_auth();
    $user = current_user();
    if (!in_array($user['role_slug'], $roles, true)) {
        http_response_code(403);
        render('errors/403', ['title' => 'Acceso denegado']);
        exit;
    }
}

function db(): PDO
{
    return Database::connection();
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][$type][] = $message;
}

function get_flashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

function asset(string $path): string
{
    return $path;
}

function storage_path(string $path = ''): string
{
    return __DIR__ . '/../../storage/' . ltrim($path, '/');
}

function public_path(string $path = ''): string
{
    return __DIR__ . '/../../public/' . ltrim($path, '/');
}

function base_path(string $path = ''): string
{
    return __DIR__ . '/../../' . ltrim($path, '/');
}

function translate(string $key): string
{
    $langFile = base_path('lang/es/messages.php');
    static $translations;
    if (!$translations) {
        $translations = file_exists($langFile) ? require $langFile : [];
    }
    return $translations[$key] ?? $key;
}
