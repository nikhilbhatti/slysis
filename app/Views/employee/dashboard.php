<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        header {
            background: #007bff;
            color: #fff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 6px 12px;
            background: #dc3545;
            border-radius: 4px;
        }
        .container {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2, h3 {
            margin-top: 0;
            color: #333;
        }
        form {
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            padding: 10px 16px;
            background: #28a745;
            color: #fff;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.3s;
        }
        button:hover {
            background: #218838;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #c3e6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

<header>
    <h2>Welcome, <?= esc($employee['user_name']) ?></h2>
    <a href="<?= base_url('logout') ?>">Logout</a>
</header>

<div class="container">

    <?php if(session()->getFlashdata('success')): ?>
        <div class="success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <h3>Update Your Details</h3>
    <form method="post" action="<?= base_url('employee/update') ?>">
        <label>Department:</label>
        <select name="department_id" required>
            <?php foreach($departments as $d): ?>
                <option value="<?= $d['id'] ?>" <?= ($employee['department_id']==$d['id'])?'selected':'' ?>>
                    <?= esc($d['dept_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Designation:</label>
        <input type="text" name="designation" value="<?= esc($employee['designation']) ?>" required>

        <label>Salary:</label>
        <input type="number" name="salary" value="<?= esc($employee['salary']) ?>" required>

        <button type="submit">Update</button>
    </form>

    <h3>Leaves Summary</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Reason</th>
            <th>Status</th>
        </tr>
        <?php if(!empty($leaves)): ?>
            <?php foreach($leaves as $lv): ?>
            <tr>
                <td><?= esc($lv['id']) ?></td>
                <td><?= esc($lv['reason']) ?></td>
                <td><?= esc($lv['status']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">No leaves found</td></tr>
        <?php endif; ?>
    </table>

    <h3>Attendance Summary</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php if(!empty($attendance)): ?>
            <?php foreach($attendance as $att): ?>
            <tr>
                <td><?= esc($att['id']) ?></td>
                <td><?= esc($att['date']) ?></td>
                <td><?= esc($att['status']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">No attendance records</td></tr>
        <?php endif; ?>
    </table>

</div>
</body>
</html>
