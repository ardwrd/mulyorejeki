<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->has('admin_user_id')) {
            return redirect()->to(site_url('admin'));
        }

        return view('admin/auth/login', [
            'title' => 'Login Admin — Mulyorejeki',
        ]);
    }

    public function authenticate()
    {
        $rules = [
            'email' => 'required|valid_email|max_length[190]',
            'password' => 'required|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');

        $model = new AdminUserModel();
        $user = $model->where('email', $email)->where('is_active', 1)->first();

        if ($user === null || ! password_verify($password, (string) $user['password_hash'])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Email atau password tidak sesuai.');
        }

        session()->regenerate(true);
        session()->set([
            'admin_user_id' => (int) $user['id'],
            'admin_user_name' => $user['name'],
            'admin_user_email' => $user['email'],
        ]);

        $model->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        $intended = (string) session()->pull('admin_intended_path');
        if ($intended !== '' && str_starts_with($intended, '/admin')) {
            return redirect()->to(site_url(ltrim($intended, '/')));
        }

        return redirect()->to(site_url('admin'));
    }

    public function logout()
    {
        session()->remove([
            'admin_user_id',
            'admin_user_name',
            'admin_user_email',
            'admin_intended_path',
        ]);
        session()->regenerate(true);

        return redirect()->to(site_url('admin/login'));
    }
}
