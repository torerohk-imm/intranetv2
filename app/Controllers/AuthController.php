<?php
namespace App\Controllers;

use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        $branding = $this->config['branding'] ?? [];
        $slot = view('auth/login', ['branding' => $branding]);
        render('layouts/base', [
            'title' => 'Iniciar sesión',
            'slot' => $slot,
            'branding' => $branding,
        ]);
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $token = $_POST['csrf_token'] ?? '';
        if (!verify_csrf($token)) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/login');
        }
        if (!$email || !$password) {
            flash('danger', 'Ingresa correo y contraseña.');
            redirect('/login');
        }
        $userModel = new User(db());
        $user = $userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            flash('danger', 'Credenciales inválidas.');
            redirect('/login');
        }
        $_SESSION['user'] = $user;
        try {
            $prefs = (new \App\Models\UserPreference(db()))->forUser($user['id']);
            if (isset($prefs['theme'])) {
                $_SESSION['theme'] = $prefs['theme'];
            }
        } catch (\Throwable $th) {
            // preferencias no disponibles aún
        }
        flash('success', 'Bienvenido de nuevo, ' . $user['name'] . '!');
        redirect('/');
    }

    public function logout()
    {
        session_destroy();
        redirect('/login');
    }
}
