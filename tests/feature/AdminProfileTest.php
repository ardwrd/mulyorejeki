<?php

use App\Database\Seeds\CatalogSeeder;
use App\Models\AdminUserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class AdminProfileTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;
    protected $seed = CatalogSeeder::class;

    public function testProfileRequiresAuthentication(): void
    {
        $this->get('/admin/profile')->assertRedirectTo('/admin/login');
    }

    public function testAdminCanUpdateProfileAndPassword(): void
    {
        $model = new AdminUserModel();
        $id = $model->insert([
            'name' => 'Admin Uji',
            'email' => 'admin-uji@example.test',
            'password_hash' => password_hash('PasswordLama123!', PASSWORD_DEFAULT),
            'is_active' => 1,
        ]);
        $this->withSession(['admin_user_id' => $id, 'admin_user_name' => 'Admin Uji']);

        $this->get('/admin/profile')->assertSee('Informasi akun');
        $this->post('/admin/profile', [
            'name' => 'Admin Baru',
            'email' => 'admin-baru@example.test',
            csrf_token() => csrf_hash(),
        ])->assertRedirectTo('/admin/profile');
        $this->assertSame('admin-baru@example.test', (new AdminUserModel())->find($id)['email']);

        $this->post('/admin/profile/password', [
            'current_password' => 'PasswordLama123!',
            'new_password' => 'PasswordBaru123!',
            'password_confirm' => 'PasswordBaru123!',
            csrf_token() => csrf_hash(),
        ])->assertRedirectTo('/admin/profile');
        $this->assertTrue(password_verify('PasswordBaru123!', (new AdminUserModel())->find($id)['password_hash']));
    }
}
