<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        // getPath() kabhi-kabhi leading slash deta hai, isliye trim kar rahe hain
        $uri = ltrim($request->getUri()->getPath(), '/');

        // 1. Agar user login nahi hai
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');

        // 2. SUPERADMIN Area Protection
        // Check karein ki kya URL 'superadmin' se shuru ho raha hai
        if (strpos($uri, 'superadmin') === 0) {
            // DHAYAN DEIN: Apne database wala role name yahan sahi likhein (superadmin ya super_admin)
            if ($role !== 'super_admin' && $role !== 'superadmin') {
                return redirect()->to('/login')->with('error', 'Unauthorized Admin access.');
            }
        }

        // 3. CENTER Area Protection
        // Check karein ki kya URL 'center' se shuru ho raha hai aur 'superadmin' nahi hai
        if (strpos($uri, 'center') === 0 && strpos($uri, 'superadmin') === false) {
            if ($role !== 'center') {
                return redirect()->to('/login')->with('error', 'Unauthorized Center access.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}