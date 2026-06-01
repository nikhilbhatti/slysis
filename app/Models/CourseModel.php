<?php

namespace App\Models;
use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table          = 'courses';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'course_name', 
        'center_id', 
        'fee', 
        'duration', // 👈 'course_duration' ki jagah sirf 'duration' rakhein taaki controller match kare
        'status', 
        'created_at'
    ];
}