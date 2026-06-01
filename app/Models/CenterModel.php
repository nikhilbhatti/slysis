<?php

namespace App\Models;

use CodeIgniter\Model; // ✅ YE LINE SABSE IMPORTANT HAI

class CenterModel extends Model
{
    protected $table      = 'centers';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'center_name',
        'center_code',
        'email',
        'password',
        'address',
        'phone',
        'status'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';
    protected $protectFields = true;
}
