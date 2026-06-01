<?= $this->extend('center/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="pd-20 card-box mb-30">
    <h3 class="text-primary mb-4"><i class="dw dw-lock"></i> Change Password</h3>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('center/update-password') ?>" method="post">
        <div class="form-group">
            <label>Old Password</label>
            <input type="password" name="old_password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Password</button>
        <a href="<?= base_url('center/profile') ?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
    </form>
</div>

<?= $this->endSection() ?>
                `