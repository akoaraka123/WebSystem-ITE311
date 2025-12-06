<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SessionTimeout implements FilterInterface
{
    /**
     * Session timeout filter
     * Automatically logs out users after period of inactivity
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        // Only check for logged in users
        if ($session->get('isLoggedIn')) {
            $timeout = 7200; // 2 hours in seconds
            $lastActivity = $session->get('last_activity');
            
            if ($lastActivity !== null && (time() - $lastActivity) > $timeout) {
                // Session expired
                $session->destroy();
                $session->setFlashdata('error', 'Your session has expired. Please login again.');
                return redirect()->to(base_url('login'));
            }
            
            // Update last activity time
            $session->set('last_activity', time());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
