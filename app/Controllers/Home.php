<?php

namespace App\Controllers;

use App\Libraries\CatalogRepository;
use App\Libraries\StoreContact;

class Home extends BaseController
{
    public function index(): string
    {
        $catalog = new CatalogRepository();

        return view('home/index', [
            'title' => 'Mulyorejeki — Katalog Pompa, Perkakas, dan Baut',
            'description' => 'Lihat pompa, perkakas, baut, dan perlengkapan teknik di katalog Mulyorejeki.',
            'activePage' => 'home',
            'contactUrl' => StoreContact::whatsappUrl('Halo Mulyorejeki, saya ingin menanyakan produk di katalog.'),
            'categories' => $catalog->categories(),
            'brands' => $catalog->brands(),
            'featuredProducts' => $catalog->featuredProducts(4),
        ]);
    }
}
