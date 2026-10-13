<?php

namespace App\Controllers;

use App\Libraries\CatalogRepository;
use App\Libraries\SiteContent;
use App\Libraries\StoreContact;

class Home extends BaseController
{
    public function index(): string
    {
        $catalog = new CatalogRepository();
        $site = (new SiteContent())->all();

        return view('home/index', [
            'title' => 'Mulyorejeki — Katalog Pompa, Perkakas, dan Baut',
            'description' => 'Lihat pompa, perkakas, baut, dan perlengkapan teknik di Mulyorejeki, Semarang Tengah.',
            'activePage' => 'home',
            'contactUrl' => StoreContact::whatsappUrl('Halo Mulyorejeki, saya ingin menanyakan produk di katalog.', $site),
            'site' => $site,
            'categories' => $catalog->categories(),
            'brands' => $catalog->brands(),
            'featuredProducts' => $catalog->featuredProducts(4),
        ]);
    }
}
