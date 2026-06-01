<?php
namespace App\Controllers;

class StudentDashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('student_login'))
        {
            return redirect()->to('student');
        }

        return view('student/dashboard');
    }
}
