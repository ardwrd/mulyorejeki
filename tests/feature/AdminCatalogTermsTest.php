<?php

use App\Database\Seeds\CatalogSeeder;
use App\Models\BrandModel;
use App\Models\CategoryModel;
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

        $this->post('/admin/categories', ['name' => 'Peralatan Las', 'sort_order' => '4', 'is_active' => '1', csrf_token() => csrf_hash()])
            ->assertRedirectTo('/admin/categories');

        $created = (new CategoryModel())->where('slug', 'peralatan-las')->first();
        $this->assertNotNull($created);
        $this->assertSame('Peralatan Las', $created['name']);

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

        $this->post('/admin/brands', ['name' => 'Merek Baru', 'is_active' => '1', csrf_token() => csrf_hash()])
            ->assertRedirectTo('/admin/brands');
        $brand = (new BrandModel())->where('slug', 'merek-baru')->first();
        $this->assertNotNull($brand);

        $this->post('/admin/brands/' . $brand['id'], [
            'name' => 'Merek Diperbarui',
            'slug' => 'merek-baru',
            'sort_order' => '2',
            'is_active' => '0',
            csrf_token() => csrf_hash(),
        ])->assertRedirectTo('/admin/brands');

        $updated = (new BrandModel())->find($brand['id']);
        $this->assertSame('Merek Diperbarui', $updated['name']);
        $this->assertSame(0, (int) $updated['is_active']);
    }
}
