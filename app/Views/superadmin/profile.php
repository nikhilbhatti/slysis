<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="pd-20 card-box mb-30 shadow-sm border-0">
    <div class="d-flex align-items-center mb-4">
        <!-- Avatar -->
        <div class="avatar mr-3">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                 style="width: 80px; height: 80px; font-size: 28px; font-weight: bold;">
                SA
            </div>
        </div>

        <div>
            <h3 class="mb-1 text-primary">
                <i class="fa fa-user-circle"></i>
                <?= esc($superadmin['name'] ?? '') ?>
            </h3>
            <span class="badge badge-info">Super Admin</span>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Profile Details -->
    <div class="row mt-3">
        <div class="col-md-6">
            <table class="table table-borderless table-striped">
                <tbody>
                    <tr>
                        <th>Name:</th>
                        <td><?= esc($superadmin['name'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?= esc($superadmin['email'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Role:</th>
                        <td><span class="badge badge-info">Super Admin</span></td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td>
                            <?= isset($superadmin['created_at']) 
                                ? date('d M Y', strtotime($superadmin['created_at'])) 
                                : '' ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4">
        <a href="<?= base_url('superadmin/edit-profile') ?>" class="btn btn-primary mr-2">
            <i class="fa fa-edit"></i> Edit Profile
        </a>
        <a href="<?= base_url('superadmin/change-password') ?>" class="btn btn-warning">
            <i class="fa fa-key"></i> Change Password
        </a>
    </div>
</div>

<?= $this->endSection() ?>
