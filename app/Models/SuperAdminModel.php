<?php

namespace App\Models;

use CodeIgniter\Model;

class SuperAdminModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'role', 'status', 'created_at'];

    // ❌ Disable timestamps to prevent updated_at error
    protected $useTimestamps = false;

    public function getSuperAdmin($id)
    {
        return $this->where('id', $id)
                    ->where('role', 'super_admin')
                    ->first();
    }
}
