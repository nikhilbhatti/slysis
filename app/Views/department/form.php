<!DOCTYPE html>
<html>
<head>
    <title>Add Department</title>
    <style>
        body { font-family: Arial; background: #f4f7f8; }
        .container { width: 400px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        label { font-weight: bold; }
        input[type="text"] { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #ccc; }
        button { width: 100%; padding: 10px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
        .success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
<div class="container">
    <h2>Add Department</h2>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/department/save') ?>">
        <label>Department Name:</label>
        <input type="text" name="dept_name" required>
        <button type="submit">Add Department</button>
    </form>
</div>
</body>
</html>
