<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Storage extends BaseConfig
{
    public string $driver = 'local';
    public string $localRoot = '';

    public string $r2Endpoint = '';
    public string $r2Bucket = '';
    public string $r2AccessKey = '';
    public string $r2SecretKey = '';
    public string $r2Region = 'auto';
    public string $r2PublicBaseUrl = '';

    public function __construct()
    {
        parent::__construct();

        $this->driver = strtolower((string) env('storage.driver', 'local'));
        $this->localRoot = (string) env('storage.localRoot', FCPATH . 'uploads');

        $this->r2Endpoint = rtrim((string) env('storage.r2.endpoint', ''), '/');
        $this->r2Bucket = (string) env('storage.r2.bucket', '');
        $this->r2AccessKey = (string) env('storage.r2.accessKey', '');
        $this->r2SecretKey = (string) env('storage.r2.secretKey', '');
        $this->r2Region = (string) env('storage.r2.region', 'auto');
        $this->r2PublicBaseUrl = rtrim((string) env('storage.r2.publicBaseUrl', ''), '/');
    }
}
