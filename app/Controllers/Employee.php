<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\EmployeeModel;
use App\Models\DepartmentModel;
use App\Models\LeaveModel;
use App\Models\AttendanceModel;

class Employee extends BaseController
{
    public function dashboard()
    {
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to('/')->with('error','Login first');

        $employee = (new EmployeeModel())
            ->select('employees.*, users.name as user_name, users.email, users.role, departments.dept_name as department_name')
            ->join('users','users.id = employees.user_id','left')
            ->join('departments','departments.id = employees.department_id','left')
            ->where('employees.user_id', $userId)
            ->first();

        return view('employee/dashboard', [
            'employee'=>$employee,
            'departments'=> (new DepartmentModel())->findAll(),
            'leaves'=> (new LeaveModel())->where('user_id',$userId)->findAll(),
            'attendance'=> (new AttendanceModel())->where('user_id',$userId)->findAll()
        ]);
    }

    public function form()
    {
        $departments = (new DepartmentModel())->findAll();
        return view('employee_form', ['departments'=>$departments]);
    }

    public function register()
    {
        $userModel = new UserModel();
        $empModel  = new EmployeeModel();

        $name = $this->request->getPost('emp_name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');
        $department = $this->request->getPost('department_id');
        $designation = $this->request->getPost('designation');
        $salary = $this->request->getPost('salary');

        if($userModel->where('email',$email)->first()){
            return redirect()->back()->with('error','Email already registered');
        }

        $userId = $userModel->insert([
            'name'=>$name,
            'email'=>$email,
            'password'=>password_hash($password,PASSWORD_DEFAULT),
            'role'=>$role
        ], true);

        $empModel->insert([
            'user_id'=>$userId,
            'department_id'=>$department,
            'designation'=>$designation,
            'salary'=>$salary
        ]);

        return redirect()->to('/')->with('success','Registered successfully. Login now.');
    }

    public function update()
    {
        $userId = session()->get('user_id');
        if(!$userId) return redirect()->to('/')->with('error','Login first');

        $userModel = new UserModel();
        $empModel = new EmployeeModel();

        $userData = [
            'name'=>$this->request->getPost('emp_name'),
            'email'=>$this->request->getPost('email'),
            'role'=>$this->request->getPost('role')
        ];

        $password = $this->request->getPost('password');
        if(!empty($password)) $userData['password'] = password_hash($password,PASSWORD_DEFAULT);

        $userModel->update($userId, $userData);

        $empData = [
            'department_id'=>$this->request->getPost('department_id'),
            'designation'=>$this->request->getPost('designation'),
            'salary'=>$this->request->getPost('salary')
        ];

        $empModel->where('user_id',$userId)->set($empData)->update();

        return redirect()->to('/employee/form')->with('success','Profile updated successfully');
    }
}
