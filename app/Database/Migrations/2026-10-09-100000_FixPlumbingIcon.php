<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixPlumbingIcon extends Migration
{
    public function up(): void
    {
        $this->db->table('categories')
            ->where('slug', 'plumbing')
            ->where('icon', 'bi-pipe')
            ->update(['icon' => 'bi-droplet']);
    }

    public function down(): void
    {
        $this->db->table('categories')
            ->where('slug', 'plumbing')
            ->where('icon', 'bi-droplet')
            ->update(['icon' => 'bi-pipe']);
    }
}
