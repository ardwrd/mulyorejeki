<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefineCatalogCopy extends Migration
{
    private const CATEGORY_COPY = [
        'pompa' => ['Pompa air dan kebutuhan transfer fluida', 'Pompa air, booster, dan pompa transfer'],
        'power-tools' => ['Gerinda, bor, cut-off, dan mesin kerja', 'Gerinda, bor, dan mesin potong'],
        'fastener' => ['Baut, mur, washer, dan kebutuhan pengikat', 'Baut, mur, dan ring'],
        'hand-tools' => ['Kunci, tang, obeng, dan alat kerja manual', 'Kunci, tang, dan obeng'],
        'plumbing' => ['Fitting, valve, selang, dan aksesorinya', 'Fitting, katup, dan selang'],
        'electrical' => ['Perlengkapan listrik untuk pekerjaan teknik', 'Komponen dan perlengkapan listrik'],
    ];

    private const PRODUCT_COPY = [
        'makita-angle-grinder-100mm' => ['Gerinda tangan 100 mm untuk pekerjaan potong dan grinding di workshop maupun lapangan.', 'Gerinda tangan Makita dengan cakram 100 mm dan daya 720 W.'],
        'ebara-pompa-centrifugal' => ['Pompa centrifugal untuk kebutuhan transfer air dan penggunaan umum.', 'Pompa sentrifugal Ebara berbahan stainless steel untuk memindahkan air.'],
        'tekiro-combination-wrench-set' => ['Set kunci kombinasi metric untuk kebutuhan perawatan dan pekerjaan mekanik.', 'Set kunci kombinasi Tekiro dengan ukuran metrik.'],
        'hex-bolt-grade-8-8' => ['Baut hex grade 8.8 dalam beberapa pilihan ukuran untuk pekerjaan konstruksi dan mekanik.', 'Baut kepala segi enam grade 8.8, ukuran M6–M20.'],
        'bosch-impact-drill-13mm' => ['Bor impact 13 mm untuk pekerjaan pengeboran umum di workshop dan proyek.', 'Bor impact Bosch dengan chuck 13 mm dan daya 550 W.'],
        'grundfos-booster-pump' => ['Pompa booster otomatis untuk membantu menjaga tekanan air.', 'Pompa booster Grundfos dengan kontrol otomatis untuk membantu menjaga tekanan air.'],
        'tekiro-combination-pliers-8-inch' => ['Tang kombinasi ukuran 8 inch untuk pekerjaan mekanik dan kelistrikan umum.', 'Tang kombinasi Tekiro ukuran 8 inci.'],
        'stainless-washer' => ['Washer stainless dalam beberapa ukuran untuk kebutuhan sambungan dan pengikat.', 'Ring datar berbahan stainless steel, ukuran M8–M12.'],
    ];

    public function up(): void
    {
        $this->replaceCopy(false);
    }

    public function down(): void
    {
        $this->replaceCopy(true);
    }

    private function replaceCopy(bool $reverse): void
    {
        foreach (self::CATEGORY_COPY as $slug => [$old, $new]) {
            $this->db->table('categories')
                ->where('slug', $slug)
                ->where('description', $reverse ? $new : $old)
                ->update(['description' => $reverse ? $old : $new]);
        }

        foreach (self::PRODUCT_COPY as $slug => [$old, $new]) {
            foreach (['short_description', 'description'] as $field) {
                $this->db->table('products')
                    ->where('slug', $slug)
                    ->where($field, $reverse ? $new : $old)
                    ->update([$field => $reverse ? $old : $new]);
            }
        }
    }
}
