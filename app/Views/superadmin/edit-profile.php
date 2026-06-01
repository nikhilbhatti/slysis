<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="pd-20 card-box mb-30">
    <h3 class="text-primary mb-4">
        <i class="fa fa-user-edit"></i> Edit Profile
    </h3>

    <!-- Success Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Validation Errors -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('superadmin/edit-profile') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Name</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="<?= esc(old('name', $superadmin['name'] ?? '')) ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="<?= esc(old('email', $superadmin['email'] ?? '')) ?>"
                   required>
        </div>

        <button type="submit" class="btn btn-success mt-3">
            <i class="fa fa-save"></i> Update Profile
        </button>

        <a href="<?= base_url('superadmin/profile') ?>" class="btn btn-secondary mt-3">
            Cancel
        </a>
    </form>
</div>

<?= $this->endSection() ?>
