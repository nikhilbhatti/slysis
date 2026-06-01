<!DOCTYPE html>
<html>
<head>
    <title>Employee Registration</title>
    <style>
        body { font-family: Arial; background:#eef2f5; }
        .container { width:600px; margin:50px auto; background:#fff; padding:20px; border-radius:6px; box-shadow:0 0 12px rgba(0,0,0,0.1); }
        h3 { text-align:center; margin-bottom:20px; }
        label { font-weight:bold; margin-top:10px; display:block; }
        input, select, button { width:100%; padding:10px; margin:6px 0 12px; border-radius:4px; border:1px solid #ccc; }
        button { background:#007bff; color:#fff; border:none; cursor:pointer; }
        button:hover { background:#0056b3; }
        .success { color:green; text-align:center; }
        .error { color:red; text-align:center; }
    </style>
</head>
<body>

<div class="container">
    <h3>Employee Registration</h3>

    <?php if(session()->getFlashdata('success')): ?>
        <p class="success"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <p class="error"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form method="post" action="<?= base_url('employee/register') ?>">

        <!-- Employee Info -->
        <label>Name</label>
        <input type="text" name="emp_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Role</label>
        <select name="role" required>
            <option value="employee">Employee</option>
            <option value="admin">Admin</option>
        </select>

        <label>Department</label>
        <select name="department_id" required>
            <option value="">-- Select Department --</option>
            <?php foreach($departments as $dept): ?>
                <option value="<?= $dept['id'] ?>"><?= $dept['dept_name'] ?></option>
            <?php endforeach; ?>
        </select>

        <label>Designation</label>
        <select name="designation" required>
            <option value="">-- Select Designation --</option>
            <option value="Junior">Junior</option>
            <option value="Senior">Senior</option>
            <option value="Team Lead">Team Lead</option>
            <option value="Manager">Manager</option>
        </select>

        <label>Salary</label>
        <input type="number" name="salary" required>

        <!-- ===== Leave Fields ===== -->
        <h3>Add Leave</h3>
        <label>Leave Type</label>
        <input type="text" name="leave_type" placeholder="Sick / Annual / Casual">

        <label>Start Date</label>
        <input type="date" name="leave_start">

        <label>End Date</label>
        <input type="date" name="leave_end">

        <!-- ===== Attendance Fields ===== -->
        <h3>Add Attendance</h3>
        <label>Date</label>
        <input type="date" name="attendance_date">

        <label>Status</label>
        <select name="attendance_status">
            <option value="">-- Select Status --</option>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
            <option value="Leave">Leave</option>
        </select>

        <button type="submit">Register</button>
    </form>
</div>

</body>
</html>
