<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;
use App\Models\UserModel;
use App\Models\DepartmentModel;
use App\Models\AttendanceModel;
use App\Models\LeaveModel;

class Admin extends BaseController
{
    public function dashboard()
    {
        if(session()->get('role') != 'admin')
            return redirect()->to('/');

        $employees = (new EmployeeModel())
            ->select('employees.*, users.name as user_name, departments.dept_name as department_name')
            ->join('users','users.id=employees.user_id','left')
            ->join('departments','departments.id=employees.department_id','left')
            ->findAll();

        $attendance = (new AttendanceModel())
            ->select('attendance.*, users.name as user_name')
            ->join('users','users.id=attendance.user_id','left')
            ->findAll();

        $leaves = (new LeaveModel())
            ->select('leaves.*, users.name as employee_name')
            ->join('users','users.id=leaves.user_id','left')
            ->findAll();

        $departments = (new DepartmentModel())->findAll();

        return view('admin/dashboard', compact(
            'employees','attendance','leaves','departments'
        ));
    }

    public function saveEmployee()
    {
        $empModel = new EmployeeModel();
        $userModel = new UserModel();

        $id = $this->request->getPost('id');
        $user_id = $this->request->getPost('user_id');

        $userModel->update($user_id, [
            'name' => $this->request->getPost('user_name')
        ]);

        $data = [
            'user_id' => $user_id,
            'department_id' => $this->request->getPost('department_id'),
            'designation' => $this->request->getPost('designation'),
            'salary' => $this->request->getPost('salary')
        ];

        $id ? $empModel->update($id,$data) : $empModel->insert($data);

        return redirect()->to('admin/dashboard');
    }

    public function deleteEmployee($id)
    {
        (new EmployeeModel())->delete($id);
        return redirect()->back();
    }

    public function saveDepartment()
    {
        (new DepartmentModel())->insert([
            'dept_name' => $this->request->getPost('dept_name')
        ]);
        return redirect()->back();
    }

    public function saveAttendance()
    {
        $model = new AttendanceModel();
        $data = $this->request->getPost();
        isset($data['id']) && $data['id']
            ? $model->update($data['id'],$data)
            : $model->insert($data);

        return redirect()->back();
    }

    public function decision($id)
    {
        $decision = $this->request->getPost('decision');
        $reason = trim($this->request->getPost('admin_reason'));

        if($decision=='Rejected' && $reason==''){
            return redirect()->back()->with('error','Reason required');
        }

        (new LeaveModel())->update($id,[
            'status'=>$decision,
            'reason'=>$decision=='Rejected'?$reason:null
        ]);

        return redirect()->back();
    }
}
