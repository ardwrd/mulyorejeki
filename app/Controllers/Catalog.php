<?php

namespace App\Controllers;

use App\Libraries\CatalogRepository;
use CodeIgniter\Exceptions\PageNotFoundException;

class Catalog extends BaseController
{
    public function index(): string
    {
        $catalog = new CatalogRepository();

        return view('catalog/index', [
            'title' => 'Katalog Produk — Mulyorejeki',
            'description' => 'Katalog produk teknik Mulyorejeki.',
            'activePage' => 'products',
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
            'product' => $product,
            'relatedProducts' => $catalog->relatedProducts($product['category'], $product['slug'], 4),
        ]);
    }
}
