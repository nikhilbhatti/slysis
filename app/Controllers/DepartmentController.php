<?php
namespace App\Controllers;
use App\Models\DepartmentModel;

class DepartmentController extends BaseController {

    public function form() {
        return view('admin/add_department'); // department form
    }

    public function save() {
        $deptName = $this->request->getPost('dept_name');
        if(!$deptName){
            return redirect()->back()->with('error','Department Name Required');
        }

        $deptModel = new DepartmentModel();
        $deptModel->insert(['dept_name'=>$deptName]);

        return redirect()->to('admin/dashboard')->with('success','Department Added');
    }
}
