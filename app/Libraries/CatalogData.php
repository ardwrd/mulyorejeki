<?php

namespace App\Libraries;

final class CatalogData
{
    public static function categories(): array
    {
        return [
            ['slug' => 'pompa', 'name' => 'Pompa', 'description' => 'Pompa air, booster, dan pompa transfer', 'icon' => 'bi-droplet-half'],
            ['slug' => 'power-tools', 'name' => 'Power Tools', 'description' => 'Gerinda, bor, dan mesin potong', 'icon' => 'bi-lightning-charge'],
            ['slug' => 'fastener', 'name' => 'Baut & Fastener', 'description' => 'Baut, mur, dan ring', 'icon' => 'bi-nut'],
            ['slug' => 'hand-tools', 'name' => 'Hand Tools', 'description' => 'Kunci, tang, dan obeng', 'icon' => 'bi-wrench-adjustable'],
            ['slug' => 'plumbing', 'name' => 'Plumbing', 'description' => 'Fitting, katup, dan selang', 'icon' => 'bi-pipe'],
            ['slug' => 'electrical', 'name' => 'Electrical', 'description' => 'Komponen dan perlengkapan listrik', 'icon' => 'bi-plug'],
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
                'description' => 'Gerinda tangan Makita dengan cakram 100 mm dan daya 720 W.',
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
                'description' => 'Pompa sentrifugal Ebara berbahan stainless steel untuk memindahkan air.',
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
                'description' => 'Set kunci kombinasi Tekiro dengan ukuran metrik.',
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
                'description' => 'Baut kepala segi enam grade 8.8, ukuran M6–M20.',
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
                'description' => 'Bor impact Bosch dengan chuck 13 mm dan daya 550 W.',
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
                'description' => 'Pompa booster Grundfos dengan kontrol otomatis untuk membantu menjaga tekanan air.',
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
                'description' => 'Tang kombinasi Tekiro ukuran 8 inci.',
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
                'description' => 'Ring datar berbahan stainless steel, ukuran M8–M12.',
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
