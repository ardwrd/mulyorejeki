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
        $productCount = (new ProductModel())->countAllResults();
        $activeCount = (new ProductModel())->where('is_active', 1)->countAllResults();
        $recentProducts = db_connect()->table('products p')
            ->select('p.id, p.name, p.sku, p.is_active, p.updated_at, c.name AS category_name')
            ->join('categories c', 'c.id = p.category_id')
            ->orderBy('p.updated_at', 'DESC')
            ->orderBy('p.id', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return view('admin/dashboard', [
            'title' => 'Dashboard Admin — Mulyorejeki',
            'productCount' => $productCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $productCount - $activeCount,
            'categoryCount' => (new CategoryModel())->countAllResults(),
            'brandCount' => (new BrandModel())->countAllResults(),
            'recentProducts' => $recentProducts,
        ]);
    }
}
