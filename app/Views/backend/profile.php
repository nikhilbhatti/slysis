<?= $this->extend('backend/layout/auth-layout') ?>

<?= $this->section('content') ?>
<h2>My Profile</h2>

<form method="post" action="<?= base_url('center/profile') ?>">
    <div>
        <label>Center Name</label>
        <input type="text" name="center_name" value="<?= $center['center_name'] ?>" required>
    </div>
    <div>
        <label>Address</label>
        <textarea name="address"><?= $center['address'] ?></textarea>
    </div>
    <div>
        <label>Phone</label>
        <input type="text" name="phone" value="<?= $center['phone'] ?>">
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="<?= $center['email'] ?>">
    </div>
    <div>
        <label>Status</label>
        <input type="text" value="<?= $center['status'] ? 'Active' : 'Inactive' ?>" readonly>
    </div>
    <button type="submit">Update Profile</button>
</form>
<?= $this->endSection() ?>
