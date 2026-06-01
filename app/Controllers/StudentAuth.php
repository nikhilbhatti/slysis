<?php
namespace App\Controllers;

class StudentAuth extends BaseController
{
    public function login()
    {
        return view('student/login');
    }

    public function check()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // demo login
        if ($username === 'student' && $password === '123')
        {
            session()->set('student_login', true);
            return redirect()->to('student/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid Login');
    }

    public function logout()
    {
        session()->remove('student_login');
        return redirect()->to('student');
    }
}
