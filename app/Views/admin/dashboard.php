<?php
// Ensure $employees, $attendance, $leaves, $departments variables are passed from Admin controller
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial,sans-serif; padding:20px; background:#f7f7f7; }
        table { border-collapse: collapse; width:100%; margin-bottom:20px; }
        th, td { border:1px solid #ddd; padding:8px; text-align:left; }
        th { background:#4CAF50; color:white; }
        h2,h3 { margin-top:30px; }
        input, select, button { padding:6px; margin:5px 0; }
        .reason-field { width:200px; }
        form.leave-form { display:flex; align-items:center; gap:5px; }
        .success{color:green;}
        .error{color:red;}

        /* Delete button */
        button.delete-btn {
            background: red;
            color: white;
            border: none;
            padding:5px 10px;
            cursor:pointer;
            border-radius:4px;
            margin-left:5px;
        }

        /* Modal CSS */
        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; }
        .modal-content { background:#fff; max-width:500px; width:90%; margin:80px auto; padding:30px; border-radius:5px; position:relative; box-shadow:0 5px 15px rgba(0,0,0,0.3);}
        .close-btn { position:absolute; top:5px; right:10px; cursor:pointer; font-size:18px; color:#555; }

        /* Modal form inputs */
        .modal-content input, .modal-content select, .modal-content button {
            width:100%;
            margin-bottom:15px;
        }
        .modal-content button {
            background:#4CAF50;
            color:#fff;
            border:none;
            padding:10px;
            cursor:pointer;
            border-radius:4px;
        }
        .modal-content button:hover{ opacity:0.9; }
    </style>
</head>
<body>

<h2>Admin Dashboard</h2>
<a href="<?= base_url('logout') ?>">Logout</a>
<hr>

<?php if(session()->getFlashdata('success')): ?>
    <p class="success"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
    <p class="error"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<!-- ================= Employees ================= -->
<h3>Employees</h3>
<button onclick="openAddEmployee()">Add Employee</button>
<table>
<tr>
    <th>ID</th>
    <th>User ID</th>
    <th>Name</th>
    <th>Department</th>
    <th>Designation</th>
    <th>Salary</th>
    <th>Action</th>
</tr>
<?php if(!empty($employees)): ?>
<?php foreach($employees as $emp): ?>
<tr>
    <td><?= esc($emp['id']) ?></td>
    <td><?= esc($emp['user_id']) ?></td>
    <td><?= esc($emp['user_name']) ?></td>
    <td><?= esc($emp['department_name'] ?? '-') ?></td>
    <td><?= esc($emp['designation'] ?? '-') ?></td>
    <td><?= esc($emp['salary'] ?? '-') ?></td>
    <td>
        <button onclick="openEditEmployee(<?= $emp['id'] ?>,<?= $emp['user_id'] ?>,'<?= esc($emp['user_name'],'js') ?>',<?= $emp['department_id'] ?? 0 ?>,'<?= esc($emp['designation'],'js') ?>',<?= $emp['salary'] ?? 0 ?>)">Edit</button>
        <form method="post" action="<?= base_url('admin/deleteEmployee/'.$emp['id']) ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this employee?');">
            <button type="submit" class="delete-btn">Delete</button>
        </form>
    </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="7">No Employees Found</td></tr>
<?php endif; ?>
</table>

<!-- ================= Attendance ================= -->
<h3>Attendance</h3>
<button onclick="openAddAttendance()">Add Attendance</button>
<table>
<tr>
    <th>ID</th>
    <th>Employee</th>
    <th>Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>
<?php if(!empty($attendance)): ?>
<?php foreach($attendance as $att): ?>
<tr>
    <td><?= esc($att['id']) ?></td>
    <td><?= esc($att['user_name'] ?? '-') ?></td>
    <td><?= esc($att['date'] ?? '-') ?></td>
    <td><?= esc($att['status'] ?? '-') ?></td>
    <td>
        <button onclick="openEditAttendance(<?= $att['id'] ?>,<?= $att['user_id'] ?>,'<?= $att['date'] ?>','<?= $att['status'] ?>')">Edit</button>
    </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5">No Attendance Found</td></tr>
<?php endif; ?>
</table>

<!-- ================= Leaves ================= -->
<h3>Leaves</h3>
<table>
<tr>
    <th>ID</th>
    <th>Employee</th>
    <th>Reason</th>
    <th>Status</th>
    <th>Action</th>
</tr>
<?php if(!empty($leaves)): foreach($leaves as $lv): ?>
<tr>
    <td><?= esc($lv['id']) ?></td>
    <td><?= esc($lv['employee_name']) ?></td>
    <td><?= esc($lv['reason'] ?? '-') ?></td>
    <td><?= esc($lv['status'] ?? '-') ?></td>
    <td>
        <?php if($lv['status']=='Pending'): ?>
        <form method="post" action="<?= base_url('admin/decision/'.$lv['id']) ?>" class="leave-form">
            <select name="decision" class="decision-select" onchange="this.form.submit()">
                <option value="">Select</option>
                <option value="Approved">Approve</option>
                <option value="Rejected">Reject</option>
            </select>
            <input type="text" name="admin_reason" class="reason-field" placeholder="Reason if rejected">
        </form>
        <?php else: ?>
            <?= esc($lv['status']) ?>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5">No Leaves Found</td></tr>
<?php endif; ?>
</table>

<!-- ================= Departments ================= -->
<h3>Departments</h3>
<button onclick="openDeptModal()">Add Department</button>
<table>
<tr>
    <th>ID</th>
    <th>Name</th>
</tr>
<?php if(!empty($departments)): foreach($departments as $d): ?>
<tr>
    <td><?= esc($d['id']) ?></td>
    <td><?= esc($d['dept_name']) ?></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="2">No Departments Found</td></tr>
<?php endif; ?>
</table>

<!-- ================= Employee Modal ================= -->
<div id="empModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('empModal')">&times;</span>
        <h3 id="empModalTitle">Add Employee</h3>
        <form method="post" action="<?= base_url('admin/saveEmployee') ?>">
            <input type="hidden" name="id" id="emp_id">

            <label>User ID:</label>
            <input type="number" name="user_id" id="user_id" required>

            <label>Name:</label>
            <input type="text" name="user_name" id="user_name" required>

            <label>Department:</label>
            <select name="department_id" id="department_id" required>
                <option value="">Select</option>
                <?php foreach($departments as $dept): ?>
                <option value="<?= $dept['id'] ?>"><?= esc($dept['dept_name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Designation:</label>
            <input type="text" name="designation" id="designation" required>

            <label>Salary:</label>
            <input type="number" name="salary" id="salary" required>

            <button type="submit">Save</button>
        </form>
    </div>
</div>

<!-- ================= Attendance Modal ================= -->
<div id="attModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('attModal')">&times;</span>
        <h3 id="attModalTitle">Add Attendance</h3>
        <form method="post" action="<?= base_url('admin/saveAttendance') ?>">
            <input type="hidden" name="id" id="att_id">
            <label>User ID:</label>
            <input type="number" name="user_id" id="att_user_id" required>
            <label>Date:</label>
            <input type="date" name="date" id="att_date" required>
            <label>Status:</label>
            <select name="status" id="att_status" required>
                <option value="">Select</option>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Leave">Leave</option>
            </select>
            <button type="submit">Save</button>
        </form>
    </div>
</div>

<!-- ================= Department Modal ================= -->
<div id="deptModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('deptModal')">&times;</span>
        <h3>Add Department</h3>
        <form method="post" action="<?= base_url('admin/saveDepartment') ?>">
            <input type="text" name="dept_name" placeholder="Department Name" required>
            <button type="submit">Save</button>
        </form>
    </div>
</div>

<script>
// Employee Modal
function openAddEmployee(){
    document.getElementById('empModalTitle').innerText='Add Employee';
    document.getElementById('emp_id').value='';
    document.getElementById('user_id').value='';
    document.getElementById('user_name').value='';
    document.getElementById('department_id').value='';
    document.getElementById('designation').value='';
    document.getElementById('salary').value='';
    document.getElementById('empModal').style.display='block';
}
function openEditEmployee(id,user_id,user_name,dept_id,designation,salary){
    document.getElementById('empModalTitle').innerText='Edit Employee';
    document.getElementById('emp_id').value=id;
    document.getElementById('user_id').value=user_id;
    document.getElementById('user_name').value=user_name;
    document.getElementById('department_id').value=dept_id;
    document.getElementById('designation').value=designation;
    document.getElementById('salary').value=salary;
    document.getElementById('empModal').style.display='block';
}
// Attendance Modal
function openAddAttendance(){
    document.getElementById('attModalTitle').innerText='Add Attendance';
    document.getElementById('att_id').value='';
    document.getElementById('att_user_id').value='';
    document.getElementById('att_date').value='';
    document.getElementById('att_status').value='';
    document.getElementById('attModal').style.display='block';
}
function openEditAttendance(id,user_id,date,status){
    document.getElementById('attModalTitle').innerText='Edit Attendance';
    document.getElementById('att_id').value=id;
    document.getElementById('att_user_id').value=user_id;
    document.getElementById('att_date').value=date;
    document.getElementById('att_status').value=status;
    document.getElementById('attModal').style.display='block';
}
function openDeptModal(){ document.getElementById('deptModal').style.display='block'; }
function closeModal(modalId){ document.getElementById(modalId).style.display='none'; }
</script>

</body>
</html>
