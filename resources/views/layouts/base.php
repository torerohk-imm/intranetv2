<?php $branding = $branding ?? ['logo_path' => '/resources/img/logo.svg']; ?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Intranet Corporativa Modular') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/resources/css/app.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center bg-soft">
        <div class="card shadow-soft p-4 w-100" style="max-width: 420px;">
            <div class="text-center mb-4">
                <img src="<?= htmlspecialchars($branding['logo_path'] ?? '/resources/img/logo.svg') ?>" alt="Logo" class="img-fluid" style="max-height: 90px;">
            </div>
            <?php if (!empty($message)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <?php if (!empty($slot)): ?>
                <?= $slot ?>
            <?php else: ?>
                <h1 class="h4 text-center mb-3"><?= htmlspecialchars($title ?? 'Intranet Corporativa Modular') ?></h1>
                <p class="text-muted text-center">Acceso restringido. Comunícate con el administrador.</p>
            <?php endif; ?>
            <div class="text-center mt-4">
                <a href="/" class="btn btn-primary">Volver al inicio</a>
            </div>
        </div>
    </div>
</body>
</html>
