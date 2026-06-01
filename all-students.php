<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.page-title {
    text-align: center;
    font-weight: 800;
    color: #1a202c;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Desktop Table */
.table-container {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.table thead th {
    background-color: #1e293b;
    color: #f8fafc;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    padding: 15px;
    border: none;
}

.table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
}

/* Filter */
.filter-export-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 1.5rem;
    background: #f8fafc;
    padding: 15px;
    border-radius: 10px;
}

.form-select-custom {
    padding: 8px 15px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    min-width: 200px;
}

/* Mobile Card View */
.student-card {
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
}

.student-card h6 {
    font-weight: 700;
    color: #1e293b;
}

.badge-center {
    background: #e0f2fe;
    color: #0369a1;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
}

/* Action Buttons */
.btn-mail-action {
    background-color: #4318ff;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 8px 12px;
    transition: 0.3s;
}
.btn-mail-action:hover {
    background-color: #3311cc;
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .filter-export-row {
        flex-direction: column;
    }
    .filter-export-row .btn,
    .form-select-custom {
        width: 100%;
    }
    .table-container {
        display: none;
    }
}
</style>

<div class="container-fluid py-3">
    <h4 class="page-title">👨‍🎓 All Students Directory</h4>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; border: none; background-color: #d1e7dd; color: #0f5132;">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; border: none; background-color: #f8d7da; color: #842029;">
            <i class="fas fa-exclamation-triangle me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="get" class="filter-export-row shadow-sm">
        <select name="center_id" class="form-select-custom">
            <option value="">-- View All Centers --</option>
            <?php foreach($centers as $center): ?>
                <option value="<?= $center['id'] ?>" <?= ($selected_center == $center['id']) ? 'selected' : '' ?>>
                    <?= esc($center['center_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-funnel"></i> Find
        </button>

        <a href="<?= base_url('superadmin/all-students-export-excel?center_id='.$selected_center) ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Excel
        </a>

        <a href="<?= base_url('superadmin/all-students-export-pdf?center_id='.$selected_center) ?>" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> PDF
        </a>
    </form>

    <div class="table-responsive table-container">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Center</th>
                    <th>Courses</th>
                    <th>Action</th> 
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($students)) : ?>
                    <?php foreach($students as $student) : ?>
                        <tr>
                            <td>
                                <strong><?= esc($student['student_name']) ?></strong><br>
                                <small class="text-muted">
                                    <?= ($student['relation_type'] == 'Husband') ? 'Husband: ' : 'Father: ' ?> 
                                    <?= esc($student['father_name'] ?? 'N/A') ?>
                                </small>
                            </td>
                            <td><?= esc($student['email']) ?></td>
                            <td>
                                S: <?= esc($student['phone']) ?><br>
                                G: <?= esc($student['guardian_phone'] ?? 'N/A') ?>
                            </td>
                            <td>
                                <span class="badge-center"><?= esc($student['center_name']) ?></span>
                            </td>
                            <td><?= $student['courses'] ?? 'No Course' ?></td>
                            <td>
                                <button type="button" class="btn-mail-action" 
                                        onclick="openQuickMail('<?= esc($student['email']) ?>', '<?= esc($student['student_name']) ?>')">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-4">No students found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-md-none">
        <?php if(!empty($students)) : ?>
            <?php foreach($students as $student) : ?>
                <div class="student-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <h6><?= esc($student['student_name']) ?></h6>
                        <button class="btn btn-primary btn-sm rounded-circle" 
                                onclick="openQuickMail('<?= esc($student['email']) ?>', '<?= esc($student['student_name']) ?>')">
                            <i class="fas fa-envelope"></i>
                        </button>
                    </div>
                    <small class="text-muted">
                        <?= ($student['relation_type'] == 'Husband') ? 'Husband: ' : 'Father: ' ?> 
                        <?= esc($student['father_name'] ?? 'N/A') ?>
                    </small>
                    <hr>
                    <p><strong>Email:</strong> <?= esc($student['email']) ?></p>
                    <p><strong>Student Phone:</strong> <?= esc($student['phone']) ?></p>
                    <p><strong>Guardian Phone:</strong> <?= esc($student['guardian_phone'] ?? 'N/A') ?></p>
                    <p><strong>Center:</strong> 
                        <span class="badge-center"><?= esc($student['center_name']) ?></span>
                    </p>
                    <p class="mb-0"><strong>Courses:</strong> <?= $student['courses'] ?? 'No Course Assigned' ?></p>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="student-card text-center">No students found.</div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="quickMailModal" tabindex="-1" aria-labelledby="quickMailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <form action="<?= base_url('superadmin/send-custom-mail') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold text-dark"><i class="fas fa-paper-plane text-primary me-2"></i> Send Mail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Sending to: <b id="studentNameLabel"></b></p>
                    <input type="hidden" name="email" id="quick_mail_to">
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Subject</label>
                        <input type="text" name="subject" class="form-control" style="border-radius: 10px;" placeholder="What is this mail about?" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Message Content</label>
                        <textarea name="message" class="form-control" style="border-radius: 10px;" rows="5" placeholder="Type your full message here..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 12px;">
                        <i class="fas fa-share-square me-2"></i> Send Message Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openQuickMail(email, name) {
    if(!email || email === "") {
        alert("This student does not have an email address!");
        return;
    }
    document.getElementById('quick_mail_to').value = email;
    document.getElementById('studentNameLabel').innerText = name + " (" + email + ")";
    
    var myModal = new bootstrap.Modal(document.getElementById('quickMailModal'));
    myModal.show();
}
</script>

<?= $this->endSection() ?>