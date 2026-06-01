<?= $this->extend('center/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="pd-20 card-box mb-30">
    <h3 class="text-primary mb-4"><i class="fa fa-edit"></i> Edit Center Profile</h3>

    <form action="<?= base_url('center/update-profile') ?>" method="post">
        <div class="form-group">
            <label>Center Name</label>
            <input type="text" name="center_name" class="form-control" value="<?= esc($center['center_name']) ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= esc($center['email']) ?>" required>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= esc($center['phone']) ?>">
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control"><?= esc($center['address']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Profile</button>
        <a href="<?= base_url('center/profile') ?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
    </form>
</div>

<?= $this->endSection() ?>
