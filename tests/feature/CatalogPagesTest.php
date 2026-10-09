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
        $result->assertSee('Cari pompa, perkakas, atau baut?');
        $result->assertSee('Cari berdasarkan jenis barang');
        $result->assertDontSee('WhatsApp: akan diisi');
        $result->assertDontSee('href="#" class="btn btn-accent');
        $result->assertSee('https://wa.me/628122802283');
        $result->assertSee('Jl. K.H. Agus Salim, Purwodinatan');
        $result->assertSee('Sabtu 08.00–14.00 WIB');
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
