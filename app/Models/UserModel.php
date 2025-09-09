<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // Table name
    protected $table = 'users';

    // Primary key
    protected $primaryKey = 'id';

    // Fields allowed to be inserted/updated
    protected $allowedFields = [
        'name',
        'email',
        'password',
        'role',
        'created_at',
        'updated_at'
    ];

    // Automatically manage created_at and updated_at
    protected $useTimestamps = true;

    // Optional: Define return type as array for easier handling
    protected $returnType = 'array';

    // Optional: Password hashing before insert/update
    protected function beforeInsert(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}
