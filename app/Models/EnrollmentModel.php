<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table      = 'enrollments';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'student_id', 
        'course_id', 
        'center_id', 
        'enroll_date', 
        'expiry_date', 
        'status', 
        'duration',    
        'fee'          
    ];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get Full Enrollment Data with Joins
     */
    public function getFullEnrollmentData($centerId = null, $status = null)
    {
        $builder = $this->db->table($this->table . ' e');
        
        $builder->select('e.*, s.student_name, s.phone, s.enrollment_no as enroll_no, s.course_status, c.course_name, ctr.center_name');
        $builder->join('students s', 's.id = e.student_id', 'left');
        $builder->join('courses c', 'c.id = e.course_id', 'left');
        $builder->join('centers ctr', 'ctr.id = e.center_id', 'left');

        if ($centerId) {
            $builder->where('e.center_id', $centerId);
        }
        
        if ($status) {
            $builder->where('s.course_status', $status);
        }

        $builder->orderBy('e.id', 'DESC');

        return $builder->get()->getResultArray();
    }
}