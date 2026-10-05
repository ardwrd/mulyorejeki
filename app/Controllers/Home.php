<?php

namespace App\Controllers;

use App\Libraries\CatalogData;

class Home extends BaseController
{
    public function index(): string
    {
        $products = CatalogData::products();

        return view('home/index', [
            'title' => 'Mulyorejeki — Toko Teknik & Peralatan Industri',
            'description' => 'Katalog pompa, gerinda, baut, perkakas, dan kebutuhan teknik untuk workshop maupun proyek.',
            'activePage' => 'home',
            'categories' => CatalogData::categories(),
            'brands' => CatalogData::brands(),
            'featuredProducts' => array_slice($products, 0, 4),
        ]);
    }
}
