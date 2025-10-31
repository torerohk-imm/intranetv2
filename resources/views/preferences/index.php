<div class="row g-4">
    <div class="col-lg-6">
        <div class="widget-card">
            <h1 class="h5 fw-semibold mb-3">Tema de la interfaz</h1>
            <form method="POST" action="/configuracion">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="themeSwitch" name="theme" value="dark" <?= ($preferences['theme'] ?? 'light') === 'dark' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="themeSwitch">Activar modo oscuro</label>
                </div>
                <button type="submit" class="btn btn-primary">Guardar preferencias</button>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="widget-card">
            <h2 class="h5 fw-semibold mb-2">Mis widgets</h2>
            <p class="text-muted small mb-0">Gestiona los widgets desde el dashboard y se guardarán automáticamente.</p>
        </div>
    </div>
</div>
