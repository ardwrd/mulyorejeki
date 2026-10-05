<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->has('admin_user_id')) {
            return null;
        }

        session()->set('admin_intended_path', $request->getUri()->getPath());

        return redirect()
            ->to(site_url('admin/login'))
            ->with('error', 'Silakan login untuk membuka panel admin.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
