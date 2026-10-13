<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSiteContent extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'setting_key' => ['type' => 'VARCHAR', 'constraint' => 80],
            'setting_value' => ['type' => 'TEXT', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('setting_key', true);
        $this->forge->createTable('site_content', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('site_content', true);
    }
}
