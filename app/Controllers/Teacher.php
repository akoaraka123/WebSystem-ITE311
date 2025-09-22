<?php

namespace App\Controllers;

class Teacher extends BaseController
{
    public function dashboard()
    {
        $role = session()->get('role');

        if ($role != 'teacher') {
            return redirect()->to('/auth/login'); 
        }

        echo "<h2>Teacher Dashboard</h2>";
        echo "<p>Welcome, Teacher!</p>";
    }
}
