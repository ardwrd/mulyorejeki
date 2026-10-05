<?php

namespace App\Controllers;

use App\Libraries\CatalogData;
use CodeIgniter\Exceptions\PageNotFoundException;

class Catalog extends BaseController
{
    public function index(): string
    {
        return view('catalog/index', [
            'title' => 'Katalog Produk — Mulyorejeki',
            'description' => 'Katalog produk teknik Mulyorejeki.',
            'activePage' => 'products',
            'categories' => CatalogData::categories(),
            'products' => CatalogData::products(),
        ]);
    }

    public function show(string $slug): string
    {
        $product = CatalogData::findProduct($slug);

        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        $related = array_values(array_filter(
            CatalogData::products(),
            static fn (array $item): bool => $item['category'] === $product['category'] && $item['slug'] !== $product['slug']
        ));

        return view('catalog/detail', [
            'title' => $product['name'] . ' — Mulyorejeki',
            'description' => $product['description'],
            'activePage' => 'products',
            'product' => $product,
            'relatedProducts' => array_slice($related, 0, 4),
        ]);
    }
}
