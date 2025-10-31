<div class="text-center mb-4">
    <h1 class="h4 fw-semibold">Bienvenido a la Intranet</h1>
    <p class="text-muted small">Inicia sesión con tu correo corporativo.</p>
</div>
<form method="POST" action="/login" class="d-grid gap-3">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="form-floating">
        <input type="email" class="form-control" name="email" id="email" placeholder="correo@empresa.com" required>
        <label for="email">Correo electrónico</label>
    </div>
    <div class="form-floating">
        <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
        <label for="password">Contraseña</label>
    </div>
    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
</form>
