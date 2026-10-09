<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ObjectStorage;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;
use Throwable;

class Products extends BaseController
{
    public function index(): string
    {
        $db = db_connect();
        $search = trim((string) $this->request->getGet('q'));
        $status = (string) $this->request->getGet('status');
        $categoryId = (int) $this->request->getGet('category');
        $status = in_array($status, ['active', 'inactive'], true) ? $status : '';
        $countBuilder = $db->table('products');
        $builder = $db->table('products')
            ->select('products.id, products.name, products.slug, products.sku, products.is_active, products.is_featured, products.updated_at, categories.name AS category_name, brands.name AS brand_name, product_images.url AS image_url')
            ->join('categories', 'categories.id = products.category_id')
            ->join('brands', 'brands.id = products.brand_id', 'left')
            ->join('product_images', 'product_images.product_id = products.id AND product_images.is_primary = 1', 'left');

        if ($search !== '') {
            $countBuilder->groupStart()->like('name', $search)->orLike('sku', $search)->orLike('slug', $search)->groupEnd();
            $builder->groupStart()->like('products.name', $search)->orLike('products.sku', $search)->orLike('products.slug', $search)->groupEnd();
        }
        if ($status !== '') {
            $countBuilder->where('is_active', $status === 'active' ? 1 : 0);
            $builder->where('products.is_active', $status === 'active' ? 1 : 0);
        }
        if ($categoryId > 0) {
            $countBuilder->where('category_id', $categoryId);
            $builder->where('products.category_id', $categoryId);
        }

        $total = $countBuilder->countAllResults();
        $perPage = 20;
        $pageCount = max(1, (int) ceil($total / $perPage));
        $page = min($pageCount, max(1, (int) $this->request->getGet('page')));
        $products = $builder
            ->orderBy('products.updated_at', 'DESC')
            ->orderBy('products.id', 'DESC')
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()
            ->getResultArray();

        return view('admin/products/index', [
            'title' => 'Produk — Admin Mulyorejeki',
            'products' => $products,
            'categories' => (new CategoryModel())->orderBy('name', 'ASC')->findAll(),
            'filters' => ['q' => $search, 'status' => $status, 'category' => $categoryId],
            'total' => $total,
            'page' => $page,
            'pageCount' => $pageCount,
        ]);
    }

    public function create(): string
    {
        return view('admin/products/form', $this->formViewData(null));
    }

