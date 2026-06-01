<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<style>
    /* Global & Typography */
    .page-header { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.04); margin-bottom: 25px; border-left: 5px solid #007bff; }
    .page-title { font-weight: 800; color: #1e293b; margin: 0; text-transform: uppercase; font-size: 1.25rem; letter-spacing: 0.5px; }
    
    /* Filter Section */
    .filter-card { background: #ffffff; padding: 25px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #e2e8f0; }
    .form-label { font-weight: 700; color: #475569; font-size: 0.85rem; }

    /* Table & Container */
    .table-container { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    .table thead th { background-color: #f8fafc; color: #1e293b; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; padding: 18px; border-bottom: 2px solid #dee2e6; text-align: center; }
    .table tbody td { padding: 15px 18px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; text-align: center; }

    /* Enrollment No - Highlighted Box */
    .enroll-no { 
        background: #eef2ff; 
        color: #4338ca; 
        border: 1px solid #c7d2fe; 
        padding: 6px 14px; 
        border-radius: 6px; 
        font-family: 'Segoe UI Mono', 'Consolas', monospace; 
        font-weight: 800; 
        font-size: 0.9rem; 
        display: inline-block;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }
    .no-data-badge { color: #f43f5e; font-style: italic; font-size: 0.8rem; font-weight: 700; }

    /* Badges */
    .status-pill { padding: 6px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px; text-transform: uppercase; }
    .status-completed { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .status-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    
    .access-active { color: #10b981; font-weight: 700; }
    .access-disabled { color: #f43f5e; font-weight: 700; }

    .btn-toggle { border-radius: 6px; font-size: 0.75rem; font-weight: 700; transition: all 0.3s; padding: 8px 12px; text-transform: uppercase; }
</style>

<div class="container-fluid">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h4 class="page-title"><i class="bi bi-person-badge-fill me-2 text-primary"></i>Admin Enrollment Manager</h4>
    </div>

    <div class="filter-card shadow-sm">
        <form method="get" action="<?= base_url('superadmin/enrollments') ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Filter by Academy/Center</label>
                <select name="center_id" class="form-select shadow-sm">
                    <option value="">-- All Centers --</option>
                    <?php foreach($centers as $center): ?>
                        <option value="<?= $center['id'] ?>" <?= (isset($selected_center) && $selected_center == $center['id']) ? 'selected' : '' ?>>
                            <?= esc($center['center_name']) ?> (<?= esc($center['center_code']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Course Status</label>
                <select name="course_status" class="form-select shadow-sm">
                    <option value="">Any Status</option>
                    <option value="pending" <?= (isset($_GET['course_status']) && $_GET['course_status'] == 'pending') ? 'selected' : '' ?>>In Progress</option>
                    <option value="completed" <?= (isset($_GET['course_status']) && $_GET['course_status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
            <div class="col-md-5 d-flex gap-2">
                <button type="submit" class="btn btn-dark px-4 shadow-sm">
                    <i class="bi bi-search me-1"></i> Search Data
                </button>
                <?php if(!empty($selected_center) || !empty($_GET['course_status'])): ?>
                    <a href="<?= base_url('superadmin/enrollments') ?>" class="btn btn-outline-danger px-3 shadow-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-container shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Enrollment No.</th>
                        <th class="text-start">Student & Phone</th>
                        <th class="text-start">Assigned Center</th>
                        <th class="text-start">Course</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($enrollments)): ?>
                        <?php foreach ($enrollments as $e): ?>
                            <tr>
                                <td>
                                    <?php 
                                        $finalEnrollNo = $e['real_enroll_no'] ?? $e['enroll_no'] ?? ''; 
                                    ?>
                                    <?php if (!empty($finalEnrollNo) && $finalEnrollNo !== 'NOT-SET'): ?>
                                        <span class="enroll-no">
                                            <?= esc($finalEnrollNo) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="no-data-badge"><i class="bi bi-exclamation-triangle"></i> PENDING</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-start">
                                    <div class="fw-bold text-dark"><?= esc($e['student_name'] ?? 'N/A') ?></div>
                                    <div class="text-muted small"><i class="bi bi-phone"></i> <?= esc($e['phone'] ?? 'N/A') ?></div>
                                </td>

                                <td class="text-start">
                                    <span class="badge bg-light text-dark border p-2">
                                        <i class="bi bi-building me-1"></i> <?= esc($e['center_name'] ?? 'N/A') ?>
                                    </span>
                                </td>

                                <td class="text-start">
                                    <div class="fw-bold text-primary"><?= esc($e['course_name'] ?? 'N/A') ?></div>
                                </td>

                                <td>
                                    <?php if (($e['course_status'] ?? 'pending') === 'completed'): ?>
                                        <span class="status-pill status-completed">
                                            <i class="bi bi-check-all"></i> Done
                                        </span>
                                    <?php else: ?>
                                        <span class="status-pill status-pending">
                                            <i class="bi bi-hourglass-split"></i> Ongoing
                                        </span>
                                    <?php endif ?>
                                </td>

                                <td>
                                    <?php if (($e['status'] ?? 'active') === 'active'): ?>
                                        <span class="access-active small"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> Active</span>
                                    <?php else: ?>
                                        <span class="access-disabled small"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> Blocked</span>
                                    <?php endif ?>
                                </td>

                                <td>
                                    <a href="<?= base_url('superadmin/toggle-enrollment/'.$e['id']) ?>"
                                       class="btn btn-sm btn-toggle <?= ($e['status'] ?? 'active') === 'active' ? 'btn-outline-danger' : 'btn-success text-white' ?> shadow-sm">
                                       <?= ($e['status'] ?? 'active') === 'active' ? 'Block Access' : 'Grant Access' ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="py-5 text-center">
                                <div class="text-muted">
                                    <i class="bi bi-file-earmark-x fs-1 opacity-25"></i>
                                    <p class="mt-2 fw-bold">No Records Found</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>