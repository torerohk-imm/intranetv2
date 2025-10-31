<?php
namespace App\Controllers;

use App\Models\BrandingSetting;
use App\Models\Role;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        authorize(['admin-principal']);
        $brandingModel = new BrandingSetting(db());
        $branding = $brandingModel->getSettings();
        $users = (new User(db()))->allWithRoles();
        $roles = (new Role(db()))->all();
        $slot = view('admin/index', [
            'title' => 'Administración',
            'active' => 'admin',
            'branding' => $branding,
            'users' => $users,
            'roles' => $roles,
            'visits' => (int)db()->query('SELECT value FROM metrics WHERE `key` = "visits"')->fetchColumn(),
            'onlineUsers' => $this->getOnlineUsers(),
        ]);
        render('layouts/app', [
            'title' => 'Administración',
            'active' => 'admin',
            'slot' => $slot,
        ]);
    }

    public function updateBranding()
    {
        authorize(['admin-principal']);
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/administracion');
        }
        $brandingModel = new BrandingSetting(db());
        foreach (['site_name', 'primary_color', 'secondary_color', 'accent_color', 'background_color', 'font_family'] as $key) {
            if (isset($_POST[$key])) {
                $brandingModel->set($key, trim($_POST[$key]));
            }
        }
        if (!empty($_FILES['logo']['name'])) {
            $path = $this->handleUpload($_FILES['logo']);
            $brandingModel->set('logo_path', $path);
        }
        flash('success', 'Branding actualizado.');
        redirect('/administracion');
    }

    public function storeUser()
    {
        authorize(['admin-principal']);
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Token CSRF inválido.');
            redirect('/administracion');
        }
        $payload = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => password_hash($_POST['password'] ?? 'Secret123*', PASSWORD_BCRYPT),
            'role_id' => (int)($_POST['role_id'] ?? 3),
        ];
        (new User(db()))->create($payload);
        flash('success', 'Usuario creado.');
        redirect('/administracion');
    }

    public function updateUser()
    {
        authorize(['admin-principal']);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Solicitud inválida.');
            redirect('/administracion');
        }
        $payload = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'role_id' => (int)($_POST['role_id'] ?? 3),
        ];
        if (!empty($_POST['password'])) {
            $payload['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }
        (new User(db()))->update($id, $payload);
        flash('success', 'Usuario actualizado.');
        redirect('/administracion');
    }

    public function destroyUser()
    {
        authorize(['admin-principal']);
        $id = (int)($_POST['id'] ?? 0);
        if (!$id || !verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('danger', 'Solicitud inválida.');
            redirect('/administracion');
        }
        (new User(db()))->delete($id);
        flash('success', 'Usuario eliminado.');
        redirect('/administracion');
    }

    private function handleUpload(array $file): string
    {
        $directory = public_path('storage/uploads/branding');
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
        $filename = 'logo_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $destination = $directory . '/' . $filename;
        move_uploaded_file($file['tmp_name'], $destination);
        return '/storage/uploads/branding/' . $filename;
    }

    private function getOnlineUsers(): array
    {
        $stmt = db()->query('SELECT user_name, last_seen FROM sessions WHERE last_seen >= DATE_SUB(NOW(), INTERVAL 10 MINUTE)');
        return $stmt->fetchAll();
    }
}
