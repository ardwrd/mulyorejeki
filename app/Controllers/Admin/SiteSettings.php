<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ObjectStorage;
use App\Libraries\SiteContent;
use CodeIgniter\HTTP\Files\UploadedFile;
use Throwable;

class SiteSettings extends BaseController
{
    private const TEXT_FIELDS = [
        'hero_title' => 160,
        'hero_text' => 500,
        'categories_title' => 120,
        'products_title' => 120,
        'brands_title' => 120,
        'brands_text' => 500,
        'about_title' => 160,
        'about_text' => 1000,
        'contact_title' => 160,
        'contact_text' => 500,
        'catalog_text' => 500,
        'footer_text' => 500,
        'phone' => 30,
        'address' => 500,
        'weekday_hours' => 120,
        'saturday_hours' => 120,
        'sunday_hours' => 120,
    ];

    public function index(): string
    {
        return view('admin/site/index', [
            'title' => 'Pengaturan Situs — Admin Mulyorejeki',
            'site' => (new SiteContent())->all(),
        ]);
    }

    public function update()
    {
        $rules = [];
        foreach (self::TEXT_FIELDS as $key => $length) {
            $rules[$key] = 'required|max_length[' . $length . ']';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $content = [];
        foreach (self::TEXT_FIELDS as $key => $length) {
            $content[$key] = trim((string) $this->request->getPost($key));
        }

        $number = preg_replace('/\D+/', '', $content['phone']);
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        if (! preg_match('/^[1-9][0-9]{8,14}$/', $number)) {
            return redirect()->back()->withInput()->with('errors', ['phone' => 'Nomor WhatsApp tidak valid. Gunakan nomor Indonesia, misalnya 0812-2802-283.']);
        }

        $file = $this->request->getFile('logo');
        $hasLogo = $file instanceof UploadedFile && $file->getError() !== UPLOAD_ERR_NO_FILE;
        if ($hasLogo && ! $this->validate([
            'logo' => [
                'label' => 'Logo toko',
                'rules' => 'uploaded[logo]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png,image/webp]|max_size[logo,2048]',
            ],
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $settings = new SiteContent();
        $previous = $settings->all();
        $storage = new ObjectStorage();
        $stored = null;

        try {
            if ($hasLogo) {
                $stored = $storage->storeSiteLogo($file);
                $content['logo_key'] = $stored['key'];
                $content['logo_url'] = $stored['url'];
            }
            $settings->save($content);
        } catch (Throwable $error) {
            if ($stored !== null) {
                try {
                    $storage->delete($stored['key']);
                } catch (Throwable $cleanupError) {
                    log_message('warning', 'Unable to clean up new site logo: {message}', ['message' => $cleanupError->getMessage()]);
                }
            }
            log_message('error', 'Site settings update failed: {message}', ['message' => $error->getMessage()]);

            return redirect()->back()->withInput()->with('error', 'Perubahan belum tersimpan. Coba lagi beberapa saat.');
        }

        if ($stored !== null && $previous['logo_key'] !== '') {
            $this->deleteOldLogo($storage, $previous['logo_key']);
        }

        return redirect()->to(site_url('admin/site'))->with('success', 'Pengaturan situs berhasil disimpan.');
    }

    public function removeLogo()
    {
        $settings = new SiteContent();
        $previous = $settings->all();
        $settings->save(['logo_key' => '', 'logo_url' => '']);
        if ($previous['logo_key'] !== '') {
            $this->deleteOldLogo(new ObjectStorage(), $previous['logo_key']);
        }

        return redirect()->to(site_url('admin/site'))->with('success', 'Logo dihapus. Tanda MR kembali ditampilkan.');
    }

    private function deleteOldLogo(ObjectStorage $storage, string $key): void
    {
        try {
            $storage->delete($key);
        } catch (Throwable $error) {
            log_message('warning', 'Unable to delete old site logo: {message}', ['message' => $error->getMessage()]);
        }
    }
}