    public function store()
    {
        if (! $this->validate($this->productRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (! $this->validateImageIfPresent()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (! $this->relationsExist()) {
            return redirect()->back()->withInput()->with('error', 'Kategori atau merek yang dipilih tidak valid.');
        }

        $model = new ProductModel();
        $data = $this->productPayload();
        $data['slug'] = $this->makeUniqueSlug($data['name']);

        $productId = $model->insert($data, true);
        if ($productId === false) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        try {
            $this->storeUploadedImage((int) $productId, $data['name']);
        } catch (Throwable $e) {
            log_message('error', 'Product image upload failed: {message}', ['message' => $e->getMessage()]);

            return redirect()
                ->to(site_url('admin/products/' . $productId . '/edit'))
                ->with('warning', 'Produk tersimpan, tetapi gambar gagal diunggah: ' . $e->getMessage());
        }

        return redirect()->to(site_url('admin/products'))->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $product = (new ProductModel())->find($id);
        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        return view('admin/products/form', $this->formViewData($product));
    }

    public function update(int $id)
    {
        $model = new ProductModel();
        $product = $model->find($id);
        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        if (! $this->validate($this->productRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (! $this->validateImageIfPresent()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (! $this->relationsExist()) {
            return redirect()->back()->withInput()->with('error', 'Kategori atau merek yang dipilih tidak valid.');
        }

        $data = $this->productPayload();
        $model->update($id, $data);

        try {
            $this->storeUploadedImage($id, $data['name']);
        } catch (Throwable $e) {
            log_message('error', 'Product image upload failed: {message}', ['message' => $e->getMessage()]);

            return redirect()
                ->back()
                ->with('warning', 'Data produk tersimpan, tetapi gambar gagal diunggah: ' . $e->getMessage());
        }

        return redirect()->to(site_url('admin/products/' . $id . '/edit'))->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $model = new ProductModel();
        $product = $model->find($id);
        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        $images = (new ProductImageModel())->where('product_id', $id)->findAll();
        $storage = new ObjectStorage();

        foreach ($images as $image) {
            try {
                $storage->delete((string) $image['object_key']);
            } catch (Throwable $e) {
                log_message('warning', 'Unable to delete product object {key}: {message}', [
                    'key' => $image['object_key'],
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $model->delete($id);

        return redirect()->to(site_url('admin/products'))->with('success', 'Produk berhasil dihapus.');
    }

    public function deleteImage(int $productId, int $imageId)
    {
        $imageModel = new ProductImageModel();
        $image = $imageModel->where('id', $imageId)->where('product_id', $productId)->first();
        if ($image === null) {
            throw PageNotFoundException::forPageNotFound('Gambar tidak ditemukan.');
        }

        try {
            (new ObjectStorage())->delete((string) $image['object_key']);
        } catch (Throwable $e) {
            log_message('warning', 'Unable to delete product object {key}: {message}', [
                'key' => $image['object_key'],
                'message' => $e->getMessage(),
            ]);
        }

        $wasPrimary = ! empty($image['is_primary']);
        $imageModel->delete($imageId);

        if ($wasPrimary) {
            $replacement = $imageModel->where('product_id', $productId)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->first();
            if ($replacement !== null) {
                $imageModel->update($replacement['id'], ['is_primary' => 1]);
            }
        }

        return redirect()->to(site_url('admin/products/' . $productId . '/edit'))->with('success', 'Gambar berhasil dihapus.');
    }

    public function setPrimaryImage(int $productId, int $imageId)
    {
        $imageModel = new ProductImageModel();
        $image = $imageModel->where('id', $imageId)->where('product_id', $productId)->first();
        if ($image === null) {
            throw PageNotFoundException::forPageNotFound('Gambar tidak ditemukan.');
        }

        $db = db_connect();
        $db->transStart();
        $db->table('product_images')->where('product_id', $productId)->update(['is_primary' => 0]);
        $db->table('product_images')->where('id', $imageId)->update(['is_primary' => 1]);
        $db->transComplete();

        return redirect()->to(site_url('admin/products/' . $productId . '/edit'))->with('success', 'Gambar utama diperbarui.');
    }

    private function formViewData(?array $product): array
    {
        $categories = (new CategoryModel())
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();
        $brands = (new BrandModel())
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $images = [];
        $specificationsText = '';
        if ($product !== null) {
            $images = (new ProductImageModel())
                ->where('product_id', $product['id'])
                ->orderBy('is_primary', 'DESC')
                ->orderBy('sort_order', 'ASC')
                ->findAll();
            $specificationsText = $this->specificationsToText((string) ($product['specifications'] ?? ''));
        }

        return [
            'title' => ($product === null ? 'Tambah Produk' : 'Edit Produk') . ' — Admin Mulyorejeki',
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'images' => $images,
            'specificationsText' => $specificationsText,
        ];
    }

    private function productRules(): array
    {
        return [
            'name' => 'required|max_length[180]',
            'sku' => 'permit_empty|max_length[100]',
            'category_id' => 'required|is_natural_no_zero',
            'brand_id' => 'permit_empty|is_natural_no_zero',
            'short_description' => 'permit_empty|max_length[500]',
            'description' => 'permit_empty|max_length[10000]',
            'meta' => 'permit_empty|max_length[160]',
            'icon' => 'permit_empty|max_length[80]',
            'badge' => 'permit_empty|max_length[80]',
            'sort_order' => 'permit_empty|integer',
            'specifications_text' => 'permit_empty|max_length[10000]',
        ];
    }

    private function productPayload(): array
    {
        return [
            'category_id' => (int) $this->request->getPost('category_id'),
            'brand_id' => $this->nullableInt($this->request->getPost('brand_id')),
            'name' => trim((string) $this->request->getPost('name')),
            'sku' => $this->nullableString($this->request->getPost('sku')),
            'short_description' => $this->nullableString($this->request->getPost('short_description')),
            'description' => $this->nullableString($this->request->getPost('description')),
            'meta' => $this->nullableString($this->request->getPost('meta')),
            'icon' => $this->nullableString($this->request->getPost('icon')) ?? 'bi-tools',
            'badge' => $this->nullableString($this->request->getPost('badge')),
            'specifications' => json_encode(
                $this->parseSpecifications((string) $this->request->getPost('specifications_text')),
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
            'sort_order' => (int) ($this->request->getPost('sort_order') ?: 0),
            'is_featured' => $this->request->getPost('is_featured') === '1' ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') === '1' ? 1 : 0,
        ];
    }

    private function relationsExist(): bool
    {
        $categoryId = (int) $this->request->getPost('category_id');
        if ((new CategoryModel())->find($categoryId) === null) {
            return false;
        }

        $brandId = $this->nullableInt($this->request->getPost('brand_id'));

        return $brandId === null || (new BrandModel())->find($brandId) !== null;
    }

    private function makeUniqueSlug(string $source): string
    {
        helper('url');

        $base = url_title($source, '-', true);
        if ($base === '') {
            $base = 'product-' . bin2hex(random_bytes(4));
        }

        $slug = $base;
        $suffix = 2;
        $db = db_connect();

        while (true) {
            $builder = $db->table('products')->where('slug', $slug);
            if ($builder->countAllResults() === 0) {
                return $slug;
            }

            $slug = $base . '-' . $suffix;
            $suffix++;
        }
    }

    private function validateImageIfPresent(): bool
    {
        $file = $this->request->getFile('image');
        if (! $file instanceof UploadedFile || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return true;
        }

        return $this->validate([
            'image' => [
                'label' => 'Gambar produk',
                'rules' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,5120]',
            ],
        ]);
    }

    private function storeUploadedImage(int $productId, string $productName): void
    {
        $file = $this->request->getFile('image');
        if (! $file instanceof UploadedFile || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return;
        }

        if (! $file->isValid()) {
            throw new RuntimeException($file->getErrorString() ?: 'Upload gambar tidak valid.');
        }

        $stored = (new ObjectStorage())->storeProductImage($file);
        $imageModel = new ProductImageModel();
        $hasPrimary = $imageModel->where('product_id', $productId)->where('is_primary', 1)->countAllResults() > 0;

        $imageModel->insert([
            'product_id' => $productId,
            'object_key' => $stored['key'],
            'url' => $stored['url'],
            'alt_text' => $productName,
            'sort_order' => 0,
            'is_primary' => $hasPrimary ? 0 : 1,
        ]);
    }

    private function parseSpecifications(string $value): array
    {
        $result = [];
        foreach (preg_split('/\R/', $value) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || ! str_contains($line, ':')) {
                continue;
            }

            [$key, $itemValue] = array_map('trim', explode(':', $line, 2));
            if ($key !== '' && $itemValue !== '') {
                $result[$key] = $itemValue;
            }
        }

        return $result;
    }

    private function specificationsToText(string $json): string
    {
        if ($json === '') {
            return '';
        }

        $decoded = json_decode($json, true);
        if (! is_array($decoded)) {
            return '';
        }

        $lines = [];
        foreach ($decoded as $key => $value) {
            if (is_scalar($value)) {
                $lines[] = $key . ': ' . $value;
            }
        }

        return implode(PHP_EOL, $lines);
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }
}
