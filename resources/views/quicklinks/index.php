<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-semibold">Botonera de enlaces rápidos</h1>
        <p class="text-muted mb-0">Accede de inmediato a tus herramientas favoritas.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#linkModal"><i class="bi bi-plus-lg"></i> Nuevo enlace</button>
</div>
<div class="row g-4">
    <div class="col-xl-6">
        <div class="widget-card">
            <h2 class="h5 fw-semibold mb-3">Generales</h2>
            <div class="row g-3">
                <?php foreach ($links as $link): ?>
                    <?php if ($link['visibility'] === 'global'): ?>
                        <div class="col-md-6">
                            <a href="<?= htmlspecialchars($link['url']) ?>" target="<?= htmlspecialchars($link['target']) ?>" class="quicklink-card d-block">
                                <strong class="text-truncate d-block"><?= htmlspecialchars($link['name']) ?></strong>
                                <span class="small text-muted text-truncate"><?= htmlspecialchars($link['url']) ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!array_filter($links, fn($link) => $link['visibility'] === 'global')): ?>
                    <p class="text-muted small mb-0">Aún no hay enlaces generales.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="widget-card">
            <h2 class="h5 fw-semibold mb-3">Mis enlaces personales</h2>
            <div class="row g-3">
                <?php foreach ($links as $link): ?>
                    <?php if ($link['visibility'] === 'personal'): ?>
                        <div class="col-md-6">
                            <div class="quicklink-card">
                                <a href="<?= htmlspecialchars($link['url']) ?>" target="<?= htmlspecialchars($link['target']) ?>" class="stretched-link text-decoration-none">
                                    <strong class="text-truncate d-block"><?= htmlspecialchars($link['name']) ?></strong>
                                    <span class="small text-muted text-truncate"><?= htmlspecialchars($link['url']) ?></span>
                                </a>
                                <form method="POST" action="/enlaces/eliminar" class="mt-2">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <input type="hidden" name="id" value="<?= $link['id'] ?>">
                                    <button class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('¿Eliminar enlace?')">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!array_filter($links, fn($link) => $link['visibility'] === 'personal')): ?>
                    <p class="text-muted small mb-0">Aún no has registrado enlaces personales.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="linkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/enlaces">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Nuevo enlace</h1>
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
                        <label class="form-label">Abrir en</label>
                        <select name="target" class="form-select">
                            <option value="_blank">Nueva pestaña</option>
                            <option value="_self">Misma pestaña</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Visibilidad</label>
                        <select name="visibility" class="form-select">
                            <option value="personal">Solo yo</option>
                            <?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
                                <option value="global">Todos los usuarios</option>
                            <?php endif; ?>
                        </select>
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
