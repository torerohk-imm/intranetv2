<?php
namespace App\Controllers;

class ProfileController extends Controller
{
    public function index()
    {
        require_auth();
        $slot = view('profile/index', [
            'title' => 'Mi perfil',
            'active' => 'profile',
            'user' => current_user(),
        ]);
        render('layouts/app', [
            'title' => 'Mi perfil',
            'active' => 'profile',
            'slot' => $slot,
        ]);
    }
}
