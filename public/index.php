<?php
$config = require __DIR__ . '/../bootstrap/app.php';

$routes = require __DIR__ . '/../routes/web.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// registrar visitas y sesiones activas
try {
    db()->prepare('INSERT INTO metrics (`key`, `value`) VALUES ("visits", 1) ON DUPLICATE KEY UPDATE value = value + 1')->execute();
    if ($user = current_user()) {
        db()->prepare('INSERT INTO sessions (user_id, user_name, last_seen) VALUES (:id, :name, NOW()) ON DUPLICATE KEY UPDATE last_seen = NOW()')
            ->execute(['id' => $user['id'], 'name' => $user['name']]);
    }
} catch (Throwable $e) {
    // ignore until migrations executed
}

foreach ($routes as $route) {
    [$routeMethod, $routePath, $handler] = $route;
    if ($routeMethod === $method && rtrim($routePath, '/') === rtrim($path, '/')) {
        [$class, $action] = $handler;
        $controller = new $class($config);
        return $controller->$action();
    }
}

http_response_code(404);
echo 'Página no encontrada';
