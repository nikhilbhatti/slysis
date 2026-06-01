<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CenterModel;

class CenterAuth extends BaseController
{
    // ================= LOGIN PAGE =================
    public function login()
    {
        // Agar pehle se login hai toh dashboard bhej dein
        if (session()->get('logged_in')) {
            return (session()->get('role') === 'super_admin') ? 
                    redirect()->to('/superadmin/dashboard') : 
                    redirect()->to('/center/dashboard');
        }

        // --- LETTER CAPTCHA LOGIC ---
        // Humne confusing characters (0, O, I, 1) hata diye hain
        $permitted_chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captcha_str = substr(str_shuffle($permitted_chars), 0, 6);
        
        // Sahi string session mein save karein
        session()->set('captcha_result', $captcha_str);
        
        // View mein captcha text bhej rahe hain
        $data['captcha_text'] = $captcha_str;
        
        return view('center/login', $data);
    }

    // ================= LOGIN PROCESS =================
    public function loginProcess()
    {
        // 1. Sabse pehle Letter Captcha check karein
        // strtoupper isliye taaki agar user small letters likhe toh bhi match ho jaye
        $userAnswer = strtoupper($this->request->getPost('captcha_answer'));
        $realAnswer = session()->get('captcha_result');

        if (!$userAnswer || $userAnswer !== $realAnswer) {
            return redirect()->back()->with('error', 'Security code did not match! Please try again.')->withInput();
        }

        // 2. Email aur Password lein
        $email    = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');

        if (!$email || !$password) {
            return redirect()->back()->with('error', 'Email and Password are required')->withInput();
        }

        /* STEP 1: Super Admin Check */
        $userModel = new UserModel();
        $superAdmin = $userModel->where([
            'email'  => $email,
            'role'   => 'super_admin',
            'status' => 1
        ])->first();

        if ($superAdmin && password_verify($password, $superAdmin['password'])) {
            session()->set([
                'superadmin_id' => $superAdmin['id'],
                'user_id'       => $superAdmin['id'],
                'role'          => 'super_admin',
                'logged_in'     => true
            ]);
            return redirect()->to('/superadmin/dashboard');
        }

        /* STEP 2: Center Check */
        $centerModel = new CenterModel();
        $center = $centerModel->where([
            'email'  => $email,
            'status' => 1
        ])->first();

        if ($center && password_verify($password, $center['password'])) {
            session()->set([
                'user_id'     => $center['id'],
                'center_id'   => $center['id'],
                'center_code' => $center['center_code'],
                'role'        => 'center',
                'logged_in'   => true
            ]);
            return redirect()->to('/center/dashboard');
        }

        // ❌ Match nahi mila
        return redirect()->back()->with('error', 'Invalid email or password')->withInput();
    }

    // ================= LOGOUT =================
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}