<?php
$branding = $branding ?? ($config['branding'] ?? []);
$user = current_user();
$flashes = get_flashes();
?>
<!DOCTYPE html>
<?php $theme = $_SESSION['theme'] ?? 'light'; ?>
<html lang="es" data-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(($title ?? 'Intranet Corporativa Modular') . ' | ' . ($branding['site_name'] ?? 'Intranet')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/resources/css/app.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/axios.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js" defer></script>
    <script src="/resources/js/app.js" defer></script>
</head>
<body class="bg-soft <?= $theme === 'dark' ? 'theme-dark' : '' ?>" style="font-family: <?= $branding['font_family'] ?? 'Poppins, sans-serif' ?>;">
<div class="layout-wrapper">
    <header class="topbar shadow-soft">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link text-primary d-lg-none" id="toggleSidebar" aria-label="Abrir menú">
                    <i class="bi bi-list"></i>
                </button>
                <a href="/" class="navbar-brand d-flex align-items-center gap-2">
                    <img src="<?= htmlspecialchars($branding['logo_path'] ?? '/resources/img/logo.svg') ?>" alt="Logo" class="brand-logo">
                    <span class="fw-semibold text-primary"><?= htmlspecialchars($branding['site_name'] ?? 'Intranet Corporativa') ?></span>
                </a>
            </div>
            <nav class="top-menu d-none d-lg-flex gap-3">
                <a href="/" class="top-menu-link <?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                <a href="/calendario" class="top-menu-link <?= ($active ?? '') === 'calendar' ? 'active' : '' ?>">Calendario</a>
                <a href="/directorio" class="top-menu-link <?= ($active ?? '') === 'directory' ? 'active' : '' ?>">Directorio</a>
                <a href="/anuncios" class="top-menu-link <?= ($active ?? '') === 'announcements' ? 'active' : '' ?>">Anuncios</a>
                <a href="/organigrama" class="top-menu-link <?= ($active ?? '') === 'organigram' ? 'active' : '' ?>">Organigrama</a>
                <a href="/enlaces" class="top-menu-link <?= ($active ?? '') === 'quicklinks' ? 'active' : '' ?>">Enlaces</a>
                <a href="/sitios" class="top-menu-link <?= ($active ?? '') === 'embedded' ? 'active' : '' ?>">Sitios embebidos</a>
                <a href="/repositorio" class="top-menu-link <?= ($active ?? '') === 'documents' ? 'active' : '' ?>">Repositorio</a>
                <?php if ($user && $user['role_slug'] === 'admin-principal'): ?>
                    <a href="/administracion" class="top-menu-link <?= ($active ?? '') === 'admin' ? 'active' : '' ?>">Administración</a>
                <?php endif; ?>
            </nav>
            <div class="dropdown">
                <button class="btn btn-link text-dark d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div class="avatar placeholder"><?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?></div>
                    <div class="text-start">
                        <div class="fw-semibold small"><?= htmlspecialchars($user['name'] ?? 'Invitado') ?></div>
                        <div class="text-muted x-small"><?= htmlspecialchars($user['role_name'] ?? '') ?></div>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-soft">
                    <li><a class="dropdown-item" href="/perfil">Perfil</a></li>
                    <li><a class="dropdown-item" href="/configuracion">Preferencias</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/logout">Cerrar sesión</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="d-flex">
        <aside class="sidebar shadow-soft" id="sidebar">
            <div class="sidebar-content">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <span class="fw-semibold text-uppercase text-muted small">Navegación</span>
                    <button class="btn btn-sm btn-outline-primary" id="collapseSidebar">Ocultar</button>
                </div>
                <ul class="nav flex-column gap-1">
                    <li><a href="/" class="sidebar-link <?= ($active ?? '') === 'dashboard' ? 'active' : '' }"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                    <li><a href="/calendario" class="sidebar-link <?= ($active ?? '') === 'calendar' ? 'active' : '' }"><i class="bi bi-calendar-event"></i> Calendario</a></li>
                    <li><a href="/directorio" class="sidebar-link <?= ($active ?? '') === 'directory' ? 'active' : '' }"><i class="bi bi-people"></i> Directorio</a></li>
                    <li><a href="/anuncios" class="sidebar-link <?= ($active ?? '') === 'announcements' ? 'active' : '' }"><i class="bi bi-megaphone"></i> Anuncios</a></li>
                    <li><a href="/organigrama" class="sidebar-link <?= ($active ?? '') === 'organigram' ? 'active' : '' }"><i class="bi bi-diagram-3"></i> Organigrama</a></li>
                    <li><a href="/enlaces" class="sidebar-link <?= ($active ?? '') === 'quicklinks' ? 'active' : '' }"><i class="bi bi-grid"></i> Enlaces rápidos</a></li>
                    <li><a href="/sitios" class="sidebar-link <?= ($active ?? '') === 'embedded' ? 'active' : '' }"><i class="bi bi-window"></i> Sitios embebidos</a></li>
                    <li><a href="/repositorio" class="sidebar-link <?= ($active ?? '') === 'documents' ? 'active' : '' }"><i class="bi bi-folder2-open"></i> Repositorio</a></li>
                    <?php if ($user && $user['role_slug'] === 'admin-principal'): ?>
                        <li><a href="/administracion" class="sidebar-link <?= ($active ?? '') === 'admin' ? 'active' : '' }"><i class="bi bi-gear"></i> Administración</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </aside>
        <main class="content flex-grow-1">
            <div class="container-fluid py-4">
                <?php foreach ($flashes as $type => $messages): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="alert alert-<?= htmlspecialchars($type) ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?= $slot ?? '' ?>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
