<?php
namespace App\Controllers;

use App\Models\EmbeddedSite;

class EmbeddedSiteController extends Controller
{
    public function index()
    {
        require_auth();
        $sites = (new EmbeddedSite(db()))->all();
        $slot = view('embedded/index', [
            'title' => 'Sitios embebidos',
            'active' => 'embedded',
            'sites' => $sites,
        ]);
        render('layouts/app', [
            'title' => 'Sitios embebidos',
            'active' => 'embedded',
            'slot' => $slot,
        ]);
    }

    public function store()
    {
        authorize(['admin-principal', 'publicador']);
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/sitios');
        }
        (new EmbeddedSite(db()))->create([
            'name' => trim($_POST['name'] ?? ''),
            'url' => trim($_POST['url'] ?? ''),
            'layout' => $_POST['layout'] ?? 'grid',
            'width' => (int)($_POST['width'] ?? 480),
            'height' => (int)($_POST['height'] ?? 320),
        ]);
        flash('success', 'Sitio embebido guardado.');
        redirect('/sitios');
    }

    public function destroy()
    {
        authorize(['admin-principal', 'publicador']);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Solicitud inválida.');
            redirect('/sitios');
        }
        (new EmbeddedSite(db()))->delete($id);
        flash('success', 'Sitio eliminado.');
        redirect('/sitios');
    }
}
