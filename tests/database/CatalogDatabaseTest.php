<?php

use App\Database\Seeds\CatalogSeeder;
use App\Libraries\CatalogRepository;
use App\Models\ProductModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class CatalogDatabaseTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;
    protected $seed = CatalogSeeder::class;

    public function testCatalogSeederCreatesProducts(): void
    {
        $products = (new ProductModel())->findAll();

        $this->assertCount(8, $products);
    }

    public function testRepositoryReadsDatabaseCatalog(): void
    {
        $repository = new CatalogRepository($this->db);

        $this->assertTrue($repository->usingDatabase());
        $this->assertCount(6, $repository->categories());
        $this->assertCount(8, $repository->products());

        $product = $repository->findProduct('makita-angle-grinder-100mm');

        $this->assertNotNull($product);
        $this->assertSame('Makita', $product['brand']);
        $this->assertSame('Power Tools', $product['category_label']);
        $this->assertSame('720 Watt', $product['specs']['Daya']);
    }
}
