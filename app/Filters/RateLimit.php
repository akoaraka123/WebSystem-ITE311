<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RateLimit implements FilterInterface
{
    /**
     * Rate limiting filter to prevent brute force attacks
     * Limits login attempts per IP address
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Only apply to login POST requests
        if ($request->getMethod() === 'POST' && uri_string() === 'login') {
            $session = \Config\Services::session();
            $ipAddress = $request->getIPAddress();
            $cache = \Config\Services::cache();
            $lockoutKey = 'login_lockout_' . md5($ipAddress);
            
            // Check if currently locked out
            $lockoutUntil = $cache->get($lockoutKey);
            if ($lockoutUntil !== null && $lockoutUntil > time()) {
                $remaining = $lockoutUntil - time();
                $minutes = ceil($remaining / 60);
                $session->setFlashdata('error', "Too many login attempts. Please try again in {$minutes} minute(s).");
                return redirect()->to(base_url('login'))->withInput();
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
