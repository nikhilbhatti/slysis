<?= $this->extend('center/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="mobile-container-padding" style="padding: 10px;">
    <div class="container shadow-sm border-0" style="max-width:800px; margin:auto; padding:0; background:#fff; border-radius:12px; overflow:hidden;">
        
        <div style="background: #f8f9ff; padding: 25px; border-bottom: 1px solid #eef0f7; text-align: center;">
            <h2 style="margin:0; font-size: 24px; color: #1e293b; font-weight: 700;">Add New Student</h2>
            <p style="color: #64748b; margin-top: 5px;">Register a new student to your center</p>
        </div>

        <div style="padding: 30px;">
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 8px;">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 8px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 8px;">
                    <ul class="mb-0">
                        <?php foreach(session()->getFlashdata('errors') as $err): ?>
                            <li><?= $err ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="addStudentForm" action="<?= site_url('center/save-student') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-12 mb-4">
                        <label class="form-label fw-bold text-dark">Enrollment Number *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold text-secondary border-end-0">
                                <?= session()->get('center_code') ?>-
                            </span>
                            <input type="text" id="enrollment_suffix" name="student_sequence" 
                                   class="form-control border-start-0 ps-1 fw-bold" required 
                                   value="<?= set_value('student_sequence') ?>"
                                   placeholder="001" style="height: 48px;">
                        </div>
                        <small class="text-muted mt-1 d-block">Student sequence number (e.g. 001, 101)</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Student Name *</label>
                        <input type="text" name="student_name" value="<?= set_value('student_name') ?>" 
                               class="form-control custom-input" required placeholder="Full Name">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark" id="relation_label">Father Name *</label>
                        <div class="input-group">
                            <select name="relation_type" id="relation_type" class="form-select bg-light" style="max-width: 110px; border-radius: 8px 0 0 8px; border-color: #cbd5e1;">
                                <option value="Father" <?= set_select('relation_type', 'Father', true) ?>>S/O</option>
                                <option value="Husband" <?= set_select('relation_type', 'Husband') ?>>W/O</option>
                            </select>
                            <input type="text" name="father_name" id="father_name_input" value="<?= set_value('father_name') ?>" 
                                   class="form-control border-start-0 custom-input" style="border-radius: 0 8px 8px 0;" required placeholder="Father's Name">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Student Phone</label>
                        <input type="text" name="phone" value="<?= set_value('phone') ?>" 
                               class="form-control custom-input" placeholder="Phone Number">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Guardian Phone *</label>
                        <input type="text" name="guardian_phone" value="<?= set_value('guardian_phone') ?>" 
                               class="form-control custom-input" required placeholder="Alternative number">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Date of Birth *</label>
                        <input type="date" name="dob" value="<?= set_value('dob') ?>" 
                               class="form-control custom-input" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Email Address *</label>
                        <input type="email" name="email" value="<?= set_value('email') ?>" 
                               class="form-control custom-input" required placeholder="student@example.com">
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold text-dark">Current Address *</label>
                        <textarea name="address" class="form-control custom-input" rows="2" required placeholder="Full residential address"><?= set_value('address') ?></textarea>
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label fw-bold text-dark">Student Image</label>
                        <input type="file" name="image" class="form-control" style="padding: 10px; background: #fafafa;">
                    </div>

                    <div class="col-12">
                        <div style="background: #f1f5f9; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <h5 class="mb-0 text-dark" style="font-size: 16px; font-weight: 700;">Course Enrollment</h5>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold text-dark">Select Course *</label>
                        <select name="course_id" id="course_id" class="form-select custom-input" required>
                            <option value="">-- Choose Course --</option>
                            <?php if(!empty($courses)): ?>
                                <?php foreach($courses as $course): ?>
                                    <option value="<?= $course['id'] ?>" <?= set_select('course_id', $course['id']) ?>>
                                        <?= $course['course_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Course Fee (₹)</label>
                        <input type="text" id="course_fee" name="fee" value="<?= set_value('fee') ?>" 
                               class="form-control custom-input bg-light" readonly placeholder="Auto-filled">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Duration</label>
                        <input type="text" id="course_duration" name="duration" value="<?= set_value('duration') ?>" 
                               class="form-control custom-input bg-light" readonly placeholder="Auto-filled">
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label fw-bold text-dark">Enrollment Date *</label>
                        <input type="date" name="enroll_date" value="<?= set_value('enroll_date', date('Y-m-d')) ?>" 
                               class="form-control custom-input" required>
                    </div>

                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm" style="min-width: 250px; background: #4c63ff; border:none; padding: 14px; border-radius: 8px; font-weight: 700;">
                            Register Student
                        </button>
                    </div>
                </div>

                <input type="hidden" name="center_id" value="<?= session()->get('center_id') ?>">
            </form>
        </div>
    </div>
</div>

<style>
    .custom-input { height: 48px; border-radius: 8px; border: 1px solid #cbd5e1; transition: 0.2s; }
    .custom-input:focus { border-color: #4c63ff; box-shadow: 0 0 0 4px rgba(76, 99, 255, 0.1); }
    textarea.custom-input { height: auto; }
    /* Select fix for input group */
    .input-group > .form-select:focus { z-index: 3; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    
    // Relation Type Change Logic
    $('#relation_type').on('change', function() {
        var type = $(this).val();
        if(type === 'Husband') {
            $('#relation_label').text('Husband Name *');
            $('#father_name_input').attr('placeholder', "Husband's Name");
        } else {
            $('#relation_label').text('Father Name *');
            $('#father_name_input').attr('placeholder', "Father's Name");
        }
    });

    // Course Details Logic
    $('#course_id').on('change', function() {
        var courseID = $(this).val();
        if (courseID) {
            $('#course_fee').val('Loading...');
            $('#course_duration').val('Loading...');

            $.ajax({
                url: "<?= site_url('center/get-course-details') ?>/" + courseID,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data) {
                        $('#course_fee').val(data.fee);
                        $('#course_duration').val(data.course_duration);
                    }
                }
            });
        }
    });
});
</script>

<?= $this->endSection() ?>