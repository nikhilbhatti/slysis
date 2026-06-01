<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="pd-20 card-box mb-30 shadow-sm border-0">
    <div class="d-flex align-items-center mb-4">
        <h3 class="text-primary mb-0"><i class="fa fa-key"></i> Change Password</h3>
    </div>

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Validation Errors -->
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('superadmin/update-password') ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password" required>
        </div>

        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
        </div>

        <button type="submit" class="btn btn-warning btn-block mt-3">
            <i class="fa fa-save"></i> Update Password
        </button>
    </form>
</div>

<?= $this->endSection() ?>
