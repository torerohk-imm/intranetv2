<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-semibold">Organigrama</h1>
        <p class="text-muted mb-0">Visualiza la estructura jerárquica de la organización.</p>
    </div>
    <?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nodeModal"><i class="bi bi-plus-lg"></i> Agregar nodo</button>
    <?php endif; ?>
</div>
<div class="widget-card overflow-auto">
    <?php $renderNode = function ($node) use (&$renderNode) { ?>
        <div class="organigram-node text-center">
            <?php if ($node['photo_path']): ?>
                <img src="<?= htmlspecialchars($node['photo_path']) ?>" alt="Foto de <?= htmlspecialchars($node['name']) ?>" class="rounded-circle mb-2" style="width:72px;height:72px;object-fit:cover;">
            <?php endif; ?>
            <h2 class="h6 fw-semibold mb-0"><?= htmlspecialchars($node['name']) ?></h2>
            <p class="text-muted small mb-1"><?= htmlspecialchars($node['position']) ?></p>
            <span class="badge bg-light text-dark small"><?= htmlspecialchars($node['department']) ?></span>
            <?php if (!empty($node['children'])): ?>
                <div class="organigram-children mt-3" id="children_<?= $node['id'] ?>">
                    <?php foreach ($node['children'] as $child): ?>
                        <?= $renderNode($child) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php }; ?>
    <div class="d-flex justify-content-center flex-wrap">
        <?php foreach ($nodes as $node): ?>
            <?= $renderNode($node) ?>
        <?php endforeach; ?>
        <?php if (empty($nodes)): ?>
            <p class="text-muted">Aún no se han registrado posiciones.</p>
        <?php endif; ?>
    </div>
</div>
<?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
<div class="modal fade" id="nodeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/organigrama" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Agregar colaborador</h1>
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
                        <label class="form-label">Departamento</label>
                        <input type="text" name="department" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jefe directo</label>
                        <input type="number" name="parent_id" class="form-control" placeholder="ID del jefe (opcional)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fotografía</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
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
