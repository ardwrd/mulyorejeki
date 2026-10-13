<?php

namespace App\Libraries;

use RuntimeException;

final class SiteContent
{
    public static function defaults(): array
    {
        return [
            'logo_key' => '',
            'logo_url' => '',
            'hero_title' => 'Cari pompa, perkakas, atau baut? Mulai dari sini.',
            'hero_text' => 'Telusuri barang menurut jenis atau merek. Ukuran dan spesifikasi yang tercatat bisa dilihat di halaman produk.',
            'categories_title' => 'Cari berdasarkan jenis barang',
            'products_title' => 'Lihat beberapa produk',
            'brands_title' => 'Merek di katalog',
            'brands_text' => 'Merek yang tercantum pada produk Mulyorejeki. Ketersediaan tiap barang bisa berubah.',
            'about_title' => 'Lihat detail barang sebelum memilih.',
            'about_text' => 'Di sini Anda bisa melihat nama, merek, ukuran, dan spesifikasi yang sudah dicatat. Harga serta stok perlu dipastikan langsung sebelum membeli.',
            'contact_title' => 'Mencari ukuran atau tipe lain?',
            'contact_text' => 'Kirim nama dan ukuran barang lewat WhatsApp, atau datang langsung ke toko.',
            'catalog_text' => 'Cari nama barang atau merek, lalu saring menurut kategori. Harga dan stok perlu dikonfirmasi sebelum membeli.',
            'footer_text' => 'Lihat pompa, perkakas, baut, dan barang teknik lain di katalog toko.',
            'phone' => trim((string) env('store.whatsappNumber', '')) ?: StoreContact::PHONE_DISPLAY,
            'address' => StoreContact::ADDRESS,
            'weekday_hours' => StoreContact::WEEKDAY_HOURS,
            'saturday_hours' => StoreContact::SATURDAY_HOURS,
            'sunday_hours' => StoreContact::SUNDAY_HOURS,
        ];
    }

    public function all(): array
    {
        $content = self::defaults();

        foreach (db_connect()->table('site_content')->select('setting_key, setting_value')->get()->getResultArray() as $row) {
            if (array_key_exists($row['setting_key'], $content)) {
                $content[$row['setting_key']] = (string) ($row['setting_value'] ?? '');
            }
        }

        return $content;
    }

    public function save(array $values): void
    {
        $allowed = self::defaults();
        $db = db_connect();
        $db->transStart();

        foreach ($values as $key => $value) {
            if (! array_key_exists($key, $allowed)) {
                continue;
            }

            $existing = $db->table('site_content')->where('setting_key', $key)->countAllResults() > 0;
            $row = ['setting_value' => (string) $value, 'updated_at' => date('Y-m-d H:i:s')];
            if ($existing) {
                $db->table('site_content')->where('setting_key', $key)->update($row);
            } else {
                $row['setting_key'] = $key;
                $db->table('site_content')->insert($row);
            }
        }

        $db->transComplete();
        if (! $db->transStatus()) {
            throw new RuntimeException('Pengaturan situs gagal disimpan.');
        }
    }
}
