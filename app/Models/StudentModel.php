<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table      = 'students';
    protected $primaryKey = 'id';

    // Aapke bataye gaye fields ko line-up kar diya hai
    protected $allowedFields = [
        'center_id',
        'enrollment_no',
        'student_name',
        'relation_type', // <--- Naya field (Father/Husband)
        'father_name',
        'phone',
        'guardian_phone',
        'dob',
        'email',
        'address',
        'image',         // <--- Controller ke 'image' se match karne ke liye
        'course_status',
        'created_at'
    ];

    protected $useTimestamps = false; 

    // Validation rules jo aapne diye hain
    protected $validationRules = [
        'enrollment_no' => 'required',
        'student_name'  => 'required',
        'email'         => 'required|valid_email',
        'dob'           => 'required', 
    ];

    // Error messages (Optional: agar aap custom message dikhana chahte ho)
    protected $validationMessages = [
        'email' => [
            'valid_email' => 'Bhai, sahi email address daalo!',
        ],
    ];
}