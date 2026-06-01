<?php
namespace App\Models;
use CodeIgniter\Model;

class LeaveModel extends Model
{
    protected $table = 'leaves';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id','type','start_date','end_date','status','reason'
    ];
}
