<!DOCTYPE html>
<html>
<head>
    <title>Employee Details Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 450px;
            margin: 50px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update Employee Details</h2>

    <!-- Flash messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/employee/update') ?>">

        

        

    
        <label>Designation:</label>
        <select name="designation" required>
            <option value="">Select Designation</option>
            <option value="Developer" <?= (isset($employee['designation']) && $employee['designation']=='Developer') ? 'selected' : '' ?>>Developer</option>
            <option value="Manager" <?= (isset($employee['designation']) && $employee['designation']=='Manager') ? 'selected' : '' ?>>Manager</option>
            <option value="Tester" <?= (isset($employee['designation']) && $employee['designation']=='Tester') ? 'selected' : '' ?>>Tester</option>
            <option value="Designer" <?= (isset($employee['designation']) && $employee['designation']=='Designer') ? 'selected' : '' ?>>Designer</option>
        </select>

    
        <label>Salary:</label>
        <input type="text" name="salary" value="<?= isset($employee['salary']) ? $employee['salary'] : '' ?>" required>

        <button type="submit">Update Details</button>
    </form>
</div>

</body>
</html>
