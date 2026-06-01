<?= $this->extend('center/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="mobile-container-padding" style="padding: 10px;">
    <div class="container shadow-sm border-0" style="max-width:800px; margin:auto; padding:0; background:#fff; border-radius:12px; overflow:hidden;">
        
        <div style="background: #f8f9ff; padding: 25px; border-bottom: 1px solid #eef0f7; text-align: center;">
            <h2 style="margin:0; font-size: 24px; color: #1e293b; font-weight: 700;">Edit Student Details</h2>
            <p style="color: #64748b; margin-top: 5px;">Update information for <?= esc($student['student_name']) ?></p>
        </div>

        <div style="padding: 30px;">
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 8px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form id="editStudentForm" action="<?= site_url('center/update-student/'.$student['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div style="text-align: center; margin-bottom: 30px; background: #fafbff; padding: 20px; border-radius: 12px; border: 1px dashed #cbd5e1;">
                    <div style="position: relative; display: inline-block; margin-bottom: 15px;">
                        <?php 
                            $photoPath = (!empty($student['image']) && file_exists('uploads/students/'.$student['image'])) 
                                         ? base_url('uploads/students/'.$student['image']) 
                                         : base_url('uploads/students/default-avatar.png'); 
                        ?>
                        <img id="imagePreview" src="<?= $photoPath ?>" alt="Student Photo" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark d-block">Change Profile Image</label>
                        <input type="file" name="image" id="photoInput" class="form-control m-auto" style="max-width: 300px; font-size: 13px;" accept="image/*">
                        <small class="text-muted mt-2 d-block">Leave blank to keep the current photo</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-4">
                        <label class="form-label fw-bold text-dark">Enrollment Number *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold text-secondary border-end-0">
                                <?= session()->get('center_code') ?>-
                            </span>
                            <?php 
                                $full_enrollment = $student['enrollment_no'] ?? '';
                                $parts = explode('-', $full_enrollment);
                                $existing_sequence = end($parts);
                            ?>
                            <input type="text" name="student_sequence" class="form-control border-start-0 ps-1 fw-bold" required 
                                   value="<?= esc($existing_sequence) ?>" style="height: 48px; color: #4c63ff;">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Student Name *</label>
                        <input type="text" name="student_name" value="<?= esc($student['student_name']) ?>" class="form-control custom-input" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark" id="relation_label">
                            <?= ($student['relation_type'] == 'Husband') ? 'Husband Name *' : 'Father Name *' ?>
                        </label>
                        <div class="input-group">
                            <select name="relation_type" id="relation_type" class="form-select bg-light" style="max-width: 110px; border-radius: 8px 0 0 8px; border-color: #cbd5e1;">
                                <option value="Father" <?= ($student['relation_type'] == 'Father') ? 'selected' : '' ?>>S/O</option>
                                <option value="Husband" <?= ($student['relation_type'] == 'Husband') ? 'selected' : '' ?>>W/O</option>
                            </select>
                            <input type="text" name="father_name" id="father_name_input" value="<?= esc($student['father_name']) ?>" 
                                   class="form-control border-start-0 custom-input" style="border-radius: 0 8px 8px 0;" required 
                                   placeholder="<?= ($student['relation_type'] == 'Husband') ? "Husband's Name" : "Father's Name" ?>">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Student Phone</label>
                        <input type="text" name="phone" value="<?= esc($student['phone']) ?>" class="form-control custom-input">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Guardian Phone *</label>
                        <input type="text" name="guardian_phone" value="<?= esc($student['guardian_phone']) ?>" class="form-control custom-input" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Date of Birth *</label>
                        <input type="date" name="dob" value="<?= esc($student['dob']) ?>" class="form-control custom-input" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Email Address *</label>
                        <input type="email" name="email" value="<?= esc($student['email']) ?>" class="form-control custom-input" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold text-dark">Current Address *</label>
                        <textarea name="address" class="form-control custom-input" rows="2" required><?= esc($student['address']) ?></textarea>
                    </div>

                    <div class="col-12 mt-2">
                        <div style="background: #f1f5f9; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <h5 class="mb-0 text-dark" style="font-size: 16px; font-weight: 700;">Update Enrollment</h5>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold text-dark">Select Course *</label>
                        <select name="course_id" id="course_id" class="form-select custom-input" required>
                            <?php foreach($courses as $course): ?>
                                <option value="<?= $course['id'] ?>" <?= ($student['course_id'] == $course['id']) ? 'selected' : '' ?>>
                                    <?= $course['course_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Course Fee (₹)</label>
                        <input type="text" id="course_fee" class="form-control custom-input bg-light" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Duration</label>
                        <input type="text" id="course_duration" class="form-control custom-input bg-light" readonly>
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label fw-bold text-dark">Enrollment Date *</label>
                        <input type="date" name="enroll_date" value="<?= esc($student['enroll_date']) ?>" class="form-control custom-input" required>
                    </div>

                    <div class="col-12 text-center mt-3 d-flex gap-3 justify-content-center">
                        <a href="<?= site_url('center/students') ?>" class="btn btn-light btn-lg" style="min-width: 150px; border-radius: 8px; font-weight: 600; padding: 14px;">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm" style="min-width: 250px; background: #4c63ff; border:none; padding: 14px; border-radius: 8px; font-weight: 700;">
                            Update Student Record
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .custom-input { height: 48px; border-radius: 8px; border: 1px solid #cbd5e1; transition: 0.2s; }
    .custom-input:focus { border-color: #4c63ff; box-shadow: 0 0 0 4px rgba(76, 99, 255, 0.1); }
    textarea.custom-input { height: auto; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Relation Type Change Logic (Father/Husband)
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

    // Image Preview
    $('#photoInput').change(function(){
        const file = this.files[0];
        if (file){
            let reader = new FileReader();
            reader.onload = function(event){
                $('#imagePreview').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Course Details Ajax
    function getDetails(courseID) {
        if (courseID) {
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
    }

    // Load initial details
    getDetails($('#course_id').val());

    // On Course Change
    $('#course_id').on('change', function() {
        getDetails($(this).val());
    });
});
</script>

<?= $this->endSection() ?>