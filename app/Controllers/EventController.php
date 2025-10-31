<?php
namespace App\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        require_auth();
        $model = new Event(db());
        $events = $model->all();
        $slot = view('events/index', [
            'title' => 'Calendario de eventos',
            'active' => 'calendar',
            'events' => $events,
        ]);
        render('layouts/app', [
            'title' => 'Calendario',
            'active' => 'calendar',
            'slot' => $slot,
        ]);
    }

    public function store()
    {
        authorize(['admin-principal', 'publicador']);
        $token = $_POST['csrf_token'] ?? '';
        if (!verify_csrf($token)) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/calendario');
        }
        $payload = [
            'title' => trim($_POST['title'] ?? ''),
            'event_date' => $_POST['event_date'] ?? '',
            'description' => trim($_POST['description'] ?? ''),
        ];
        (new Event(db()))->create($payload);
        flash('success', 'Evento creado.');
        redirect('/calendario');
    }

    public function update()
    {
        authorize(['admin-principal', 'publicador']);
        $token = $_POST['csrf_token'] ?? '';
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($token)) {
            flash('danger', 'Solicitud inválida.');
            redirect('/calendario');
        }
        $payload = [
            'title' => trim($_POST['title'] ?? ''),
            'event_date' => $_POST['event_date'] ?? '',
            'description' => trim($_POST['description'] ?? ''),
        ];
        (new Event(db()))->update($id, $payload);
        flash('success', 'Evento actualizado.');
        redirect('/calendario');
    }

    public function destroy()
    {
        authorize(['admin-principal', 'publicador']);
        $token = $_POST['csrf_token'] ?? '';
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($token)) {
            flash('danger', 'Solicitud inválida.');
            redirect('/calendario');
        }
        (new Event(db()))->delete($id);
        flash('success', 'Evento eliminado.');
        redirect('/calendario');
    }
}
