<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-semibold">Calendario de eventos</h1>
        <p class="text-muted mb-0">Organiza las actividades corporativas.</p>
    </div>
    <?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal"><i class="bi bi-plus-lg"></i> Nuevo evento</button>
    <?php endif; ?>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="widget-card">
            <div id="eventsCalendar" data-events='<?= json_encode(array_map(fn($e) => [
                'title' => $e['title'],
                'start' => $e['event_date'],
                'description' => $e['description'],
            ], $events)) ?>'></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="widget-card">
            <h2 class="h5 fw-semibold mb-3">Próximos eventos</h2>
            <ul class="list-unstyled mb-0">
                <?php foreach ($events as $event): ?>
                    <li class="mb-3">
                        <h3 class="h6 mb-1"><?= htmlspecialchars($event['title']) ?></h3>
                        <span class="text-muted small"><?= date('d/m/Y', strtotime($event['event_date'])) ?></span>
                        <p class="small mt-2 mb-0"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="/calendario">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Nuevo evento</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Título</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="event_date" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                        </div>
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
