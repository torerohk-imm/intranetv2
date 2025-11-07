<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-semibold">Repositorio de documentos</h1>
        <p class="text-muted mb-0">Comparte archivos con toda la organización.</p>
    </div>
    <?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#folderModal"><i class="bi bi-folder-plus"></i> Nueva carpeta</button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#documentModal"><i class="bi bi-upload"></i> Subir documento</button>
        </div>
    <?php endif; ?>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="widget-card document-tree">
            <h2 class="h6 fw-semibold mb-3">Estructura</h2>
            <?php $renderFolder = function ($folder) use (&$renderFolder, $currentFolder) { ?>
                <li>
                    <a href="/repositorio?folder=<?= $folder['id'] ?>" class="text-decoration-none <?= $currentFolder === (int)$folder['id'] ? 'fw-semibold text-accent' : '' ?>">
                        <i class="bi bi-folder2 me-2"></i><?= htmlspecialchars($folder['name']) ?>
                    </a>
                    <?php if (!empty($folder['children'])): ?>
                        <ul>
                            <?php foreach ($folder['children'] as $child): ?>
                                <?= $renderFolder($child) ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php }; ?>
            <ul class="ps-0">
                <li><a href="/repositorio" class="text-decoration-none <?= $currentFolder ? '' : 'fw-semibold text-accent' ?>"><i class="bi bi-house me-2"></i>Inicio</a></li>
                <?php foreach ($tree as $folder): ?>
                    <?= $renderFolder($folder) ?>
                <?php endforeach; ?>
                <?php if (empty($tree)): ?>
                    <li class="text-muted">No hay carpetas registradas.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="widget-card">
            <h2 class="h6 fw-semibold mb-3">Documentos</h2>
            <div class="list-group">
                <?php foreach ($documents as $document): ?>
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-file-earmark-text me-2"></i>
                            <strong><?= htmlspecialchars($document['name']) ?></strong>
                            <span class="text-muted small ms-2">Visibilidad: <?= htmlspecialchars($document['visibility']) ?></span>
                        </div>
                        <div class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="/repositorio/documento/descargar?id=<?= $document['id'] ?>" download><i class="bi bi-download"></i> Descargar</a>
                            <?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
                                <form method="POST" action="/repositorio/documento/eliminar">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <input type="hidden" name="id" value="<?= $document['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar documento?')"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($documents)): ?>
                    <p class="text-muted mb-0">No hay documentos en esta carpeta.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php if (in_array(current_user()['role_slug'], ['admin-principal', 'publicador'], true)): ?>
<div class="modal fade" id="folderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/repositorio/carpeta">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Nueva carpeta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Carpeta padre</label>
                        <input type="number" name="parent_id" class="form-control" placeholder="ID de la carpeta (opcional)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="documentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/repositorio/documento" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Subir documento</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Archivo</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Carpeta</label>
                        <input type="number" name="folder_id" class="form-control" placeholder="ID de carpeta (opcional)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Visibilidad</label>
                        <select name="visibility" class="form-select">
                            <option value="todos">Todos los roles</option>
                            <option value="publicador">Solo publicadores</option>
                            <option value="admin-principal">Solo administradores</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
