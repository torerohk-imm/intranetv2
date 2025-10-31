<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-semibold">Tablón de anuncios</h1>
        <p class="text-muted mb-0">Comparte novedades y oportunidades con la organización.</p>
    </div>
    <form class="d-flex align-items-center gap-2" method="GET" action="/anuncios">
        <label class="form-label mb-0 small text-muted">Ordenar</label>
        <select name="order" class="form-select" onchange="this.form.submit()">
            <option value="desc" <?= $order === 'desc' ? 'selected' : '' ?>>Más recientes primero</option>
            <option value="asc" <?= $order === 'asc' ? 'selected' : '' ?>>Más antiguos primero</option>
        </select>
    </form>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="d-grid gap-4">
            <?php foreach ($announcements as $announcement): ?>
                <article class="widget-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h2 class="h5 fw-semibold mb-1"><?= htmlspecialchars($announcement['title']) ?></h2>
                            <small class="text-muted">Publicado el <?= date('d/m/Y H:i', strtotime($announcement['published_at'])) ?></small>
                        </div>
                        <?php if ($announcement['image_path']): ?>
                            <img src="<?= htmlspecialchars($announcement['image_path']) ?>" alt="Imagen del anuncio" class="rounded" style="width: 96px; height: 96px; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                    <p class="mb-3">
                        <?= nl2br(htmlspecialchars($announcement['content'])) ?>
                    </p>
                    <?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
                        <form method="POST" action="/anuncios/eliminar" class="d-flex gap-2">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="id" value="<?= $announcement['id'] ?>">
                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar anuncio?')">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
            <?php if (empty($announcements)): ?>
                <p class="text-muted">No hay anuncios publicados.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
            <div class="widget-card">
                <h2 class="h5 fw-semibold mb-3">Publicar anuncio</h2>
                <form method="POST" action="/anuncios" enctype="multipart/form-data" class="d-grid gap-3">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <div>
                        <label class="form-label">Título</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Contenido</label>
                        <textarea name="content" class="form-control" rows="4" required></textarea>
                    </div>
                    <div>
                        <label class="form-label">Imagen (opcional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </form>
            </div>
        <?php else: ?>
            <div class="widget-card">
                <h2 class="h5 fw-semibold mb-3">Consejos</h2>
                <p class="text-muted mb-0 small">Mantente al tanto de las oportunidades internas y comparte tus logros con el equipo.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
