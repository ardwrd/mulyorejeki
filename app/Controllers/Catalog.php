<?php

namespace App\Controllers;

use App\Libraries\CatalogRepository;
use App\Libraries\SiteContent;
use App\Libraries\StoreContact;
use CodeIgniter\Exceptions\PageNotFoundException;

class Catalog extends BaseController
{
    public function index(): string
    {
        $catalog = new CatalogRepository();
        $site = (new SiteContent())->all();

        return view('catalog/index', [
            'title' => 'Katalog Produk — Mulyorejeki',
            'description' => 'Cari pompa, perkakas, baut, dan perlengkapan teknik menurut nama atau kategori.',
            'activePage' => 'products',
            'contactUrl' => StoreContact::whatsappUrl('Halo Mulyorejeki, saya ingin menanyakan produk di katalog.', $site),
            'site' => $site,
            'categories' => $catalog->categories(),
            'products' => $catalog->products(),
        ]);
    }

    public function show(string $slug): string
    {
        $catalog = new CatalogRepository();
        $site = (new SiteContent())->all();
        $product = $catalog->findProduct($slug);

        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        return view('catalog/detail', [
            'title' => $product['name'] . ' — Mulyorejeki',
            'description' => $product['description'],
            'activePage' => 'products',
            'contactUrl' => StoreContact::whatsappUrl('Halo Mulyorejeki, saya ingin menanyakan ' . $product['name'] . '.', $site),
            'site' => $site,
            'product' => $product,
            'relatedProducts' => $catalog->relatedProducts($product['category'], $product['slug'], 4),
        ]);
    }
}
