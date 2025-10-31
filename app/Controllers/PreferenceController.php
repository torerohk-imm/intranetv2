<?php
namespace App\Controllers;

use App\Models\UserPreference;

class PreferenceController extends Controller
{
    public function index()
    {
        require_auth();
        $preferences = (new UserPreference(db()))->forUser(current_user()['id']);
        if (isset($preferences['theme'])) {
            $_SESSION['theme'] = $preferences['theme'];
        }
        $slot = view('preferences/index', [
            'title' => 'Preferencias',
            'active' => 'preferences',
            'preferences' => $preferences,
        ]);
        render('layouts/app', [
            'title' => 'Preferencias',
            'active' => 'preferences',
            'slot' => $slot,
        ]);
    }

    public function update()
    {
        require_auth();
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/configuracion');
        }
        $theme = $_POST['theme'] ?? 'light';
        $prefs = (new UserPreference(db()))->forUser(current_user()['id']);
        $prefs['theme'] = $theme;
        (new UserPreference(db()))->store(current_user()['id'], $prefs);
        $_SESSION['theme'] = $theme;
        flash('success', 'Preferencias actualizadas.');
        redirect('/configuracion');
    }
}
