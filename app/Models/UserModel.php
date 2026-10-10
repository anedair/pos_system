<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'username',
        'full_name',
        'password',
        'avatar',
    ];

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]',
        'full_name' => 'required|min_length[2]|max_length[150]',
    ];
}
