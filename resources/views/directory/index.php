<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-semibold">Directorio corporativo</h1>
        <p class="text-muted mb-0">Encuentra rápidamente la información de contacto.</p>
    </div>
    <form class="d-flex gap-2" method="GET" action="/directorio">
        <input type="search" class="form-control" name="q" placeholder="Buscar por nombre, puesto o correo" value="<?= htmlspecialchars($term) ?>">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>
<div class="widget-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 fw-semibold mb-0">Colaboradores</h2>
        <?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#contactModal">Agregar contacto</button>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-neumorphism align-middle mb-0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Puesto</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Ext.</th>
                    <?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?><th class="text-end">Acciones</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['name']) ?></td>
                        <td><?= htmlspecialchars($contact['position']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="text-decoration-none"><?= htmlspecialchars($contact['email']) ?></a></td>
                        <td><?= htmlspecialchars($contact['phone']) ?></td>
                        <td><?= htmlspecialchars($contact['extension']) ?></td>
                        <?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
                            <td class="text-end">
                                <form method="POST" action="/directorio/eliminar" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar contacto?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($contacts)): ?>
            <p class="text-muted text-center py-4 mb-0">No se encontraron resultados.</p>
        <?php endif; ?>
    </div>
</div>
<?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/directorio">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Nuevo contacto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Puesto</label>
                        <input type="text" name="position" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Extensión</label>
                        <input type="text" name="extension" class="form-control">
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
