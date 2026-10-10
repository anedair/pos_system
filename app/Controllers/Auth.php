<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // Redirect authenticated users away from the login page.
        if (session()->get('user_id')) {
            return redirect()->to(site_url('products'));
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required|max_length[100]',
            'password' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid username or password.');
        }

        // Regenerate the session ID after successful authentication.
        session()->regenerate(true);

        session()->set([
            'user_id'   => (int) $user['id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'avatar'    => $user['avatar'] ?? null,
        ]);

        return redirect()->to(site_url('products'))
            ->with('success', 'Welcome back, ' . $user['full_name'] . '!');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'));
    }
}
