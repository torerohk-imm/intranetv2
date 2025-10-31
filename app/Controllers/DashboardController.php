<?php
namespace App\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\QuickLink;
use App\Models\UserPreference;
use App\Models\Contact;

class DashboardController extends Controller
{
    public function index()
    {
        require_auth();
        $user = current_user();
        $eventModel = new Event(db());
        $announcementModel = new Announcement(db());
        $quickLinks = (new QuickLink(db()))->forUser($user['id']);
        $preferences = (new UserPreference(db()))->forUser($user['id']);
        $contacts = (new Contact(db()))->all();

        $stats = [
            'labels' => ['Usuarios', 'Eventos', 'Anuncios', 'Documentos'],
            'values' => [
                (int)db()->query('SELECT COUNT(*) FROM users')->fetchColumn(),
                (int)db()->query('SELECT COUNT(*) FROM events')->fetchColumn(),
                (int)db()->query('SELECT COUNT(*) FROM announcements')->fetchColumn(),
                (int)db()->query('SELECT COUNT(*) FROM documents')->fetchColumn(),
            ],
        ];

        $slot = view('dashboard/index', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'events' => $eventModel->upcoming(5),
            'announcements' => $announcementModel->latest(4),
            'quickLinks' => $quickLinks,
            'preferences' => $preferences,
            'stats' => $stats,
            'contacts' => $contacts,
        ]);

        render('layouts/app', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'slot' => $slot,
        ]);
    }

    public function savePreferences()
    {
        require_auth();
        $token = $_POST['csrf_token'] ?? '';
        if (!verify_csrf($token)) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/');
        }
        $widgets = $_POST['widgets'] ?? [];
        (new UserPreference(db()))->store(current_user()['id'], [
            'widgets' => array_keys(array_filter($widgets)),
        ]);
        flash('success', 'Preferencias guardadas.');
        redirect('/');
    }
}
