<?php

namespace App\Models;

use CodeIgniter\Model;

class SessionModel extends Model
{
    // Database me table ka naam
    protected $table = 'sessions';

    // Primary key
    protected $primaryKey = 'id';

    // Jo fields insert/update karni hai
    protected $allowedFields = [
        'user_id',
        'session_data',
        'created_at',
        'updated_at'
    ];

    // Agar timestamps chahiye
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
