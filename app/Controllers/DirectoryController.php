<?php
namespace App\Controllers;

use App\Models\Contact;

class DirectoryController extends Controller
{
    public function index()
    {
        require_auth();
        $term = trim($_GET['q'] ?? '');
        $model = new Contact(db());
        $contacts = $term ? $model->search($term) : $model->all();
        $slot = view('directory/index', [
            'title' => 'Directorio corporativo',
            'active' => 'directory',
            'contacts' => $contacts,
            'term' => $term,
        ]);
        render('layouts/app', [
            'title' => 'Directorio',
            'active' => 'directory',
            'slot' => $slot,
        ]);
    }

    public function store()
    {
        authorize(['admin-principal', 'publicador']);
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/directorio');
        }
        (new Contact(db()))->create([
            'name' => trim($_POST['name'] ?? ''),
            'position' => trim($_POST['position'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'extension' => trim($_POST['extension'] ?? ''),
        ]);
        flash('success', 'Contacto creado.');
        redirect('/directorio');
    }

    public function update()
    {
        authorize(['admin-principal', 'publicador']);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Solicitud inválida.');
            redirect('/directorio');
        }
        (new Contact(db()))->update($id, [
            'name' => trim($_POST['name'] ?? ''),
            'position' => trim($_POST['position'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'extension' => trim($_POST['extension'] ?? ''),
        ]);
        flash('success', 'Contacto actualizado.');
        redirect('/directorio');
    }

    public function destroy()
    {
        authorize(['admin-principal', 'publicador']);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Solicitud inválida.');
            redirect('/directorio');
        }
        (new Contact(db()))->delete($id);
        flash('success', 'Contacto eliminado.');
        redirect('/directorio');
    }
}
