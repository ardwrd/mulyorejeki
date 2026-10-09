<?php

namespace App\Controllers;

use App\Libraries\CatalogRepository;
use App\Libraries\StoreContact;
use CodeIgniter\Exceptions\PageNotFoundException;

class Catalog extends BaseController
{
    public function index(): string
    {
        $catalog = new CatalogRepository();

        return view('catalog/index', [
            'title' => 'Katalog Produk — Mulyorejeki',
            'description' => 'Cari pompa, perkakas, baut, dan perlengkapan teknik menurut nama atau kategori.',
            'activePage' => 'products',
            'contactUrl' => StoreContact::whatsappUrl('Halo Mulyorejeki, saya ingin menanyakan produk di katalog.'),
            'categories' => $catalog->categories(),
            'products' => $catalog->products(),
        ]);
    }

    public function show(string $slug): string
    {
        $catalog = new CatalogRepository();
        $product = $catalog->findProduct($slug);

        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        return view('catalog/detail', [
            'title' => $product['name'] . ' — Mulyorejeki',
            'description' => $product['description'],
            'activePage' => 'products',
            'contactUrl' => StoreContact::whatsappUrl('Halo Mulyorejeki, saya ingin menanyakan ' . $product['name'] . '.'),
            'product' => $product,
            'relatedProducts' => $catalog->relatedProducts($product['category'], $product['slug'], 4),
        ]);
    }
}
