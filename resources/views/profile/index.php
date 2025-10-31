<div class="widget-card">
    <h1 class="h4 fw-semibold mb-3">Información personal</h1>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label text-muted">Nombre</label>
            <p class="mb-0 fw-semibold"><?= htmlspecialchars($user['name']) ?></p>
        </div>
        <div class="col-md-6">
            <label class="form-label text-muted">Correo</label>
            <p class="mb-0 fw-semibold"><?= htmlspecialchars($user['email']) ?></p>
        </div>
        <div class="col-md-6">
            <label class="form-label text-muted">Rol</label>
            <p class="mb-0"><span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($user['role_name']) ?></span></p>
        </div>
    </div>
</div>
