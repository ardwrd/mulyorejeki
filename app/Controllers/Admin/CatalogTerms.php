<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CatalogTerms extends BaseController
{
    public function categories(): string
    {
        return $this->indexTerms('categories');
    }

    public function brands(): string
    {
        return $this->indexTerms('brands');
    }

    public function newCategory(): string
    {
        return $this->form('categories');
    }

    public function newBrand(): string
    {
        return $this->form('brands');
    }

    public function editCategory(int $id): string
    {
        return $this->form('categories', $id);
    }

    public function editBrand(int $id): string
    {
        return $this->form('brands', $id);
    }

    public function storeCategory()
    {
        return $this->save('categories');
    }

    public function storeBrand()
    {
        return $this->save('brands');
    }

    public function updateCategory(int $id)
    {
        return $this->save('categories', $id);
    }

    public function updateBrand(int $id)
    {
        return $this->save('brands', $id);
    }

    public function deleteCategory(int $id)
    {
        return $this->deleteTerm('categories', $id);
    }

    public function deleteBrand(int $id)
    {
        return $this->deleteTerm('brands', $id);
    }

    private function indexTerms(string $type): string
    {
        $foreignKey = $type === 'categories' ? 'category_id' : 'brand_id';
        $terms = $this->modelFor($type)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();
        $counts = db_connect()->table('products')
            ->select($foreignKey . ', COUNT(*) AS product_count')
            ->groupBy($foreignKey)
            ->get()
            ->getResultArray();
        $countById = array_column($counts, 'product_count', $foreignKey);
        foreach ($terms as &$term) {
            $term['product_count'] = (int) ($countById[$term['id']] ?? 0);
        }
        unset($term);

        return view('admin/terms/index', [
            'title' => ($type === 'categories' ? 'Kategori' : 'Merek') . ' — Admin Mulyorejeki',
            'type' => $type,
            'label' => $type === 'categories' ? 'Kategori' : 'Merek',
            'terms' => $terms,
        ]);
    }

    private function form(string $type, ?int $id = null): string
    {
        $term = $id === null ? null : $this->findTerm($type, $id);
        $label = $type === 'categories' ? 'Kategori' : 'Merek';

        return view('admin/terms/form', [
            'title' => ($term === null ? 'Tambah ' : 'Edit ') . $label . ' — Admin Mulyorejeki',
            'type' => $type,
            'label' => $label,
            'term' => $term,
        ]);
    }

    private function save(string $type, ?int $id = null)
    {
        $term = $id === null ? null : $this->findTerm($type, $id);
        $rules = [
            'name' => 'required|max_length[120]',
        ];
        if ($type === 'categories') {
            $rules['description'] = 'permit_empty|max_length[500]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = $this->modelFor($type);
        $name = trim((string) $this->request->getPost('name'));
        $data = [
            'name' => $name,
            'is_active' => $this->request->getPost('is_active') === '1' ? 1 : 0,
        ];
        if ($id === null) {
            $data['slug'] = $this->uniqueSlug($type, $name);
            $data['sort_order'] = $this->nextSortOrder($type);
        }
        if ($type === 'categories') {
            $data['description'] = $this->nullableString($this->request->getPost('description'));
            if ($id === null) {
                $data['icon'] = 'bi-grid';
            }
        }

        $saved = $id === null ? $model->insert($data) : $model->update($id, $data);
        if ($saved === false) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        $label = $type === 'categories' ? 'Kategori' : 'Merek';

        return redirect()->to(site_url('admin/' . $type))
            ->with('success', $label . ($id === null ? ' berhasil ditambahkan.' : ' berhasil diperbarui.'));
    }

    private function deleteTerm(string $type, int $id)
    {
        $this->findTerm($type, $id);
        $foreignKey = $type === 'categories' ? 'category_id' : 'brand_id';
        $usage = (new ProductModel())->where($foreignKey, $id)->countAllResults();
        $label = $type === 'categories' ? 'Kategori' : 'Merek';
        if ($usage > 0) {
            return redirect()->to(site_url('admin/' . $type))
                ->with('warning', $label . ' masih dipakai oleh ' . $usage . ' produk. Pindahkan produk tersebut sebelum menghapusnya.');
        }

        $this->modelFor($type)->delete($id);

        return redirect()->to(site_url('admin/' . $type))->with('success', $label . ' berhasil dihapus.');
    }

    private function findTerm(string $type, int $id): array
    {
        $term = $this->modelFor($type)->find($id);
        if ($term === null) {
            throw PageNotFoundException::forPageNotFound('Data tidak ditemukan.');
        }

        return $term;
    }

    private function modelFor(string $type): CategoryModel|BrandModel
    {
        return $type === 'categories' ? new CategoryModel() : new BrandModel();
    }

    private function nextSortOrder(string $type): int
    {
        $row = db_connect()->table($type)->selectMax('sort_order')->get()->getRowArray();

        return isset($row['sort_order']) ? (int) $row['sort_order'] + 1 : 0;
    }

    private function uniqueSlug(string $type, string $source): string
    {
        helper('url');
        $base = url_title($source, '-', true);
        if ($base === '') {
            $base = $type === 'categories' ? 'kategori' : 'merek';
        }
        $base = substr($base, 0, 125);
        $slug = $base;
        $suffix = 2;

        while (true) {
            $query = $this->modelFor($type)->where('slug', $slug);
            if ($query->countAllResults() === 0) {
                return $slug;
            }
            $slug = $base . '-' . $suffix++;
        }
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
