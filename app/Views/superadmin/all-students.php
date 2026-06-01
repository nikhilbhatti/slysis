<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* UI Enhancements */
    .page-title { text-align: center; font-weight: 800; color: #1a202c; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px; }
    .table-container { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; }
    .table thead th { background-color: #1e293b; color: #f8fafc; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; padding: 15px; border: none; }
    
    /* Sorting Styles */
    .sortable-header { cursor: pointer; transition: background 0.2s; position: relative; white-space: nowrap; }
    .sortable-header:hover { background-color: #334155 !important; }
    .sort-icon { font-size: 10px; margin-left: 5px; opacity: 0.4; }
    .sort-icon.active { opacity: 1; color: #fbbf24; }

    .table tbody td { padding: 12px 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #334155; }
    .filter-export-row { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 1.5rem; background: #f8fafc; padding: 15px; border-radius: 10px; align-items: center; }
    .form-select-custom, .form-input-custom { padding: 8px 15px; border-radius: 8px; border: 1px solid #cbd5e1; height: 42px; }
    .form-select-custom { min-width: 200px; }
    .form-input-custom { min-width: 250px; }
    
    .badge-center { background: #e0f2fe; color: #0369a1; font-weight: 600; padding: 5px 12px; border-radius: 20px; font-size: 11px; }
    .badge-enroll { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-weight: 700; padding: 3px 8px; border-radius: 6px; font-size: 11px; }
    
    .btn-action-custom { border: none; border-radius: 10px; width: 38px; height: 38px; transition: 0.3s; color: white; display: inline-flex; align-items: center; justify-content: center; }
    .btn-edit-master { background-color: #1e293b; }
    .btn-mail-action { background-color: #4318ff; }
    .btn-action-custom:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.15); color: white; }

    /* Modal Styling */
    .custom-modal-input { height: 48px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px; padding: 10px; width: 100%; transition: 0.2s; }
    .custom-modal-input:focus { border-color: #4318ff; box-shadow: 0 0 0 4px rgba(67, 24, 255, 0.1); outline: none; }
    .label-style { font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 6px; display: block; }
    .admin-section-divider { background: #f8fafc; padding: 10px 15px; border-radius: 8px; border-left: 5px solid #1e293b; margin: 20px 0 15px 0; }
    .file-upload-box { background: #ffffff; border: 1px dashed #cbd5e1; padding: 12px; border-radius: 10px; transition: 0.3s; }
    .file-upload-box:hover { border-color: #4318ff; background: #f0f3ff; }

    @media (max-width: 768px) {
        .filter-export-row { flex-direction: column; align-items: stretch; }
        .table-container { border-radius: 0; border-left: none; border-right: none; }
    }
</style>

<div class="container-fluid py-4">
    <h4 class="page-title">👨‍🎓 Students Master Directory</h4>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" style="border-radius: 10px;">
            <div class="d-flex">
                <i class="fas fa-check-circle me-3 mt-1 fs-5"></i>
                <div>
                    <h6 class="mb-0 fw-bold">Success!</h6>
                    <p class="mb-0 small"><?= session()->getFlashdata('success') ?></p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Updated Filter Form with Search & Error Fix -->
    <form id="filterForm" method="get" class="filter-export-row shadow-sm border">
        <input type="hidden" name="sort" id="sortInput" value="<?= $sortField ?>">
        <input type="hidden" name="order" id="orderInput" value="<?= $sortOrder ?>">

        <div class="d-flex flex-grow-1 gap-2 flex-wrap">
            <!-- Search Filter - Fix: Using service('request') -->
            <input type="text" name="search" class="form-input-custom shadow-sm" placeholder="Search Name or Enrollment..." value="<?= service('request')->getGet('search') ?>">
            
            <select name="center_id" class="form-select-custom shadow-sm">
                <option value="">-- All Training Centers --</option>
                <?php foreach($centers as $center): ?>
                    <option value="<?= $center['id'] ?>" <?= ($selected_center == $center['id']) ? 'selected' : '' ?>>
                        <?= esc($center['center_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-dark px-4"><i class="fas fa-search me-1"></i> Filter</button>
            <a href="<?= base_url('superadmin/all-students') ?>" class="btn btn-outline-secondary px-3" title="Clear Filters"><i class="fas fa-sync-alt"></i></a>
        </div>

        <div class="ms-md-auto d-flex gap-2">
            <a href="<?= base_url("superadmin/all-students-export-excel?center_id=$selected_center&sort=$sortField&order=$sortOrder&search=" . (service('request')->getGet('search') ?? '')) ?>" 
               class="btn btn-outline-success border-2 fw-bold" title="Download Excel">
               <i class="fas fa-file-excel me-1"></i> EXCEL
            </a>
            <a href="<?= base_url("superadmin/all-students-export-pdf?center_id=$selected_center&sort=$sortField&order=$sortOrder&search=" . (service('request')->getGet('search') ?? '')) ?>" 
               class="btn btn-outline-danger border-2 fw-bold" title="Download PDF">
               <i class="fas fa-file-pdf me-1"></i> PDF
            </a>
        </div>
    </form>

    <div class="table-responsive table-container">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th class="sortable-header" onclick="applySort('student_name')">
                        Student Detail 
                        <i class="fas <?= ($sortField == 'student_name') ? ($sortOrder == 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : 'fa-sort sort-icon' ?>"></i>
                    </th>
                    <th>Contacts</th>
                    <th class="sortable-header" onclick="applySort('enrollment_no')">
                        Enrollment 
                        <i class="fas <?= ($sortField == 'enrollment_no') ? ($sortOrder == 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : 'fa-sort sort-icon' ?>"></i>
                    </th>
                    <th class="sortable-header" onclick="applySort('center_name')">
                        Center 
                        <i class="fas <?= ($sortField == 'center_name') ? ($sortOrder == 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : 'fa-sort sort-icon' ?>"></i>
                    </th>
                    <th class="sortable-header" onclick="applySort('course_name')">
                        Status & Course
                        <i class="fas <?= ($sortField == 'course_name') ? ($sortOrder == 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : 'fa-sort sort-icon' ?>"></i>
                    </th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($students)) : ?>
                    <?php foreach($students as $student) : ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= $student['img_display'] ?>" 
                                         style="width: 48px; height: 48px; border-radius: 10px; object-fit: cover; margin-right: 12px; border: 2px solid #e2e8f0;"
                                         onerror="this.src='<?= base_url('uploads/students/default-avatar.png') ?>';">
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 14px;"><?= esc($student['student_name']) ?></div>
                                        <small class="text-muted" style="font-size: 11px;">
                                            <?= (($student['relation_type'] ?? 'Father') == 'Husband') ? 'W/O' : 'S/O' ?>: <?= esc($student['father_name'] ?? 'N/A') ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="line-height: 1.4;">
                                    <div class="small text-muted"><i class="far fa-envelope me-1"></i> <?= esc($student['email']) ?></div>
                                    <div class="small fw-bold text-primary"><i class="fas fa-phone-alt me-1"></i> <?= esc($student['phone']) ?></div>
                                </div>
                            </td>
                            <td><span class="badge-enroll"><?= esc($student['enrollment_no'] ?? 'NEW') ?></span></td>
                            <td><span class="badge-center"><i class="fas fa-map-marker-alt me-1"></i> <?= esc($student['center_name']) ?></span></td>
                            <td>
                                <span class="badge <?= $student['status_class'] ?> mb-1" style="font-size: 9px; padding: 4px 8px;">
                                    <?= strtoupper($student['status_label']) ?>
                                </span>
                                <div style="font-size: 10px; font-weight: 700; color: #475569;"><?= esc($student['course_display']) ?></div>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn-action-custom btn-edit-master btn-student-edit" 
                                            data-id="<?= $student['id'] ?>"
                                            onclick='openEditMasterModal(<?= json_encode($student, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)' 
                                            title="Edit Full Record">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="btn-action-custom btn-mail-action" 
                                            onclick='openMailModal("<?= esc($student['student_name']) ?>", "<?= esc($student['email']) ?>")'
                                            title="Send Notification">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" style="width: 80px; opacity: 0.2;">
                            <p class="text-muted mt-3">No student records match your filter.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Sections -->
<div class="modal fade" id="quickMailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header border-0 bg-white">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-paper-plane me-2 text-primary"></i> Quick Connect</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('superadmin/send-student-notification') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="p-2 mb-3 rounded" style="background: #e0f2fe; border: 1px solid #bae6fd;">
                        <span class="small fw-bold text-dark">To: </span>
                        <span id="mail_display_info" class="small text-primary fw-bold"></span>
                        <input type="hidden" name="student_email" id="target_email">
                    </div>
                    <div class="mb-3">
                        <label class="label-style">Subject</label>
                        <input type="text" name="subject" class="custom-modal-input" placeholder="Enter subject here..." required>
                    </div>
                    <div class="mb-3">
                        <label class="label-style">Message</label>
                        <textarea name="message" class="custom-modal-input" style="height: 150px;" placeholder="Type your message..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="submit" class="btn btn-primary w-100 fw-bold" style="background: #4318ff; border: none; height: 48px; border-radius: 10px;">
                        Send Mail Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="masterEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <form action="<?= base_url('superadmin/update-student-master') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header border-0 bg-dark p-4">
                    <h5 class="fw-bold mb-0 text-white"><i class="fas fa-shield-alt me-2 text-warning"></i> Master Control Panel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                    <input type="hidden" name="student_id" id="m_id">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="label-style">Full Name *</label>
                                    <input type="text" name="student_name" id="m_name" class="custom-modal-input" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="label-style">Enrollment ID</label>
                                    <input type="text" id="m_enroll" class="custom-modal-input bg-light fw-bold" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="label-style">Relation & Guardian *</label>
                                    <div class="input-group">
                                        <select name="relation_type" id="m_rel_type" class="form-select" style="max-width: 90px; height: 48px;">
                                            <option value="Father">S/O</option>
                                            <option value="Husband">W/O</option>
                                        </select>
                                        <input type="text" name="father_name" id="m_father" class="form-control" style="height: 48px;" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="label-style">Date of Birth *</label>
                                    <input type="date" name="dob" id="m_dob" class="custom-modal-input" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="label-style">Student Mobile *</label>
                                    <input type="text" name="phone" id="m_phone" class="custom-modal-input" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="label-style">Alternate Mobile</label>
                                    <input type="text" name="guardian_phone" id="m_gphone" class="custom-modal-input">
                                </div>
                                <div class="col-md-12">
                                    <label class="label-style">Official Email *</label>
                                    <input type="email" name="email" id="m_email" class="custom-modal-input" required>
                                </div>
                                <div class="col-12">
                                    <label class="label-style">Residential Address</label>
                                    <textarea name="address" id="m_address" class="custom-modal-input" style="height: 60px;"></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="admin-section-divider">
                                        <h6 class="mb-0 fw-bold text-dark small"><i class="fas fa-graduation-cap me-2"></i> ACADEMIC ASSIGNMENT</h6>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="label-style">Enrolled Course</label>
                                    <select name="course_id" id="m_course" class="custom-modal-input">
                                        <option value="">-- Change Course --</option>
                                        <?php foreach($courses as $course): ?>
                                            <option value="<?= $course['id'] ?>" data-fee="<?= $course['fee'] ?>" data-duration="<?= $course['course_duration'] ?>">
                                                <?= $course['course_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="label-style">Fee (₹)</label>
                                    <input type="text" id="m_course_fee" class="custom-modal-input bg-light" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="label-style">Duration</label>
                                    <input type="text" id="m_course_duration" class="custom-modal-input bg-light" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="label-style">Admission Date</label>
                                    <input type="date" name="enroll_date" id="m_enroll_date" class="custom-modal-input" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="p-4 bg-light rounded-4 border h-100">
                                <h6 class="fw-bold text-center mb-3">Profile Photo</h6>
                                <div class="text-center mb-4">
                                    <a id="m_preview_link" href="#" target="_blank">
                                        <img id="m_preview" src="<?= base_url('uploads/students/default-avatar.png') ?>" 
                                             style="width: 150px; height: 150px; border-radius: 20px; object-fit: cover; border: 5px solid #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                                    </a>
                                    <div class="mt-3">
                                        <input type="file" name="student_image" class="form-control form-control-sm" accept="image/*" onchange="previewMasterImg(this)">
                                        <small class="text-muted mt-1 d-block">Max Size: 500KB (JPG/PNG)</small>
                                    </div>
                                </div>
                                <div class="admin-section-divider mt-2">
                                    <h6 class="mb-0 fw-bold small"><i class="fas fa-folder-open me-2"></i> ATTACHMENTS</h6>
                                </div>
                                <div class="file-upload-box mb-3">
                                    <label class="label-style text-danger small">Update Certificate</label>
                                    <input type="file" name="certificate_file" class="form-control form-control-sm mb-2">
                                    <div id="current_cert_link"></div>
                                </div>
                                <div class="file-upload-box">
                                    <label class="label-style text-primary small">Verification Document</label>
                                    <input type="file" name="other_letter_doc" class="form-control form-control-sm mb-2">
                                    <div id="current_doc_link"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white shadow-lg d-flex justify-content-between">
                    <div class="text-start">
                        <span class="badge bg-danger p-2" style="font-size: 10px;"><i class="fas fa-exclamation-triangle me-1"></i> CRITICAL ACCESS</span>
                        <p class="mb-0 x-small text-muted mt-1" style="font-size: 11px;">Changes will reflect globally.</p>
                    </div>
                    <button type="submit" class="btn btn-primary px-5 fw-bold" style="background: #1e293b; border: none; height: 48px; border-radius: 10px;">
                        <i class="fas fa-save me-2"></i> Update Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('edit_id');
    if (editId) {
        $(`.btn-student-edit[data-id="${editId}"]`).trigger('click');
    }
});

function openMailModal(name, email) {
    $('#mail_display_info').text(name + " (" + email + ")");
    $('#target_email').val(email);
    var mailModal = new bootstrap.Modal(document.getElementById('quickMailModal'));
    mailModal.show();
}

function applySort(field) {
    let currentField = $('#sortInput').val();
    let currentOrder = $('#orderInput').val();
    let newOrder = (currentField === field && currentOrder === 'ASC') ? 'DESC' : 'ASC';

    $('#sortInput').val(field);
    $('#orderInput').val(newOrder);
    $('#filterForm').submit();
}

$(document).on('change', '#m_course', function() {
    var selected = $(this).find('option:selected');
    $('#m_course_fee').val(selected.data('fee') || '0.00');
    $('#m_course_duration').val(selected.data('duration') || 'N/A');
});

function openEditMasterModal(data) {
    try {
        $('#m_id').val(data.id);
        $('#m_name').val(data.student_name);
        $('#m_enroll').val(data.enrollment_no || 'NOT GENERATED');
        $('#m_rel_type').val(data.relation_type || 'Father');
        $('#m_father').val(data.father_name);
        $('#m_dob').val(data.dob);
        $('#m_phone').val(data.phone);
        $('#m_gphone').val(data.guardian_phone || '');
        $('#m_email').val(data.email);
        $('#m_address').val(data.address || '');
        $('#m_enroll_date').val(data.enroll_date);

        if (data.course_id) {
            $('#m_course').val(data.course_id);
            var selectedOption = $('#m_course').find('option:selected');
            $('#m_course_fee').val(selectedOption.data('fee') || '0.00');
            $('#m_course_duration').val(selectedOption.data('duration') || 'N/A');
        }

        let certBtn = data.certificate_doc ? 
            `<a href="<?= base_url('uploads/certificates/') ?>/${data.certificate_doc}" target="_blank" class="btn btn-sm btn-success w-100 py-2 mt-1"><i class="fas fa-download me-1"></i> Download Cert</a>` : 
            `<span class="badge bg-light text-muted w-100 py-2 mt-1">No File Uploaded</span>`;
        $('#current_cert_link').html(certBtn);

        let docBtn = data.other_letter_doc ? 
            `<a href="<?= base_url('uploads/docs/') ?>/${data.other_letter_doc}" target="_blank" class="btn btn-sm btn-primary w-100 py-2 mt-1"><i class="fas fa-file-pdf me-1"></i> View Doc</a>` : 
            `<span class="badge bg-light text-muted w-100 py-2 mt-1">No File Uploaded</span>`;
        $('#current_doc_link').html(docBtn);

        let studentImgPath = data.image ? data.image.replace(/^\/+|uploads\/students\//g, '') : 'default-avatar.png';
        let fullPhotoUrl = "<?= base_url('uploads/students') ?>/" + studentImgPath;
        
        $('#m_preview').attr('src', fullPhotoUrl);
        $('#m_preview_link').attr('href', fullPhotoUrl);

        var myModal = new bootstrap.Modal(document.getElementById('masterEditModal'));
        myModal.show();
    } catch (err) {
        console.error("Master Modal Exception:", err);
        alert("Critical Error loading student data.");
    }
}

function previewMasterImg(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { 
            $('#m_preview').attr('src', e.target.result); 
            $('#m_preview_link').attr('href', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?= $this->endSection() ?>