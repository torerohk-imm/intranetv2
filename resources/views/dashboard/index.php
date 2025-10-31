<?php
$user = current_user();
$widgets = $preferences['widgets'] ?? ['events', 'announcements', 'quicklinks', 'stats'];
$eventsJson = json_encode(array_map(fn($e) => [
    'title' => $e['title'],
    'start' => $e['event_date'],
    'description' => $e['description'] ?? ''
], $events));
?>
<div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-4 gap-3">
    <div>
        <h1 class="h3 fw-semibold mb-1">Hola, <?= htmlspecialchars($user['name']) ?> 👋</h1>
        <p class="text-muted mb-0">Esto es lo nuevo en la empresa.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#widgetConfig"><i class="bi bi-sliders"></i> Personalizar widgets</button>
        <a class="btn btn-primary" href="/calendario"><i class="bi bi-calendar-event"></i> Ver calendario</a>
    </div>
</div>
<div class="collapse mb-4" id="widgetConfig">
    <form method="POST" action="/dashboard/preferencias" class="widget-config">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <h2 class="h5 fw-semibold mb-3">Selecciona qué widgets quieres ver</h2>
        <div class="row g-3">
            <?php foreach (['events' => 'Eventos próximos', 'announcements' => 'Anuncios recientes', 'quicklinks' => 'Accesos rápidos', 'stats' => 'Estadísticas', 'birthdays' => 'Cumpleaños'] as $key => $label): ?>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="widgets[<?= $key ?>]" id="widget_<?= $key ?>" <?= in_array($key, $widgets, true) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="widget_<?= $key ?>"><?= $label ?></label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Guardar preferencias</button>
    </form>
</div>
<div class="widget-grid">
    <?php if (in_array('events', $widgets, true)): ?>
        <div class="widget-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-semibold mb-0"><i class="bi bi-calendar3 me-2"></i>Calendario</h2>
                <?php if (in_array($user['role_slug'], ['admin-principal', 'publicador'], true)): ?>
                    <a href="/calendario" class="btn btn-sm btn-outline-primary">Gestionar</a>
                <?php endif; ?>
            </div>
            <div id="eventsCalendar" data-events='<?= $eventsJson ?>'></div>
        </div>
    <?php endif; ?>

    <?php if (in_array('announcements', $widgets, true)): ?>
        <div class="widget-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-semibold mb-0"><i class="bi bi-megaphone me-2"></i>Anuncios</h2>
                <a href="/anuncios" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="d-grid gap-3">
                <?php foreach ($announcements as $announcement): ?>
                    <article class="p-3 rounded border bg-light">
                        <h3 class="h6 mb-1"><?= htmlspecialchars($announcement['title']) ?></h3>
                        <small class="text-muted">Publicado el <?= date('d/m/Y', strtotime($announcement['published_at'])) ?></small>
                        <p class="mt-2 mb-0 small text-muted"><?= nl2br(htmlspecialchars($announcement['content'])) ?></p>
                    </article>
                <?php endforeach; ?>
                <?php if (empty($announcements)): ?>
                    <p class="text-muted small mb-0">No hay anuncios disponibles.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (in_array('quicklinks', $widgets, true)): ?>
        <div class="widget-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-semibold mb-0"><i class="bi bi-grid me-2"></i>Accesos rápidos</h2>
                <a href="/enlaces" class="btn btn-sm btn-outline-primary">Administrar</a>
            </div>
            <div class="row g-3">
                <?php foreach ($quickLinks as $link): ?>
                    <div class="col-md-6">
                        <a class="quicklink-card d-block" href="<?= htmlspecialchars($link['url']) ?>" target="<?= htmlspecialchars($link['target']) ?>">
                            <strong class="d-block text-truncate"><?= htmlspecialchars($link['name']) ?></strong>
                            <span class="small text-muted text-truncate"><?= htmlspecialchars($link['url']) ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($quickLinks)): ?>
                    <p class="text-muted small mb-0">Aún no hay enlaces configurados.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (in_array('stats', $widgets, true)): ?>
        <div class="widget-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-semibold mb-0"><i class="bi bi-graph-up me-2"></i>Estadísticas</h2>
            </div>
            <canvas id="statsChart" data-stats='<?= json_encode($stats) ?>'></canvas>
        </div>
    <?php endif; ?>

    <?php if (in_array('birthdays', $widgets, true)): ?>
        <div class="widget-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-semibold mb-0"><i class="bi bi-gift me-2"></i>Cumpleaños de la semana</h2>
            </div>
            <ul class="list-unstyled mb-0">
                <?php foreach ($contacts as $contact): ?>
                    <?php if (!empty($contact['birthday']) && (int)date('W', strtotime($contact['birthday'])) === (int)date('W')): ?>
                        <li class="py-2 border-bottom">
                            <strong><?= htmlspecialchars($contact['name']) ?></strong>
                            <span class="d-block small text-muted"><?= date('d/m', strtotime($contact['birthday'])) ?></span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <li class="py-2" id="phraseOfDay">Cargando frase del día...</li>
            </ul>
        </div>
    <?php endif; ?>
</div>
