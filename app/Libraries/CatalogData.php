<?php

namespace App\Libraries;

final class CatalogData
{
    public static function categories(): array
    {
        return [
            ['slug' => 'pompa', 'name' => 'Pompa', 'description' => 'Pompa air dan kebutuhan transfer fluida', 'icon' => 'bi-droplet-half'],
            ['slug' => 'power-tools', 'name' => 'Power Tools', 'description' => 'Gerinda, bor, cut-off, dan mesin kerja', 'icon' => 'bi-lightning-charge'],
            ['slug' => 'fastener', 'name' => 'Baut & Fastener', 'description' => 'Baut, mur, washer, dan kebutuhan pengikat', 'icon' => 'bi-nut'],
            ['slug' => 'hand-tools', 'name' => 'Hand Tools', 'description' => 'Kunci, tang, obeng, dan alat kerja manual', 'icon' => 'bi-wrench-adjustable'],
            ['slug' => 'plumbing', 'name' => 'Plumbing', 'description' => 'Fitting, valve, selang, dan aksesorinya', 'icon' => 'bi-pipe'],
            ['slug' => 'electrical', 'name' => 'Electrical', 'description' => 'Perlengkapan listrik untuk pekerjaan teknik', 'icon' => 'bi-plug'],
        ];
    }

    public static function brands(): array
    {
        return ['Makita', 'Bosch', 'Ebara', 'Tekiro', 'Grundfos', 'HiKOKI'];
    }

    public static function products(): array
    {
        return [
            [
                'slug' => 'makita-angle-grinder-100mm',
                'brand' => 'Makita',
                'name' => 'Angle Grinder 100 mm',
                'category' => 'power-tools',
                'category_label' => 'Power Tools',
                'meta' => '720 W',
                'icon' => 'bi-lightning-charge',
                'badge' => 'Pilihan',
                'description' => 'Gerinda tangan 100 mm untuk pekerjaan potong dan grinding di workshop maupun lapangan.',
                'specs' => [
                    'Brand' => 'Makita',
                    'Tipe' => 'Angle Grinder',
                    'Daya' => '720 Watt',
                    'Diameter Disc' => '100 mm',
                    'No Load Speed' => '11.000 RPM',
                    'Kategori' => 'Power Tools',
                ],
            ],
            [
                'slug' => 'ebara-pompa-centrifugal',
                'brand' => 'Ebara',
                'name' => 'Pompa Centrifugal',
                'category' => 'pompa',
                'category_label' => 'Pompa',
                'meta' => 'Stainless Steel',
                'icon' => 'bi-droplet-half',
                'description' => 'Pompa centrifugal untuk kebutuhan transfer air dan penggunaan umum.',
                'specs' => ['Brand' => 'Ebara', 'Tipe' => 'Centrifugal Pump', 'Material' => 'Stainless Steel', 'Kategori' => 'Pompa'],
            ],
            [
                'slug' => 'tekiro-combination-wrench-set',
                'brand' => 'Tekiro',
                'name' => 'Combination Wrench Set',
                'category' => 'hand-tools',
                'category_label' => 'Hand Tools',
                'meta' => 'Metric',
                'icon' => 'bi-wrench-adjustable',
                'description' => 'Set kunci kombinasi metric untuk kebutuhan perawatan dan pekerjaan mekanik.',
                'specs' => ['Brand' => 'Tekiro', 'Tipe' => 'Combination Wrench Set', 'Satuan' => 'Metric', 'Kategori' => 'Hand Tools'],
            ],
            [
                'slug' => 'hex-bolt-grade-8-8',
                'brand' => 'Generic',
                'name' => 'Hex Bolt Grade 8.8',
                'category' => 'fastener',
                'category_label' => 'Fastener',
                'meta' => 'M6–M20',
                'icon' => 'bi-nut',
                'description' => 'Baut hex grade 8.8 dalam beberapa pilihan ukuran untuk pekerjaan konstruksi dan mekanik.',
                'specs' => ['Tipe' => 'Hex Bolt', 'Grade' => '8.8', 'Ukuran' => 'M6–M20', 'Kategori' => 'Fastener'],
            ],
            [
                'slug' => 'bosch-impact-drill-13mm',
                'brand' => 'Bosch',
                'name' => 'Impact Drill 13 mm',
                'category' => 'power-tools',
                'category_label' => 'Power Tools',
                'meta' => '550 W',
                'icon' => 'bi-tools',
                'description' => 'Bor impact 13 mm untuk pekerjaan pengeboran umum di workshop dan proyek.',
                'specs' => ['Brand' => 'Bosch', 'Tipe' => 'Impact Drill', 'Chuck' => '13 mm', 'Daya' => '550 Watt', 'Kategori' => 'Power Tools'],
            ],
            [
                'slug' => 'grundfos-booster-pump',
                'brand' => 'Grundfos',
                'name' => 'Booster Pump',
                'category' => 'pompa',
                'category_label' => 'Pompa',
                'meta' => 'Automatic',
                'icon' => 'bi-water',
                'description' => 'Pompa booster otomatis untuk membantu menjaga tekanan air.',
                'specs' => ['Brand' => 'Grundfos', 'Tipe' => 'Booster Pump', 'Kontrol' => 'Automatic', 'Kategori' => 'Pompa'],
            ],
            [
                'slug' => 'tekiro-combination-pliers-8-inch',
                'brand' => 'Tekiro',
                'name' => 'Combination Pliers 8 inch',
                'category' => 'hand-tools',
                'category_label' => 'Hand Tools',
                'meta' => '8 inch',
                'icon' => 'bi-tools',
                'description' => 'Tang kombinasi ukuran 8 inch untuk pekerjaan mekanik dan kelistrikan umum.',
                'specs' => ['Brand' => 'Tekiro', 'Tipe' => 'Combination Pliers', 'Ukuran' => '8 inch', 'Kategori' => 'Hand Tools'],
            ],
            [
                'slug' => 'stainless-washer',
                'brand' => 'Generic',
                'name' => 'Stainless Washer',
                'category' => 'fastener',
                'category_label' => 'Fastener',
                'meta' => 'M8–M12',
                'icon' => 'bi-circle',
                'description' => 'Washer stainless dalam beberapa ukuran untuk kebutuhan sambungan dan pengikat.',
                'specs' => ['Tipe' => 'Flat Washer', 'Material' => 'Stainless Steel', 'Ukuran' => 'M8–M12', 'Kategori' => 'Fastener'],
            ],
        ];
    }

    public static function findProduct(string $slug): ?array
    {
        foreach (self::products() as $product) {
            if ($product['slug'] === $slug) {
                return $product;
            }
        }

        return null;
    }
}
