<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-semibold">Centro de administración</h1>
        <p class="text-muted mb-0">Configura la intranet y gestiona usuarios.</p>
    </div>
</div>
<div class="row g-4">
    <div class="col-xl-6">
        <div class="widget-card">
            <h2 class="h5 fw-semibold mb-3">Personalización del sitio</h2>
            <form method="POST" action="/administracion/branding" enctype="multipart/form-data" class="d-grid gap-3">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div>
                    <label class="form-label">Nombre del sitio</label>
                    <input type="text" name="site_name" class="form-control" value="<?= htmlspecialchars($branding['site_name'] ?? '') ?>">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Color primario</label>
                        <input type="color" name="primary_color" class="form-control form-control-color" value="<?= htmlspecialchars($branding['primary_color'] ?? '#051223') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Color secundario</label>
                        <input type="color" name="secondary_color" class="form-control form-control-color" value="<?= htmlspecialchars($branding['secondary_color'] ?? '#e41f0d') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Color de acento</label>
                        <input type="color" name="accent_color" class="form-control form-control-color" value="<?= htmlspecialchars($branding['accent_color'] ?? '#ffffff') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Color de fondo</label>
                        <input type="color" name="background_color" class="form-control form-control-color" value="<?= htmlspecialchars($branding['background_color'] ?? '#ffffff') ?>">
                    </div>
                </div>
                <div>
                    <label class="form-label">Tipografía</label>
                    <input type="text" name="font_family" class="form-control" value="<?= htmlspecialchars($branding['font_family'] ?? 'Poppins, sans-serif') ?>">
                </div>
                <div>
                    <label class="form-label">Logotipo</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </form>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="widget-card">
            <h2 class="h5 fw-semibold mb-3">Gestión de usuarios</h2>
            <form method="POST" action="/administracion/usuarios" class="row g-3 mb-4">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contraseña temporal</label>
                    <input type="password" name="password" class="form-control" placeholder="Secret123*">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rol</label>
                    <select name="role_id" class="form-select">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Crear usuario</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($user['role_name']) ?></span></td>
                                <td class="text-end">
                                    <form method="POST" action="/administracion/usuarios/eliminar" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar usuario?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="widget-card">
            <div class="row g-4">
                <div class="col-md-6">
                    <h2 class="h5 fw-semibold">Visitas totales</h2>
                    <p class="display-6 mb-0"><?= number_format($visits) ?></p>
                </div>
                <div class="col-md-6">
                    <h2 class="h5 fw-semibold">Usuarios conectados</h2>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($onlineUsers as $session): ?>
                            <li class="d-flex justify-content-between">
                                <span><?= htmlspecialchars($session['user_name']) ?></span>
                                <small class="text-muted">Último acceso: <?= date('H:i', strtotime($session['last_seen'])) ?></small>
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($onlineUsers)): ?>
                            <li class="text-muted">No hay usuarios conectados.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
