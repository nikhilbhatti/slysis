<?php
namespace App\Controllers;
use App\Models\LeaveModel;

class LeaveController extends BaseController {
    public function add() {
        return view('leave_form');
    }

    public function save() {
        $leaveModel = new LeaveModel();
        $userId = session()->get('user_id');
        $leaveType = $this->request->getPost('leave_type');
        $reason = ($leaveType=='Other')?$this->request->getPost('other_reason'):$leaveType;

        $leaveModel->insert([
            'user_id'=>$userId,
            'reason'=>$reason,
            'status'=>'Pending'
        ]);

        return redirect()->to('/employee/dashboard')->with('success','Leave Applied');
    }
}

       