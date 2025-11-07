<?php
namespace App\Controllers;

use App\Models\Document;
use App\Models\DocumentFolder;
use App\Support\UploadManager;
use RuntimeException;

class DocumentController extends Controller
{
    private string $uploadPath = 'documents';

    public function index()
    {
        require_auth();
        $folderId = isset($_GET['folder']) ? (int)$_GET['folder'] : null;
        $folderModel = new DocumentFolder(db());
        $documentModel = new Document(db());
        $slot = view('documents/index', [
            'title' => 'Repositorio de documentos',
            'active' => 'documents',
            'tree' => $folderModel->tree(),
            'documents' => $documentModel->forFolder($folderId),
            'currentFolder' => $folderId,
        ]);
        render('layouts/app', [
            'title' => 'Repositorio',
            'active' => 'documents',
            'slot' => $slot,
        ]);
    }

    public function storeFolder()
    {
        authorize(['admin-principal', 'publicador']);
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/repositorio');
        }
        (new DocumentFolder(db()))->create([
            'name' => trim($_POST['name'] ?? ''),
            'parent_id' => $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null,
        ]);
        flash('success', 'Carpeta creada.');
        redirect('/repositorio');
    }

    public function storeDocument()
    {
        authorize(['admin-principal', 'publicador']);
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/repositorio');
        }
        if (empty($_FILES['file']['name'])) {
            flash('danger', 'Selecciona un archivo.');
            redirect('/repositorio');
        }
        try {
            $path = $this->handleUpload($_FILES['file']);
        } catch (RuntimeException $exception) {
            flash('danger', $exception->getMessage());
            redirect('/repositorio');
        }
        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            $name = $_FILES['file']['name'];
        }
        $visibility = $_POST['visibility'] ?? 'todos';
        if (!in_array($visibility, ['todos', 'publicador', 'admin-principal'], true)) {
            $visibility = 'todos';
        }
        (new Document(db()))->create([
            'name' => $name,
            'file_path' => $path,
            'folder_id' => $_POST['folder_id'] !== '' ? (int)$_POST['folder_id'] : null,
            'visibility' => $visibility,
        ]);
        flash('success', 'Documento cargado.');
        redirect('/repositorio');
    }

    public function destroyDocument()
    {
        authorize(['admin-principal', 'publicador']);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Solicitud inválida.');
            redirect('/repositorio');
        }
        $model = new Document(db());
        $document = $model->find($id);
        if ($document) {
            UploadManager::delete($document['file_path']);
            $model->delete($id);
        }
        flash('success', 'Documento eliminado.');
        redirect('/repositorio');
    }

    public function downloadDocument()
    {
        require_auth();
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            http_response_code(404);
            exit('Documento no encontrado');
        }
        $model = new Document(db());
        $document = $model->find($id);
        if (!$document || !$this->canAccessDocument($document)) {
            http_response_code($document ? 403 : 404);
            exit('No tienes permiso para acceder a este documento.');
        }
        $fullPath = $this->resolveFilePath($document['file_path']);
        if (!is_file($fullPath)) {
            http_response_code(404);
            exit('El archivo solicitado no existe.');
        }
        $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $this->buildDownloadName($document) . '"');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    }

    private function handleUpload(array $file): string
    {
        $allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt'];
        $allowedMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
        ];
        $maxSize = 20 * 1024 * 1024; // 20 MB
        return UploadManager::store($file, $this->uploadPath, $allowedExtensions, $allowedMimeTypes, $maxSize);
    }

    private function canAccessDocument(array $document): bool
    {
        $user = current_user();
        if (!$user) {
            return false;
        }
        $visibility = $document['visibility'] ?? 'todos';
        if ($visibility === 'todos') {
            return true;
        }
        if ($visibility === 'publicador') {
            return in_array($user['role_slug'], ['admin-principal', 'publicador'], true);
        }
        if ($visibility === 'admin-principal') {
            return $user['role_slug'] === 'admin-principal';
        }
        return false;
    }

    private function resolveFilePath(string $storedPath): string
    {
        if ($storedPath === '') {
            return '';
        }
        if (strpos($storedPath, '/storage/') === 0) {
            return public_path(ltrim($storedPath, '/'));
        }
        if (strpos($storedPath, 'storage/') === 0) {
            return public_path($storedPath);
        }
        return storage_path($storedPath);
    }

    private function buildDownloadName(array $document): string
    {
        $name = $document['name'] ?? 'documento';
        $sanitized = trim(preg_replace('/[^A-Za-z0-9 _.-]/', '', $name) ?: 'documento');
        $extension = strtolower(pathinfo($document['file_path'] ?? '', PATHINFO_EXTENSION));
        if ($extension) {
            $suffix = '.' . $extension;
            $lowerSanitized = strtolower($sanitized);
            if (substr($lowerSanitized, -strlen($suffix)) !== $suffix) {
                $sanitized .= $suffix;
            }
        }
        return $sanitized;
    }
}
