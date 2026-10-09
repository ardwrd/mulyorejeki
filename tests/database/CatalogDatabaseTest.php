<?php

use App\Database\Seeds\CatalogSeeder;
use App\Database\Migrations\RefineCatalogCopy;
use App\Libraries\CatalogRepository;
use App\Models\ProductModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Database;

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

    public function testCopyMigrationOnlyReplacesUneditedSeedText(): void
    {
        $this->db->table('products')->where('slug', 'makita-angle-grinder-100mm')->update([
            'short_description' => 'Gerinda tangan 100 mm untuk pekerjaan potong dan grinding di workshop maupun lapangan.',
            'description' => 'Deskripsi yang ditulis admin.',
        ]);

        (new RefineCatalogCopy(Database::forge($this->db)))->up();

        $product = $this->db->table('products')->where('slug', 'makita-angle-grinder-100mm')->get()->getRowArray();
        $this->assertSame('Gerinda tangan Makita dengan cakram 100 mm dan daya 720 W.', $product['short_description']);
        $this->assertSame('Deskripsi yang ditulis admin.', $product['description']);
    }
}
