<?php
namespace App\Controllers;

use App\Models\QuickLink;

class QuickLinkController extends Controller
{
    public function index()
    {
        require_auth();
        $links = (new QuickLink(db()))->forUser(current_user()['id']);
        $slot = view('quicklinks/index', [
            'title' => 'Enlaces rápidos',
            'active' => 'quicklinks',
            'links' => $links,
        ]);
        render('layouts/app', [
            'title' => 'Enlaces rápidos',
            'active' => 'quicklinks',
            'slot' => $slot,
        ]);
    }

    public function store()
    {
        authorize(['admin-principal', 'publicador', 'usuario-final']);
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/enlaces');
        }
        $visibility = $_POST['visibility'] ?? 'personal';
        if ($visibility === 'global') {
            authorize(['admin-principal', 'publicador']);
        }
        (new QuickLink(db()))->create([
            'name' => trim($_POST['name'] ?? ''),
            'url' => trim($_POST['url'] ?? ''),
            'target' => $_POST['target'] ?? '_blank',
            'visibility' => $visibility,
            'category' => $visibility === 'personal' ? 'Mis enlaces' : 'Generales',
            'user_id' => $visibility === 'personal' ? current_user()['id'] : null,
        ]);
        flash('success', 'Enlace guardado.');
        redirect('/enlaces');
    }

    public function destroy()
    {
        authorize(['admin-principal', 'publicador', 'usuario-final']);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Solicitud inválida.');
            redirect('/enlaces');
        }
        $model = new QuickLink(db());
        $link = $model->find($id);
        if (!$link) {
            flash('danger', 'Enlace no encontrado.');
            redirect('/enlaces');
        }
        if ($link['visibility'] === 'personal' && $link['user_id'] !== current_user()['id']) {
            flash('danger', 'No tienes permiso para eliminar este enlace.');
            redirect('/enlaces');
        }
        if ($link['visibility'] === 'global') {
            authorize(['admin-principal', 'publicador']);
        }
        $model->delete($id);
        flash('success', 'Enlace eliminado.');
        redirect('/enlaces');
    }
}
