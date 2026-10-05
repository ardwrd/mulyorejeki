<?php

namespace App\Controllers;

use App\Libraries\CatalogRepository;

class Home extends BaseController
{
    public function index(): string
    {
        $catalog = new CatalogRepository();

        return view('home/index', [
            'title' => 'Mulyorejeki — Toko Teknik & Peralatan Industri',
            'description' => 'Katalog pompa, gerinda, baut, perkakas, dan kebutuhan teknik untuk workshop maupun proyek.',
            'activePage' => 'home',
            'categories' => $catalog->categories(),
            'brands' => $catalog->brands(),
            'featuredProducts' => $catalog->featuredProducts(4),
        ]);
    }
}
