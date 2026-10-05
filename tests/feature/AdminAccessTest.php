<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class AdminAccessTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testAdminLoginPageLoads(): void
    {
        $result = $this->get('/admin/login');

        $result->assertStatus(200);
        $result->assertSee('Masuk ke admin');
    }

    public function testDashboardRequiresAuthentication(): void
    {
        $result = $this->get('/admin');

        $result->assertStatus(302);
        $result->assertRedirectTo('/admin/login');
    }
}
