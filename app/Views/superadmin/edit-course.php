<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<style>
    .page-title { color: #1b2559; font-weight: 700; }
    .card { border-radius: 15px; border: none; }
    .form-label { color: #344767; font-size: 0.9rem; margin-bottom: 0.5rem; }
    .form-control, .form-select { 
        border-radius: 10px; 
        padding: 0.6rem 1rem; 
        border: 1px solid #d2d6da; 
    }
    .form-control:focus { border-color: #5e72e4; box-shadow: 0 0 0 2px rgba(94, 114, 228, 0.2); }
    .btn-update { background-color: #4318ff; border: none; border-radius: 10px; padding: 0.6rem 1.5rem; font-weight: 600; }
    .btn-update:hover { background-color: #3311cc; }
    .btn-cancel { border-radius: 10px; padding: 0.6rem 1.5rem; font-weight: 600; }
</style>

<div class="container-fluid">
    <h3 class="page-title mb-4 mt-2">Edit Course Details</h3>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <ul class="mb-0 small fw-600">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <form action="<?= base_url('superadmin/edit-course/'.$course['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group mb-4">
                <label class="form-label fw-bold">Course Name</label>
                <input 
                    type="text" 
                    name="course_name" 
                    class="form-control" 
                    value="<?= set_value('course_name', $course['course_name']) ?>" 
                    placeholder="e.g. Advanced Java Development"
                    required
                >
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">Duration</label>
                        <input 
                            type="text" 
                            name="course_duration" 
                            class="form-control" 
                            value="<?= set_value('course_duration', $course['course_duration']) ?>" 
                            placeholder="e.g. 6 Months or 1 Year"
                            required
                        >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">Course Fee (₹)</label>
                        <input 
                            type="number" 
                            name="fee" 
                            class="form-control" 
                            value="<?= set_value('fee', $course['fee']) ?>" 
                            placeholder="e.g. 5000"
                            required
                        >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">Assign Center</label>
                        <select name="center_id" class="form-select" required>
                            <option value="">-- Select Center --</option>
                            <?php foreach ($centers as $center): ?>
                                <option 
                                    value="<?= $center['id'] ?>" 
                                    <?= (set_value('center_id', $course['center_id']) == $center['id']) ? 'selected' : '' ?>
                                >
                                    <?= $center['center_name'] ?> (<?= $center['center_code'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">Course Status</label>
                        <select name="status" class="form-select" required>
                            <option value="1" <?= (set_value('status', $course['status']) == 1) ? 'selected' : '' ?>>
                                Active
                            </option>
                            <option value="0" <?= (set_value('status', $course['status']) == 0) ? 'selected' : '' ?>>
                                Inactive
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="my-4 opacity-10">

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-update">
                    <i class="bi bi-save me-1"></i> Update Changes
                </button>
                <a href="<?= base_url('superadmin/courses') ?>" class="btn btn-light btn-cancel">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>