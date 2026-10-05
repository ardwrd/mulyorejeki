<?php

namespace App\Libraries;

use Aws\S3\S3Client;
use CodeIgniter\HTTP\Files\UploadedFile;
use Config\Storage;
use RuntimeException;

final class ObjectStorage
{
    private Storage $config;
    private ?S3Client $r2 = null;

    public function __construct(?Storage $config = null)
    {
        $this->config = $config ?? config('Storage');
    }

    /**
     * @return array{key: string, url: string}
     */
    public function storeProductImage(UploadedFile $file): array
    {
        if (! $file->isValid()) {
            throw new RuntimeException($file->getErrorString() ?: 'File upload tidak valid.');
        }

        $extension = strtolower((string) $file->getExtension());
        if ($extension === '') {
            $extension = 'bin';
        }

        $key = sprintf(
            'products/%s/%s.%s',
            date('Y/m'),
            bin2hex(random_bytes(16)),
            $extension
        );

        if ($this->config->driver === 'r2') {
            return $this->storeToR2($file, $key);
        }

        if ($this->config->driver !== 'local') {
            throw new RuntimeException('Storage driver tidak didukung: ' . $this->config->driver);
        }

        return $this->storeLocally($file, $key);
    }

    public function delete(string $key): void
    {
        if ($key === '') {
            return;
        }

        if ($this->config->driver === 'r2') {
            $this->r2Client()->deleteObject([
                'Bucket' => $this->config->r2Bucket,
                'Key' => $key,
            ]);

            return;
        }

        $path = rtrim($this->config->localRoot, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, ltrim($key, '/'));

        if (is_file($path)) {
            @unlink($path);
        }
    }

    /**
     * @return array{key: string, url: string}
     */
    private function storeLocally(UploadedFile $file, string $key): array
    {
        $directory = rtrim($this->config->localRoot, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, dirname($key));

        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new RuntimeException('Direktori upload lokal tidak dapat dibuat.');
        }

        $file->move($directory, basename($key), true);

        return [
            'key' => $key,
            'url' => rtrim(site_url('uploads'), '/') . '/' . $key,
        ];
    }

    /**
     * @return array{key: string, url: string}
     */
    private function storeToR2(UploadedFile $file, string $key): array
    {
        if ($this->config->r2PublicBaseUrl === '') {
            throw new RuntimeException('storage.r2.publicBaseUrl wajib diisi untuk menampilkan gambar R2.');
        }

        $this->r2Client()->putObject([
            'Bucket' => $this->config->r2Bucket,
            'Key' => $key,
            'SourceFile' => $file->getTempName(),
            'ContentType' => $file->getMimeType(),
            'CacheControl' => 'public, max-age=31536000, immutable',
        ]);

        return [
            'key' => $key,
            'url' => $this->config->r2PublicBaseUrl . '/' . ltrim($key, '/'),
        ];
    }

    private function r2Client(): S3Client
    {
        if ($this->r2 !== null) {
            return $this->r2;
        }

        foreach ([
            'endpoint' => $this->config->r2Endpoint,
            'bucket' => $this->config->r2Bucket,
            'accessKey' => $this->config->r2AccessKey,
            'secretKey' => $this->config->r2SecretKey,
        ] as $name => $value) {
            if ($value === '') {
                throw new RuntimeException('Konfigurasi R2 belum lengkap: ' . $name);
            }
        }

        $this->r2 = new S3Client([
            'version' => 'latest',
            'region' => $this->config->r2Region,
            'endpoint' => $this->config->r2Endpoint,
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key' => $this->config->r2AccessKey,
                'secret' => $this->config->r2SecretKey,
            ],
        ]);

        return $this->r2;
    }
}
