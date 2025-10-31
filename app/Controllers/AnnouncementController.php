<?php
namespace App\Controllers;

use App\Models\Announcement;

class AnnouncementController extends Controller
{
    private string $uploadPath = 'uploads/announcements';

    public function index()
    {
        require_auth();
        $order = $_GET['order'] ?? 'desc';
        $model = new Announcement(db());
        $announcements = $model->all();
        usort($announcements, function ($a, $b) use ($order) {
            return $order === 'asc'
                ? strcmp($a['published_at'], $b['published_at'])
                : strcmp($b['published_at'], $a['published_at']);
        });
        $slot = view('announcements/index', [
            'title' => 'Tablón de anuncios',
            'active' => 'announcements',
            'announcements' => $announcements,
            'order' => $order,
        ]);
        render('layouts/app', [
            'title' => 'Anuncios',
            'active' => 'announcements',
            'slot' => $slot,
        ]);
    }

    public function store()
    {
        authorize(['admin-principal', 'publicador']);
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/anuncios');
        }
        $payload = [
            'title' => trim($_POST['title'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'image_path' => null,
            'published_at' => date('Y-m-d H:i:s'),
            'author_id' => current_user()['id'],
        ];
        if (!empty($_FILES['image']['name'])) {
            $payload['image_path'] = $this->handleUpload($_FILES['image']);
        }
        (new Announcement(db()))->create($payload);
        flash('success', 'Anuncio publicado.');
        redirect('/anuncios');
    }

    public function update()
    {
        authorize(['admin-principal', 'publicador']);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Solicitud inválida.');
            redirect('/anuncios');
        }
        $payload = [
            'title' => trim($_POST['title'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
        ];
        if (!empty($_FILES['image']['name'])) {
            $payload['image_path'] = $this->handleUpload($_FILES['image']);
        }
        (new Announcement(db()))->update($id, $payload);
        flash('success', 'Anuncio actualizado.');
        redirect('/anuncios');
    }

    public function destroy()
    {
        authorize(['admin-principal', 'publicador']);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Solicitud inválida.');
            redirect('/anuncios');
        }
        $model = new Announcement(db());
        $announcement = $model->find($id);
        if ($announcement && !empty($announcement['image_path'])) {
            $path = public_path(ltrim($announcement['image_path'], '/'));
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $model->delete($id);
        flash('success', 'Anuncio eliminado.');
        redirect('/anuncios');
    }

    private function handleUpload(array $file): string
    {
        $directory = public_path('storage/' . $this->uploadPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
        $filename = uniqid('announcement_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $destination = $directory . '/' . $filename;
        move_uploaded_file($file['tmp_name'], $destination);
        return '/storage/' . $this->uploadPath . '/' . $filename;
    }
}
