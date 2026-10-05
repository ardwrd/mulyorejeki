<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\ProductModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        return view('admin/dashboard', [
            'title' => 'Dashboard Admin — Mulyorejeki',
            'productCount' => (new ProductModel())->countAllResults(),
            'categoryCount' => (new CategoryModel())->countAllResults(),
            'brandCount' => (new BrandModel())->countAllResults(),
        ]);
    }
}
