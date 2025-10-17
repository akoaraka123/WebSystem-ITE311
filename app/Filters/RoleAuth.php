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

        // Kung hindi naka-login, balik sa login page
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Role-based restriction
        $userRole = $session->get('role');

        if (!empty($arguments) && !in_array($userRole, $arguments)) {
            // If user's role is not allowed for this route
            return redirect()->to('/unauthorized');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing here
    }
}
