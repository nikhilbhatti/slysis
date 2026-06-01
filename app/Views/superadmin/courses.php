<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<style>

/* ================= CARD ================= */

.course-card-box {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    border: none;
}

.page-title {
    font-weight: 800;
    letter-spacing: -0.5px;
    color: #1e293b;
}

/* ================= TABLE ================= */

.table-responsive {
    overflow-x: auto;
}

.table thead th {
    background-color: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 1px;
    font-weight: 700;
    color: #64748b;
}

.course-name-cell {
    font-weight: 700;
    color: #334155;
    font-size: 15px;
}

.badge-outline-center {
    border: 1px solid #3b82f6;
    color: #3b82f6;
    background: #eff6ff;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 8px;
    display: inline-block;
}

.price-text {
    color: #10b981;
    font-weight: 800;
    font-size: 16px;
}

/* ================= ACTION BUTTONS ================= */

.btn-action {
    width: 35px;
    height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    transition: 0.3s;
}

.btn-edit { 
    background: #fff7ed; 
    color: #f59e0b; 
    border: 1px solid #fed7aa; 
}
.btn-edit:hover { 
    background: #f59e0b; 
    color: #fff; 
}

.btn-delete { 
    background: #fef2f2; 
    color: #ef4444; 
    border: 1px solid #fee2e2; 
}
.btn-delete:hover { 
    background: #ef4444; 
    color: #fff; 
}

/* ================= RESPONSIVE ================= */

@media (max-width: 991px) {

    .page-header .row {
        flex-direction: column;
        gap: 15px;
    }

    .page-header .col-md-6:last-child {
        text-align: left !important;
    }

    .page-header a.btn {
        width: 100%;
        text-align: center;
    }

}

@media (max-width: 576px) {

    .course-card-box {
        padding: 15px;
    }

    .page-title {
        font-size: 18px;
    }

    .btn-action {
        width: 32px;
        height: 32px;
    }

    .price-text {
        font-size: 14px;
    }

    table.dataTable {
        font-size: 13px;
    }

    .table td, .table th {
        padding: 8px;
    }

}

</style>


<div class="page-header mb-30">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h3 class="page-title mb-2">📚 Manage Courses</h3>
            <p class="text-muted">Aapke sabhi academic centers ke courses ki list yahan hai.</p>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="<?= base_url('superadmin/add-course') ?>" 
               class="btn btn-primary shadow-sm" 
               style="border-radius: 10px; padding: 10px 20px;">
               <i class="bi bi-plus-lg mr-1"></i> Add New Course
            </a>
        </div>
    </div>
</div>


<div class="course-card-box mb-30">

<div class="table-responsive">

<table class="table table-hover" id="courses-table" style="width:100%">

<thead>
<tr>
    <th>Course Details</th>
    <th>Allotted Center</th>
    <th>Fee Structure</th>
    <th>Duration</th>
    <th>Status</th>
    <th class="text-center datatable-nosort">Actions</th>
</tr>
</thead>

<tbody>

<?php if(!empty($courses)): ?>
<?php foreach($courses as $course): ?>
<tr>

<td>
<div class="course-name-cell"><?= esc($course['course_name']) ?></div>
<small class="text-muted">Academic Program</small>
</td>

<td>
<span class="badge-outline-center">
<i class="bi bi-building mr-1"></i>
<?= esc($course['center_name'] ?? 'General') ?>
</span>
</td>

<td>
<span class="price-text">₹<?= number_format($course['fee']) ?></span>
</td>

<td>
<div class="font-weight-bold"><?= esc($course['course_duration']) ?> Months</div>
<small class="text-muted">Full Term</small>
</td>

<td>
<?php if($course['status'] == 1): ?>
<span class="badge badge-success px-3 py-2" style="border-radius: 6px;">Active</span>
<?php else: ?>
<span class="badge badge-danger px-3 py-2" style="border-radius: 6px;">Inactive</span>
<?php endif; ?>
</td>

<td class="text-center">
<div class="d-flex justify-content-center flex-wrap gap-2">

<a href="<?= base_url('superadmin/edit-course/'.$course['id']) ?>" 
class="btn-action btn-edit mr-2" 
title="Edit Course">
<i class="dw dw-edit2"></i>
</a>

<a href="<?= base_url('superadmin/delete-course/'.$course['id']) ?>" 
class="btn-action btn-delete" 
title="Delete Course"
onclick="return confirm('Kya aap wakayi is course ko delete karna chahte hain?')">
<i class="dw dw-delete-3"></i>
</a>

</div>
</td>

</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td colspan="6" class="text-center py-5">
<img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" style="opacity:0.5;">
<p class="mt-3 text-muted">Abhi tak koi courses add nahi kiye gaye hain.</p>
</td>
</tr>
<?php endif; ?>

</tbody>
</table>

</div>
</div>


<script>
$(document).ready(function() {

if ($.fn.DataTable.isDataTable('#courses-table')) {
    $('#courses-table').DataTable().destroy();
}

$('#courses-table').DataTable({
    paging: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: false,
    responsive: true,
    scrollX: true,
    language: {
        search: "Filter Courses:",
        paginate: {
            next: "Next",
            previous: "Prev"
        }
    }
});

});
</script>

<?= $this->endSection() ?>
