<?= $this->extend('center/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="pd-20">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary"><i class="fa fa-building"></i> Center Profile</h3>
        <a href="<?= base_url('center/edit-profile') ?>" class="btn btn-outline-primary">
            <i class="fa fa-edit"></i> Edit Profile
        </a>
    </div>

    <!-- Profile Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <!-- Left Column: Icon / Image -->
                <div class="col-md-3 text-center mb-3">
                    <div class="profile-icon rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width:120px; height:120px; font-size:48px;">
                        <?= strtoupper(substr($center['center_name'], 0, 1)) ?>
                    </div>
                </div>

                <!-- Right Column: Details -->
                <div class="col-md-9">
                    <h4 class="mb-2"><?= esc($center['center_name']) ?></h4>
                    <p class="text-muted mb-1"><strong>Center Code:</strong> <?= esc($center['center_code']) ?></p>
                    <p class="text-muted mb-1"><strong>Email:</strong> <a href="mailto:<?= esc($center['email']) ?>"><?= esc($center['email']) ?></a></p>
                    <p class="text-muted mb-1"><strong>Phone:</strong> <a href="tel:<?= esc($center['phone'] ?? 'Not Updated') ?>"><?= esc($center['phone'] ?? 'Not Updated') ?></a></p>
                    <p class="text-muted mb-1"><strong>Address:</strong> <?= esc($center['address'] ?? 'Not Updated') ?></p>
                    <p class="mb-1"><strong>Status:</strong>
                        <?php if ($center['status'] == 1): ?>
                            <span class="badge badge-success">Active</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Inactive</span>
                        <?php endif; ?>
                    </p>
                    <p class="text-muted mb-0"><strong>Created At:</strong> <?= date('d M Y', strtotime($center['created_at'])) ?></p>

                    <div class="mt-3">
                        <a href="<?= base_url('center/dashboard') ?>" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modern Profile Icon */
    .profile-icon {
        font-weight: bold;
        letter-spacing: 2px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: transform 0.3s;
    }
    .profile-icon:hover {
        transform: scale(1.05);
    }
    /* Card Hover Effect */
    .card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        transition: all 0.3s;
    }
</style>

<?= $this->endSection() ?>
