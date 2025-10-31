<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-semibold">Sitios web embebidos</h1>
        <p class="text-muted mb-0">Trabaja en tus herramientas sin salir de la intranet.</p>
    </div>
    <?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#siteModal"><i class="bi bi-plus-lg"></i> Agregar sitio</button>
    <?php endif; ?>
</div>
<div class="widget-card">
    <div class="embed-grid">
        <?php foreach ($sites as $site): ?>
            <div>
                <h2 class="h6 fw-semibold mb-2"><?= htmlspecialchars($site['name']) ?></h2>
                <iframe src="<?= htmlspecialchars($site['url']) ?>" style="height: <?= (int)$site['height'] ?>px;"></iframe>
            </div>
        <?php endforeach; ?>
        <?php if (empty($sites)): ?>
            <p class="text-muted">No hay sitios registrados.</p>
        <?php endif; ?>
    </div>
</div>
<?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
<div class="modal fade" id="siteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/sitios">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Nuevo sitio embebido</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL</label>
                        <input type="url" name="url" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alto (px)</label>
                        <input type="number" name="height" class="form-control" value="320">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
