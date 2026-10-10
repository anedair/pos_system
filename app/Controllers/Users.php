<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Users extends BaseController
{
    private const AVATAR_UPLOAD_DIR = 'uploads/avatars';
    private const USERS_ROUTE = '/users';
    private const MAX_AVATAR_SIZE_KB = 2048;

    protected UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    // Display all staff members.
    public function index(): string
    {
        return view('users/index', [
            'users' => $this->users
                ->orderBy('id', 'DESC')
                ->findAll(),
        ]);
    }

    // Display the Add Staff form.
    public function create(): string
    {
        return view('users/create');
    }

    // Shared validation rules for avatar uploads.
    private function avatarRules(): string
    {
        return 'permit_empty|is_image[avatar]'
            . '|mime_in[avatar,image/jpeg,image/png,image/webp]'
            . '|max_size[avatar,' . self::MAX_AVATAR_SIZE_KB . ']';
    }

    // Build an avatar's file path safely.
    private function avatarPath(string $filename): string
    {
        return FCPATH . self::AVATAR_UPLOAD_DIR
            . DIRECTORY_SEPARATOR . basename($filename);
    }

    // Upload and resize an optional avatar.
    private function uploadAvatar(): ?string
    {
        $file = $this->request->getFile('avatar');

        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        // File validation must run before this method is called.
        if (!$file->isValid() || $file->hasMoved()) {
            return null;
        }

        $uploadPath = FCPATH . self::AVATAR_UPLOAD_DIR;

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Never trust the original client filename.
        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        $fullPath = $this->avatarPath($newName);

        // Follow the laboratory manual: crop to 300 x 300.
        service('image')
            ->withFile($fullPath)
            ->fit(300, 300, 'center')
            ->save($fullPath);

        return $newName;
    }

    // Create a new staff account.
    public function store(): RedirectResponse
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'full_name' => 'required|min_length[2]|max_length[150]',
            'password' => 'required|min_length[8]|max_length[255]',
            'avatar' => $this->avatarRules(),
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $avatar = $this->uploadAvatar();

        $data = [
            'username' => trim($this->request->getPost('username')),
            'full_name' => trim($this->request->getPost('full_name')),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'avatar' => $avatar,
        ];

        if (!$this->users->insert($data)) {
            if ($avatar !== null) {
                @unlink($this->avatarPath($avatar));
            }

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->users->errors());
        }

        return redirect()->to(self::USERS_ROUTE)
            ->with('success', 'Staff account created successfully.');
    }

    // Display the Edit Staff form.
    public function edit(int $id): string
    {
        $user = $this->users->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('users/edit', [
            'user' => $user,
        ]);
    }

    // Update an existing staff account.
    public function update(int $id): RedirectResponse
    {
        $user = $this->users->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Exclude the current user when checking username uniqueness.
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,' . $id . ']',
            'full_name' => 'required|min_length[2]|max_length[150]',
            'password' => 'permit_empty|min_length[8]|max_length[255]',
            'avatar' => $this->avatarRules(),
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => trim($this->request->getPost('username')),
            'full_name' => trim($this->request->getPost('full_name')),
        ];

        $newPassword = $this->request->getPost('password');

        // An empty password means keep the current password.
        if ($newPassword !== null && $newPassword !== '') {
            $data['password'] = password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            );
        }

        $avatar = $this->uploadAvatar();

        if ($avatar !== null) {
            $data['avatar'] = $avatar;
        }

        if (!$this->users->update($id, $data)) {
            if ($avatar !== null) {
                @unlink($this->avatarPath($avatar));
            }

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->users->errors());
        }

        // Remove the previous image only after the update succeeds.
        if ($avatar !== null && !empty($user['avatar'])) {
            $oldPath = $this->avatarPath($user['avatar']);

            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return redirect()->to(self::USERS_ROUTE)
            ->with('success', 'Staff account updated successfully.');
    }

    // Delete a staff member only when no sales reference them.
    public function delete(int $id): RedirectResponse
    {
        $user = $this->users->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound();
        }

        $salesCount = db_connect()
            ->table('sales')
            ->where('user_id', $id)
            ->countAllResults();

        if ($salesCount > 0) {
            return redirect()->to(self::USERS_ROUTE)
                ->with(
                    'error',
                    'This staff member has sales records and cannot be deleted.'
                );
        }

        $this->users->delete($id);

        if (!empty($user['avatar'])) {
            $oldPath = $this->avatarPath($user['avatar']);

            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return redirect()->to(self::USERS_ROUTE)
            ->with('success', 'Staff account deleted successfully.');
    }
}
