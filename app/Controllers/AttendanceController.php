<?php
namespace App\Controllers;
use App\Models\AttendanceModel;

class AttendanceController extends BaseController
{
    public function add()
    {
        return view('attendance/add');
    }

    public function save()
    {
        $uid = session()->get('user_id'); 
        $attendanceModel = new AttendanceModel();

        $data = [
            'user_id' => $uid,
            'date' => $this->request->getPost('date'),
            'status' => $this->request->getPost('status')
        ];

        $attendanceModel->insert($data);
        return redirect()->to('/employee/dashboard')->with('success','Attendance added');
    }
}
