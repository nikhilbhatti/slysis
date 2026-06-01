<?= $this->extend('backend/layout/auth-layout') ?>

<?= $this->section('content') ?>
<a href="<?= base_url('center/add-student') ?>" class="btn btn-primary">Add Student</a>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Father Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($students as $stu): ?>
        <tr>
            <td><?= $stu['id'] ?></td>
            <td><?= $stu['student_name'] ?></td>
            <td><?= $stu['father_name'] ?></td>
            <td><?= $stu['phone'] ?></td>
            <td><?= $stu['email'] ?></td>
            <td><?= $stu['address'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
