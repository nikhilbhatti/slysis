<?= $this->extend('center/layout/pages-layout') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --soft-ui-bg: #f8f9fa;
        --text-main: #344767;
        --text-secondary: #67748e;
        --card-radius: 16px;
    }

    .notice-card {
        border-radius: var(--card-radius);
        border: none;
        transition: transform 0.2s ease;
    }

    .timeline-date {
        min-width: 100px;
    }

    .timeline-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #cb0c9f; /* Soft Pink Theme */
        display: inline-block;
        margin-right: 10px;
        box-shadow: 0 0 0 4px rgba(203, 12, 159, 0.1);
    }

    .msg-content {
        background: #ffffff;
        border-radius: 12px;
        padding: 15px 20px;
        border: 1px solid #f0f0f0;
        position: relative;
    }

    .msg-content:hover {
        background: #fdfdfd;
        border-color: #e2e2e2;
    }

    .badge-status {
        font-size: 10px;
        text-transform: uppercase;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 700;
    }

    .bg-soft-new { background: #e8fff3; color: #17ad37; }
    .bg-soft-read { background: #f6f9fc; color: #8392ab; }

    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #e2e2e2; border-radius: 10px; }
</style>

<div class="container-fluid py-4" style="background: var(--soft-ui-bg); min-height:100vh;">

    <div class="row mb-4 px-2 align-items-center">
        <div class="col-8">
            <h3 class="mb-0" style="font-weight:800; color: var(--text-main); letter-spacing: -0.5px;">Notice Board</h3>
            <p class="text-sm text-secondary mb-0">Track all announcements sent by Super Admin</p>
        </div>
        <div class="col-4 text-end">
            <a href="<?= base_url('center/dashboard') ?>" class="btn btn-sm bg-white shadow-sm text-dark border-radius-lg px-3">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-11 mx-auto">
            <div class="card notice-card shadow-lg">
                <div class="card-header pb-0 p-4 bg-white border-0">
                    <h6 class="mb-0 fw-bold">Recent History</h6>
                </div>
                
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0 border-0">
                            <tbody>
                                <?php if(!empty($messages)): ?>
                                    <?php foreach($messages as $msg): ?>
                                    <tr class="border-0">
                                        <td class="border-0 ps-0 align-top" style="width: 180px;">
                                            <div class="d-flex align-items-center">
                                                <span class="timeline-dot"></span>
                                                <div>
                                                    <h6 class="text-sm mb-0 fw-bold text-dark"><?= date('d M, Y', strtotime($msg['created_at'])) ?></h6>
                                                    <p class="text-xs text-secondary mb-0"><?= date('h:i A', strtotime($msg['created_at'])) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="border-0 align-top">
                                            <div class="msg-content shadow-sm">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <p class="text-sm mb-0 pe-4" style="line-height: 1.6; color: #4e5e7a;">
                                                        <?= esc($msg['message']) ?>
                                                    </p>
                                                    <?php if(($msg['status'] ?? '') == 'read'): ?>
                                                        <span class="badge-status bg-soft-read">Read</span>
                                                    <?php else: ?>
                                                        <span class="badge-status bg-soft-new">New</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="height: 15px;"><td colspan="2" class="border-0"></td></tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="text-center py-7">
                                            <div class="mb-3">
                                                <i class="bi bi-megaphone text-light" style="font-size: 60px;"></i>
                                            </div>
                                            <h5 class="text-secondary font-weight-normal">No announcements found.</h5>
                                            <p class="text-sm text-muted">History will appear here once you receive messages.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>