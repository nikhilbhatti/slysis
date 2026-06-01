<h3>Pending Leaves</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>Employee</th>
        <th>Reason</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach($leaves as $leave): ?>
        <tr>
            <td><?= $leave['name'] ?></td>
            <td><?= $leave['reason'] ?></td>
            <td><?= $leave['status'] ?></td>
            <td>
                <?php if($leave['status']=='Pending'): ?>
                    <a href="<?= base_url('leave/approve/'.$leave['id']) ?>">Approve</a>
                    <form method="post" action="<?= base_url('leave/reject/'.$leave['id']) ?>" style="display:inline;">
                        <input type="text" name="admin_reason" placeholder="Reason for rejection" required>
                        <button type="submit">Reject</button>
                    </form>
                <?php else: ?>
                    <?= $leave['status'] ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
