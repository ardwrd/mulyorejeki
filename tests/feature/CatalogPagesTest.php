<?php

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class CatalogPagesTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testHomePageLoads(): void
    {
        $result = $this->get('/');

        $result->assertStatus(200);
        $result->assertSee('Pompa, gerinda, baut');
        $result->assertSee('Pilih jenis barang yang dicari');
    }

    public function testCatalogPageLoads(): void
    {
        $result = $this->get('/products');

        $result->assertStatus(200);
        $result->assertSee('Katalog produk');
        $result->assertSee('Angle Grinder 100 mm');
    }

    public function testProductDetailPageLoads(): void
    {
        $result = $this->get('/products/makita-angle-grinder-100mm');

        $result->assertStatus(200);
        $result->assertSee('Angle Grinder 100 mm');
        $result->assertSee('Spesifikasi');
    }

    public function testUnknownProductThrowsPageNotFound(): void
    {
        $this->expectException(PageNotFoundException::class);
        $this->get('/products/produk-tidak-ada');
    }
}
