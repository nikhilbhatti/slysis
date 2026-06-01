<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<style>
    /* Page background match karne ke liye */
    body { background-color: #f8f9fa; }
    
    h3.page-title { 
        text-align: center; 
        font-weight: 700; 
        margin-bottom: 2rem; 
        color: #1b2559; 
        font-size: 2.2rem; 
    }

    .form-container { 
        max-width: 900px; 
        margin: 0 auto; 
        padding: 3rem; 
        background-color: #fff; 
        border-radius: 20px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
    }

    .form-label { 
        font-weight: 600; 
        margin-bottom: 0.6rem; 
        color: #344767;
        font-size: 0.95rem;
    }

    .form-control, .form-select { 
        padding: 0.8rem 1rem; 
        border-radius: 10px; 
        border: 1px solid #d2d6da; 
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #5e72e4;
        box-shadow: 0 0 0 2px rgba(94, 114, 228, 0.2);
    }

    .form-error { color: #ea0606; font-size: 0.85rem; margin-top: 0.4rem; font-weight: 500; }

    .btn-submit { 
        width: 100%; 
        font-size: 1.1rem; 
        padding: 0.8rem; 
        border-radius: 12px; 
        background: blue; /* Aapke screenshot ke button color se match */
        border: none;
        font-weight: 600;
        margin-top: 1rem;
    }

    .btn-submit:hover { background: darkblue; transform: translateY(-1px); }
</style>

<h3 class="page-title">Add Course</h3>

<div class="form-container">
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm" style="border-radius: 10px; background-color: #d4edda; color: #155724; padding: 1rem; margin-bottom: 1.5rem;">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 10px; background-color: #f8d7da; color: #721c24; padding: 1rem; margin-bottom: 1.5rem;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('superadmin/add-course') ?>">
        <?= csrf_field() ?> 
        
        <div class="mb-4">
            <label class="form-label">Course Name</label>
            <input type="text" name="course_name" class="form-control" value="<?= set_value('course_name') ?>" placeholder="e.g. Diploma in Computer (DCA)">
            <?php if(isset($errors['course_name'])): ?>
                <div class="form-error"><?= $errors['course_name'] ?></div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label class="form-label">Duration</label>
                <input type="text" name="course_duration" class="form-control" value="<?= set_value('course_duration') ?>" placeholder="e.g. 6 Months or 1 Year">
                <?php if(isset($errors['course_duration'])): ?>
                    <div class="form-error"><?= $errors['course_duration'] ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label">Course Fees (₹)</label>
                <input type="number" name="fee" class="form-control" value="<?= set_value('fee') ?>" placeholder="e.g. 5000">
                <?php if(isset($errors['fee'])): ?>
                    <div class="form-error"><?= $errors['fee'] ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Assign to Center</label>
            <select name="center_id" class="form-select">
                <option value="">Select Center</option>
                <?php foreach($centers as $center): ?>
                    <option value="<?= $center['id'] ?>" <?= set_select('center_id', $center['id']) ?>>
                        <?= $center['center_name'] ?> (<?= $center['center_code'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if(isset($errors['center_id'])): ?>
                <div class="form-error"><?= $errors['center_id'] ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary btn-submit">
            <i class="bi bi-plus-circle me-2"></i> Save & Assign Course
        </button>
    </form>
</div>
<!-- nikhil -->
<?= $this->endSection() ?>