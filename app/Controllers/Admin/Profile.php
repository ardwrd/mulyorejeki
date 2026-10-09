<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Profile extends BaseController
{
    public function index(): string
    {
        $user = $this->currentUser();

        return view('admin/profile/index', [
            'title' => 'Profil Admin — Mulyorejeki',
            'user' => $user,
        ]);
    }

    public function updateDetails()
    {
        if (! $this->validate([
            'name' => 'required|max_length[120]',
            'email' => 'required|valid_email|max_length[190]',
        ])) {
            return redirect()->back()->withInput()->with('details_errors', $this->validator->getErrors());
        }

        $user = $this->currentUser();
        $name = trim((string) $this->request->getPost('name'));
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $model = new AdminUserModel();

        if ($model->where('email', $email)->where('id !=', $user['id'])->countAllResults() > 0) {
            return redirect()->back()->withInput()->with('details_errors', ['email' => 'Email sudah dipakai oleh admin lain.']);
        }

        $model->update($user['id'], ['name' => $name, 'email' => $email]);
        session()->set(['admin_user_name' => $name, 'admin_user_email' => $email]);

        return redirect()->to(site_url('admin/profile'))->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword()
    {
        if (! $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min_length[12]|max_length[255]',
            'password_confirm' => 'required|matches[new_password]',
        ])) {
            return redirect()->to(site_url('admin/profile'))->with('password_errors', $this->validator->getErrors());
        }

        $user = $this->currentUser();
        if (! password_verify((string) $this->request->getPost('current_password'), (string) $user['password_hash'])) {
            return redirect()->to(site_url('admin/profile'))->with('password_errors', ['current_password' => 'Password saat ini tidak sesuai.']);
        }

        (new AdminUserModel())->update($user['id'], [
            'password_hash' => password_hash((string) $this->request->getPost('new_password'), PASSWORD_DEFAULT),
        ]);
        session()->regenerate(true);

        return redirect()->to(site_url('admin/profile'))->with('success', 'Password berhasil diganti.');
    }

    private function currentUser(): array
    {
        $user = (new AdminUserModel())->find((int) session('admin_user_id'));
        if ($user === null) {
            throw PageNotFoundException::forPageNotFound('Admin tidak ditemukan.');
        }

        return $user;
    }
}
