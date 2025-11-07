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
        unset($user['password']);
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role_id' => $user['role_id'],
            'role_name' => $user['role_name'],
            'role_slug' => $user['role_slug'],
        ];
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
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
            session_regenerate_id(true);
            session_destroy();
        }
        redirect('/login');
    }
}
