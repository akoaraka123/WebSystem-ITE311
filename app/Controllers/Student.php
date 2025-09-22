<?php

namespace App\Controllers;

class Student extends BaseController
{
    public function dashboard()
    {
        $role = session()->get('role');

        if ($role != 'student') {
            return redirect()->to('/auth/login'); 
        }

        echo "<h2>Student Dashboard</h2>";
        echo "<p>Welcome, Student!</p>";
    }
}
