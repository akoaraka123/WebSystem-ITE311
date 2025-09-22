<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function dashboard()
    {
        $role = session()->get('role');

        if ($role != 'admin') {
            return redirect()->to('/auth/login');
        }

        echo "<h2>Admin Dashboard</h2>";
        echo "<p>Welcome, Admin!</p>";
    }
}
