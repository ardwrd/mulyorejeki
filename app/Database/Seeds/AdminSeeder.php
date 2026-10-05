<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $name = trim((string) env('admin.seed.name', ''));
        $email = strtolower(trim((string) env('admin.seed.email', '')));
        $password = (string) env('admin.seed.password', '');

        if ($name === '' || $email === '' || $password === '') {
            return;
        }

        $existing = $this->db->table('admin_users')->where('email', $email)->get()->getRowArray();
        $now = date('Y-m-d H:i:s');
        $data = [
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'is_active' => 1,
            'updated_at' => $now,
        ];

        if ($existing !== null) {
            $this->db->table('admin_users')->where('id', $existing['id'])->update($data);
            return;
        }

        $data['created_at'] = $now;
        $this->db->table('admin_users')->insert($data);
    }
}
