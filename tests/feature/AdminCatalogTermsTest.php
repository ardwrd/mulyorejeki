<?php

use App\Database\Seeds\CatalogSeeder;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class AdminCatalogTermsTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;
    protected $seed = CatalogSeeder::class;

    public function testTaxonomyPagesRequireAuthentication(): void
    {
        $this->get('/admin/categories')->assertRedirectTo('/admin/login');
        $this->get('/admin/brands')->assertRedirectTo('/admin/login');
    }

    public function testAdminCanOpenCategoryAndBrandPages(): void
    {
        $this->withSession(['admin_user_id' => 1, 'admin_user_name' => 'Admin']);

        $this->get('/admin/categories')->assertSee('Power Tools');
        $this->get('/admin/brands')->assertSee('Makita');
        $this->get('/admin/categories/new')->assertSee('Tambah Kategori');
        $this->get('/admin/brands/new')->assertSee('Tambah Merek');
        $this->get('/admin/categories/new')->assertDontSee('name="slug"');
        $this->get('/admin/brands/new')->assertDontSee('name="slug"');
        $this->get('/admin/products/new')->assertDontSee('name="slug"');
        $categoryForm = $this->get('/admin/categories/new');
        $categoryForm->assertDontSee('name="icon"');
        $categoryForm->assertDontSee('name="sort_order"');
        $brandForm = $this->get('/admin/brands/new');
        $brandForm->assertDontSee('name="logo_url"');
        $brandForm->assertDontSee('name="sort_order"');
        $productForm = $this->get('/admin/products/new');
        $productForm->assertDontSee('name="icon"');
        $productForm->assertDontSee('name="sort_order"');
    }

    public function testProductFiltersAndDashboardRender(): void
    {
        $this->withSession(['admin_user_id' => 1, 'admin_user_name' => 'Admin']);

        $this->get('/admin')->assertSee('Produk terbaru');
        $this->get('/admin/products')->assertSee('Angle Grinder 100 mm');
        $this->get('/admin/products?q=Makita')->assertSee('Angle Grinder 100 mm');
        $this->get('/admin/products?q=produk-tidak-ada')->assertSee('Produk tidak ditemukan');
    }

    public function testCategoryCanBeCreatedAndCannotBeDeletedWhileUsed(): void
    {
        $this->withSession(['admin_user_id' => 1, 'admin_user_name' => 'Admin']);

        $previousOrder = (int) db_connect()->table('categories')->selectMax('sort_order')->get()->getRow('sort_order');
        $this->post('/admin/categories', ['name' => 'Peralatan Las', 'icon' => 'bi-malicious', 'sort_order' => '-10', 'is_active' => '1', csrf_token() => csrf_hash()])
            ->assertRedirectTo('/admin/categories');

        $created = (new CategoryModel())->where('slug', 'peralatan-las')->first();
        $this->assertNotNull($created);
        $this->assertSame('Peralatan Las', $created['name']);
        $this->assertSame('bi-grid', $created['icon']);
        $this->assertSame($previousOrder + 1, (int) $created['sort_order']);

        $this->post('/admin/categories/' . $created['id'], ['name' => 'Peralatan Las Baru', 'icon' => 'bi-other', 'sort_order' => '99', 'is_active' => '1', csrf_token() => csrf_hash()])
            ->assertRedirectTo('/admin/categories');
        $updated = (new CategoryModel())->find($created['id']);
        $this->assertSame('bi-grid', $updated['icon']);
        $this->assertSame($previousOrder + 1, (int) $updated['sort_order']);

        $used = (new CategoryModel())->where('slug', 'power-tools')->first();
        $this->assertNotNull($used);
        $this->post('/admin/categories/' . $used['id'] . '/delete', [csrf_token() => csrf_hash()])
            ->assertRedirectTo('/admin/categories');
        $this->assertNotNull((new CategoryModel())->find($used['id']));
    }

    public function testUnusedBrandCanBeRemoved(): void
    {
        $this->withSession(['admin_user_id' => 1, 'admin_user_name' => 'Admin']);

        $brandId = (new BrandModel())->insert(['name' => 'Merek Uji', 'slug' => 'merek-uji', 'is_active' => 1]);
        $this->post('/admin/brands/' . $brandId . '/delete', [csrf_token() => csrf_hash()])->assertRedirectTo('/admin/brands');

        $this->assertNull((new BrandModel())->find($brandId));
    }

    public function testBrandCanBeCreatedAndUpdated(): void
    {
        $this->withSession(['admin_user_id' => 1, 'admin_user_name' => 'Admin']);

        $previousOrder = (int) db_connect()->table('brands')->selectMax('sort_order')->get()->getRow('sort_order');
        $this->post('/admin/brands', ['name' => 'Merek Baru', 'sort_order' => '-10', 'logo_url' => 'https://example.test/ignored.png', 'is_active' => '1', csrf_token() => csrf_hash()])
            ->assertRedirectTo('/admin/brands');
        $brand = (new BrandModel())->where('slug', 'merek-baru')->first();
        $this->assertNotNull($brand);
        $this->assertSame($previousOrder + 1, (int) $brand['sort_order']);
        $this->assertEmpty($brand['logo_url']);

        (new BrandModel())->update($brand['id'], ['logo_url' => 'https://example.test/existing.png']);

        $this->post('/admin/brands/' . $brand['id'], [
            'name' => 'Merek Diperbarui',
            'slug' => 'slug-yang-diabaikan',
            'sort_order' => '2',
            'is_active' => '0',
            csrf_token() => csrf_hash(),
        ])->assertRedirectTo('/admin/brands');

        $updated = (new BrandModel())->find($brand['id']);
        $this->assertSame('Merek Diperbarui', $updated['name']);
        $this->assertSame('merek-baru', $updated['slug']);
        $this->assertSame(0, (int) $updated['is_active']);
        $this->assertSame($previousOrder + 1, (int) $updated['sort_order']);
        $this->assertSame('https://example.test/existing.png', $updated['logo_url']);
    }

    public function testProductSlugIsGeneratedAndDoesNotChangeOnEdit(): void
    {
        $this->withSession(['admin_user_id' => 1, 'admin_user_name' => 'Admin']);
        $category = (new CategoryModel())->where('slug', 'power-tools')->first();
        $this->assertNotNull($category);
        $previousOrder = (int) db_connect()->table('products')->selectMax('sort_order')->get()->getRow('sort_order');

        $this->post('/admin/products', [
            'name' => 'Bor Listrik Uji',
            'slug' => 'slug-yang-diabaikan',
            'icon' => 'bi-other',
            'sort_order' => '-10',
            'category_id' => $category['id'],
            'is_active' => '1',
            csrf_token() => csrf_hash(),
        ])->assertRedirectTo('/admin/products');

        $product = (new ProductModel())->where('slug', 'bor-listrik-uji')->first();
        $this->assertNotNull($product);
        $this->assertSame($category['icon'], $product['icon']);
        $this->assertSame($previousOrder + 1, (int) $product['sort_order']);

        $this->post('/admin/products/' . $product['id'], [
            'name' => 'Bor Listrik Baru',
            'slug' => 'slug-lain-yang-diabaikan',
            'icon' => 'bi-other',
            'sort_order' => '99',
            'category_id' => $category['id'],
            'is_active' => '1',
            csrf_token() => csrf_hash(),
        ])->assertRedirectTo('/admin/products/' . $product['id'] . '/edit');

        $updated = (new ProductModel())->find($product['id']);
        $this->assertSame('Bor Listrik Baru', $updated['name']);
        $this->assertSame('bor-listrik-uji', $updated['slug']);
        $this->assertSame($category['icon'], $updated['icon']);
        $this->assertSame($previousOrder + 1, (int) $updated['sort_order']);
    }
}
