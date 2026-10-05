<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCatalogSchema extends Migration
{
    public function up(): void
    {
        $this->createCategories();
        $this->createBrands();
        $this->createProducts();
        $this->createProductImages();
        $this->createAdminUsers();
    }

    public function down(): void
    {
        $this->forge->dropTable('product_images', true);
        $this->forge->dropTable('products', true);
        $this->forge->dropTable('brands', true);
        $this->forge->dropTable('categories', true);
        $this->forge->dropTable('admin_users', true);
    }

    private function createCategories(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 140,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
                'null' => true,
            ],
            'sort_order' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('categories', true);
    }

    private function createBrands(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 140,
            ],
            'logo_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'sort_order' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('brands', true);
    }

    private function createProducts(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'category_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'brand_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 180,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'short_description' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'meta' => [
                'type' => 'VARCHAR',
                'constraint' => 160,
                'null' => true,
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
                'null' => true,
            ],
            'badge' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
                'null' => true,
            ],
            'specifications' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sort_order' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'is_featured' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('category_id');
        $this->forge->addKey('brand_id');
        $this->forge->addUniqueKey('slug');
        $this->forge->addUniqueKey('sku');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('brand_id', 'brands', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('products', true);
    }

    private function createProductImages(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'object_key' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'url' => [
                'type' => 'VARCHAR',
                'constraint' => 1000,
            ],
            'alt_text' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'sort_order' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'is_primary' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('product_id');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_images', true);
    }

    private function createAdminUsers(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 190,
            ],
            'password_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'last_login_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('admin_users', true);
    }
}
