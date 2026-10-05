<?php

namespace App\Database\Seeds;

use App\Libraries\CatalogData;
use CodeIgniter\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        helper('url');

        $now = date('Y-m-d H:i:s');

        $categoryIds = [];
        foreach (CatalogData::categories() as $index => $category) {
            $existing = $this->db->table('categories')->where('slug', $category['slug'])->get()->getRowArray();
            if ($existing !== null) {
                $categoryIds[$category['slug']] = (int) $existing['id'];
                continue;
            }

            $this->db->table('categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'icon' => $category['icon'],
                'sort_order' => $index,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $categoryIds[$category['slug']] = (int) $this->db->insertID();
        }

        $brandIds = [];
        foreach (CatalogData::brands() as $index => $brandName) {
            $slug = url_title($brandName, '-', true);
            $existing = $this->db->table('brands')->where('slug', $slug)->get()->getRowArray();
            if ($existing !== null) {
                $brandIds[strtolower($brandName)] = (int) $existing['id'];
                continue;
            }

            $this->db->table('brands')->insert([
                'name' => $brandName,
                'slug' => $slug,
                'sort_order' => $index,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $brandIds[strtolower($brandName)] = (int) $this->db->insertID();
        }

        foreach (CatalogData::products() as $index => $product) {
            if ($this->db->table('products')->where('slug', $product['slug'])->countAllResults() > 0) {
                continue;
            }

            $brandKey = strtolower((string) ($product['brand'] ?? ''));
            $this->db->table('products')->insert([
                'category_id' => $categoryIds[$product['category']],
                'brand_id' => $brandIds[$brandKey] ?? null,
                'name' => $product['name'],
                'slug' => $product['slug'],
                'sku' => null,
                'short_description' => $product['description'] ?? null,
                'description' => $product['description'] ?? null,
                'meta' => $product['meta'] ?? null,
                'icon' => $product['icon'] ?? 'bi-tools',
                'badge' => $product['badge'] ?? null,
                'specifications' => json_encode($product['specs'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'sort_order' => $index,
                'is_featured' => $index < 4 ? 1 : 0,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
