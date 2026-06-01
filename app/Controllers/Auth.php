<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        // Login page show karne ke liye
        return view('login');
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validation
        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Both email and password are required.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Check user exists & password correct
        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid Email or Password');
        }

        // Set session
        session()->set([
            'user_id' => $user['id'],
            'role'    => $user['role'],
            'name'    => $user['name'],
            'logged_in' => true
        ]);

        // Redirect based on role
        if ($user['role'] === 'admin') {
            return redirect()->to('/admin/dashboard');
        } elseif ($user['role'] === 'employee') {
            return redirect()->to('/employee/dashboard');
        } else {
            // Default fallback
            session()->destroy();
            return redirect()->to('/')->with('error', 'Unauthorized role.');
        }
    }

    public function logout()
    {
        // Destroy all session data
        session()->destroy();
        return redirect()->to('/')->with('success', 'You have been logged out successfully.');
    }
}
