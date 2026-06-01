<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fa fa-edit mr-2"></i> Edit Center</h4>
        </div>

        <div class="card-body">

            <!-- Flash messages -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <?php if(session()->get('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach(session()->get('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('superadmin/edit-center/'.$center['id']) ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label>Center Name</label>
                    <input type="text" name="center_name" class="form-control" value="<?= old('center_name', $center['center_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Center Code</label>
                    <input type="text" class="form-control" value="<?= $center['center_code'] ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= old('email', $center['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= old('phone', $center['phone']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="3" required><?= old('address', $center['address']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Password <small class="text-muted">(leave blank to keep current)</small></label>
                    <input type="password" name="password" class="form-control" placeholder="New password">
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url('superadmin/centers') ?>" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Update Center
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
