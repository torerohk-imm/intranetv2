<?php
namespace App\Controllers;

use App\Models\Document;
use App\Models\DocumentFolder;

class DocumentController extends Controller
{
    private string $uploadPath = 'uploads/documents';

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
        $path = $this->handleUpload($_FILES['file']);
        (new Document(db()))->create([
            'name' => trim($_POST['name'] ?? $_FILES['file']['name']),
            'file_path' => $path,
            'folder_id' => $_POST['folder_id'] !== '' ? (int)$_POST['folder_id'] : null,
            'visibility' => $_POST['visibility'] ?? 'todos',
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
            $fullPath = public_path(ltrim($document['file_path'], '/'));
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $model->delete($id);
        }
        flash('success', 'Documento eliminado.');
        redirect('/repositorio');
    }

    private function handleUpload(array $file): string
    {
        $directory = public_path('storage/' . $this->uploadPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
        $filename = uniqid('doc_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $destination = $directory . '/' . $filename;
        move_uploaded_file($file['tmp_name'], $destination);
        return '/storage/' . $this->uploadPath . '/' . $filename;
    }
}
