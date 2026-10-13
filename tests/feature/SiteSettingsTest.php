<?php

use App\Database\Seeds\CatalogSeeder;
use App\Libraries\SiteContent;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class SiteSettingsTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;
    protected $seed = CatalogSeeder::class;

    public function testSettingsRequireAuthentication(): void
    {
        $this->get('/admin/site')->assertRedirectTo('/admin/login');
        $this->post('/admin/site', [csrf_token() => csrf_hash()])->assertRedirectTo('/admin/login');
        $this->post('/admin/site/logo/delete', [csrf_token() => csrf_hash()])->assertRedirectTo('/admin/login');
    }

    public function testAdminCanEditSiteCopyAndContactDetails(): void
    {
        $this->withSession(['admin_user_id' => 1, 'admin_user_name' => 'Admin']);
        $this->get('/admin/site')->assertSee('Logo toko');

        $values = SiteContent::defaults();
        unset($values['logo_key'], $values['logo_url']);
        $values['hero_title'] = 'Perlengkapan teknik di Semarang';
        $values['catalog_text'] = 'Cari barang yang Anda perlukan.';
        $values['footer_text'] = 'Kunjungi toko kami di Semarang.';
        $values['address'] = 'Jalan Contoh Nomor 1, Semarang';
        $values['phone'] = '0812-3456-7890';

        $this->post('/admin/site', $values + [csrf_token() => csrf_hash()])->assertRedirectTo('/admin/site');

        $home = $this->get('/');
        $home->assertSee('Perlengkapan teknik di Semarang');
        $home->assertSee('Jalan Contoh Nomor 1, Semarang');
        $home->assertSee('Kunjungi toko kami di Semarang.');
        $home->assertSee('wa.me/6281234567890');
        $this->get('/products')->assertSee('Cari barang yang Anda perlukan.');
        $this->assertSame('Perlengkapan teknik di Semarang', (new SiteContent())->all()['hero_title']);
    }

    public function testSiteCopyIsEscapedAndLogoCanBeRemoved(): void
    {
        $this->withSession(['admin_user_id' => 1, 'admin_user_name' => 'Admin']);
        $values = SiteContent::defaults();
        unset($values['logo_key'], $values['logo_url']);
        $values['hero_title'] = '<script>alert(1)</script>';
        $this->post('/admin/site', $values + [csrf_token() => csrf_hash()])->assertRedirectTo('/admin/site');
        $this->get('/')->assertDontSee('<script>alert(1)</script>');

        (new SiteContent())->save(['logo_url' => 'https://example.test/logo.png']);
        $this->get('/')->assertSee('https://example.test/logo.png');
        $this->post('/admin/site/logo/delete', [csrf_token() => csrf_hash()])->assertRedirectTo('/admin/site');
        $this->assertSame('', (new SiteContent())->all()['logo_url']);
    }
}
