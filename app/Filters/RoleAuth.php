<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Check role requirement
        if ($arguments && isset($arguments[0])) {
            $requiredRole = $arguments[0];
            $userRole = $session->get('role');

            // If user's role doesn't match required role
            if ($userRole !== $requiredRole) {
                return redirect()->to('/announcements')
                                 ->with('error', 'Access Denied: Insufficient Permissions');
            }
        }

        return null; // allow access
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing here
    }
}
