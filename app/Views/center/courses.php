<?= $this->extend('center/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div style="background:#eef1f5;padding:35px;min-height:100vh;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="margin:0;font-weight:700;color:#1f2937;">Active Courses</h2>
            <p style="margin-top:6px;font-size:15px;color:#6b7280;">List of courses assigned to your center</p>
        </div>
        <a href="<?= base_url('center/dashboard') ?>" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
            <i class="fa fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="vertical-align: middle;">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
                        <tr>
                            <th style="padding:15px 25px; color:#4a5568; font-weight:700; width: 80px;">#</th>
                            <th style="padding:15px 25px; color:#4a5568; font-weight:700;">Course Name</th>
                            <th style="padding:15px 25px; color:#4a5568; font-weight:700;">Duration</th>
                            <th style="padding:15px 25px; color:#4a5568; font-weight:700;">Fees</th>
                            <th style="padding:15px 25px; color:#4a5568; font-weight:700; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($courses)): ?>
                            <?php foreach($courses as $key => $course): ?>
                            <tr style="border-bottom: 1px solid #edf2f7;">
                                <td style="padding:15px 25px; font-weight:600; color:#2d3748;"><?= $key + 1 ?></td>
                                <td style="padding:15px 25px;">
                                    <div style="font-weight:700; color:#1a202c; font-size: 16px;"><?= esc($course['course_name']) ?></div>
                                </td>
                                <td style="padding:15px 25px; color:#4a5568;">
                                    <span class="badge bg-light text-dark" style="padding:8px 14px; border-radius:8px; border:1px solid #e2e8f0; font-weight: 600;">
                                        <i class="far fa-calendar-alt me-1 text-primary"></i> 
                                        <?= esc($course['course_duration'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td style="padding:15px 25px; font-weight:800; color:#2d3748; font-size: 16px;">
                                    ₹<?= number_format($course['fee'] ?? 0) ?>
                                </td>
                                <td style="padding:15px 25px; text-align: center;">
                                    <span class="badge" style="padding:8px 16px; border-radius:20px; background-color: #dcfce7; color: #166534; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fa fa-check-circle me-1"></i> Active
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center" style="padding:60px; color:#a0aec0;">
                                    <div class="mb-3">
                                        <i class="fa fa-folder-open" style="font-size:50px; opacity: 0.3;"></i>
                                    </div>
                                    <h5 style="font-weight: 600; color: #718096;">No active courses found</h5>
                                    <p class="mb-0">Please contact Super Admin to assign courses to your center.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr { transition: background-color 0.2s ease; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    .me-1 { margin-right: 0.25rem !important; }
</style>

<?= $this->endSection() ?>