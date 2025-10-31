<?php
namespace App\Controllers;

use App\Models\OrgNode;

class OrganigramController extends Controller
{
    private string $uploadPath = 'uploads/avatars';

    public function index()
    {
        require_auth();
        $tree = (new OrgNode(db()))->tree();
        $slot = view('organigram/index', [
            'title' => 'Organigrama',
            'active' => 'organigram',
            'nodes' => $tree,
        ]);
        render('layouts/app', [
            'title' => 'Organigrama',
            'active' => 'organigram',
            'slot' => $slot,
        ]);
    }

    public function store()
    {
        authorize(['admin-principal', 'publicador']);
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/organigrama');
        }
        $payload = [
            'name' => trim($_POST['name'] ?? ''),
            'position' => trim($_POST['position'] ?? ''),
            'department' => trim($_POST['department'] ?? ''),
            'parent_id' => $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null,
            'photo_path' => null,
        ];
        if (!empty($_FILES['photo']['name'])) {
            $payload['photo_path'] = $this->handleUpload($_FILES['photo']);
        }
        (new OrgNode(db()))->create($payload);
        flash('success', 'Nodo agregado.');
        redirect('/organigrama');
    }

    public function update()
    {
        authorize(['admin-principal', 'publicador']);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Solicitud inválida.');
            redirect('/organigrama');
        }
        $payload = [
            'name' => trim($_POST['name'] ?? ''),
            'position' => trim($_POST['position'] ?? ''),
            'department' => trim($_POST['department'] ?? ''),
            'parent_id' => $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null,
        ];
        if (!empty($_FILES['photo']['name'])) {
            $payload['photo_path'] = $this->handleUpload($_FILES['photo']);
        }
        (new OrgNode(db()))->update($id, $payload);
        flash('success', 'Nodo actualizado.');
        redirect('/organigrama');
    }

    public function destroy()
    {
        authorize(['admin-principal', 'publicador']);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Solicitud inválida.');
            redirect('/organigrama');
        }
        $model = new OrgNode(db());
        $node = $model->find($id);
        if ($node && !empty($node['photo_path'])) {
            $path = public_path(ltrim($node['photo_path'], '/'));
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $model->delete($id);
        flash('success', 'Nodo eliminado.');
        redirect('/organigrama');
    }

    private function handleUpload(array $file): string
    {
        $directory = public_path('storage/' . $this->uploadPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
        $filename = uniqid('avatar_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $destination = $directory . '/' . $filename;
        move_uploaded_file($file['tmp_name'], $destination);
        return '/storage/' . $this->uploadPath . '/' . $filename;
    }
}
