<?= $this->extend('center/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div style="background:#f4f7fa; padding:20px; min-height:100vh;">

    <div style="display:flex; flex-direction: column; gap:15px; margin-bottom:25px;">
        <div style="display:flex; flex-direction: column; gap:10px;">
            <h2 style="color:#1a202c; font-weight:700; margin:0; font-size: clamp(22px, 5vw, 28px);"><?= $pageTitle ?? 'Students' ?></h2>
            <p style="color: #718096; font-size: 14px; margin:0;">Manage your students using their unique enrollment numbers.</p>
        </div>
        <a href="<?= base_url('center/add-student') ?>" class="btn-add-new" style="align-self: flex-start;">
            <i class="fa fa-plus"></i> <span class="btn-text">Add New Student</span>
        </a>
    </div>

    <div class="filter-container">
        <div class="tabs-wrapper" style="overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch;">
            <button type="button" onclick="filterStudents('all')" id="btn-all" class="filter-tab active">All Students</button>
            <button type="button" onclick="filterStudents('completed')" id="btn-completed" class="filter-tab">Completed</button>
            <button type="button" onclick="filterStudents('pending')" id="btn-pending" class="filter-tab">In Progress</button>
        </div>

        <div class="search-wrapper">
            <i class="fa fa-search"></i>
            <input type="text" id="studentSearch" placeholder="Search by Enrollment, Name or Phone..." onkeyup="searchTable()">
        </div>

        <div class="stats-wrapper">
            <div class="stat-mini" style="border-left-color: #ffc107;">
                <span class="label">IN PROGRESS</span>
                <span class="val" id="count-pending">
                    <?php 
                        $pendingCount = 0;
                        if(!empty($students)){
                            foreach($students as $s) {
                                if(($s['course_status'] ?? 'pending') != 'completed') $pendingCount++;
                            }
                        }
                        echo $pendingCount;
                    ?>
                </span>
            </div>
            <div class="stat-mini" style="border-left-color: #28a745;">
                <span class="label">COMPLETED</span>
                <span class="val" id="count-completed">
                    <?php 
                        $completedCount = 0;
                        if(!empty($students)){
                            foreach($students as $s) {
                                if(($s['course_status'] ?? '') == 'completed') $completedCount++;
                            }
                        }
                        echo $completedCount;
                    ?>
                </span>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table id="studentTable">
                <thead>
                    <tr>
                        <th width="150">Enrollment No.</th> 
                        <th>Student Info</th>
                        <th class="hide-mobile">Selected Course</th> 
                        <th class="hide-mobile">Enroll Date</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($students)): ?>
                        <?php foreach($students as $student): ?>
                            <?php 
                                $status = $student['course_status'] ?? 'pending'; 
                                $enrollNo = !empty($student['enrollment_no']) ? $student['enrollment_no'] : 'NOT-SET';
                                
                                // PHOTO PATH LOGIC
                                $photoName = $student['photo'] ?? $student['image'] ?? '';
                                $photoPath = base_url('assets/images/default-avatar.png'); 
                                
                                if (!empty($photoName)) {
                                    $fullPath = FCPATH . 'uploads/students/' . $photoName;
                                    if (file_exists($fullPath)) {
                                        $photoPath = base_url('uploads/students/' . $photoName);
                                    }
                                }

                                // CERTIFICATE PATH
                                $certFile = $student['certificate_doc'] ?? '';
                                $hasCert = false;
                                if (!empty($certFile) && file_exists(FCPATH . 'uploads/certificates/' . $certFile)) {
                                    $certPath = base_url('uploads/certificates/' . $certFile);
                                    $hasCert = true;
                                }
                                
                                $studentName = esc($student['student_name'] ?? 'Student');
                                $studentPhone = esc($student['phone'] ?? 'N/A');
                                $studentEmail = esc($student['email'] ?? 'N/A');
                                $courseName = esc($student['course_name'] ?? 'N/A');
                            ?>
                            <tr class="student-row" data-status="<?= $status ?>">
                                <td>
                                    <div class="enrollment-badge">
                                        <?= esc($enrollNo) ?>
                                    </div>
                                </td>

                                <td>
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <img src="<?= $photoPath ?>" 
                                             onclick="openPhotoModal('<?= $photoPath ?>', '<?= $studentName ?>', '<?= $courseName ?>', '<?= $studentPhone ?>', '<?= $studentEmail ?>')" 
                                             style="width:45px; height:45px; border-radius:50%; object-fit:cover; cursor:pointer; border:2px solid #4361ee; box-shadow: 0 2px 8px rgba(67,97,238,0.2); flex-shrink:0;" 
                                             title="Click to view full photo"
                                             onerror="this.src='<?= base_url('assets/images/default-avatar.png') ?>';">
                                        <div style="min-width:0;">
                                            <div class="std-name"><?= $studentName ?></div>
                                            <div class="std-meta show-mobile">
                                                <span class="mobile-course"><?= $courseName ?></span>
                                                <span class="mobile-date"><?= isset($student['enroll_date']) ? date('d M Y', strtotime($student['enroll_date'])) : '-' ?></span>
                                            </div>
                                            <div class="std-meta hide-mobile" style="display:flex; align-items:center; gap:8px;">
                                                <span><i class="fa fa-phone"></i> <?= $studentPhone ?></span>
                                                <span style="color:#cbd5e0;">•</span>
                                                <span><i class="fa fa-envelope"></i> <?= $studentEmail ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="hide-mobile">
                                    <div class="course-badge"><?= $courseName ?></div>
                                </td>

                                <td class="date-cell hide-mobile">
                                    <i class="fa fa-calendar-alt"></i> 
                                    <?= isset($student['enroll_date']) ? date('d M, Y', strtotime($student['enroll_date'])) : '-' ?>
                                </td>

                                <td style="text-align:center;">
                                    <span class="status-pill <?= $status == 'completed' ? 'status-complete' : 'status-progress' ?>">
                                        <?= ucfirst($status == 'completed' ? 'Completed' : 'In Progress') ?>
                                    </span>
                                </td>

                                <td style="text-align:center;">
                                    <div class="action-group">
                                        <?php if($hasCert): ?>
                                            <a href="<?= $certPath ?>" target="_blank" class="cert-ribbon-btn" title="View Certificate">
                                                <div class="cert-stamp"><i class="fa fa-medal"></i></div>
                                                <div class="cert-ribbon-tails"></div>
                                            </a>
                                        <?php endif; ?>

                                        <button type="button" class="action-btn btn-view" 
                                                onclick="openPhotoModal('<?= $photoPath ?>', '<?= $studentName ?>', '<?= $courseName ?>', '<?= $studentPhone ?>', '<?= $studentEmail ?>')" 
                                                title="View Full Photo">
                                            <i class="fa fa-image"></i>
                                        </button>

                                        <button type="button" class="action-btn <?= $status == 'completed' ? 'btn-undo' : 'btn-done' ?>" 
                                                onclick="updateCourseStatus(<?= $student['id'] ?>, '<?= $status ?>')"
                                                title="<?= $status == 'completed' ? 'Mark as Pending' : 'Mark as Done' ?>">
                                            <i class="fa <?= $status == 'completed' ? 'fa-undo' : 'fa-check' ?>"></i>
                                        </button>

                                        <a href="<?= base_url('center/edit-student/'.$student['id']) ?>" 
                                           class="action-btn btn-edit" title="Edit Student">
                                             <i class="fa fa-edit"></i>
                                        </a>
                                        
                                        <a href="<?= base_url('center/delete-student/'.$student['id']) ?>" 
                                           class="action-btn btn-del" 
                                           onclick="return confirm('Are you sure you want to delete this student?');" title="Delete">
                                             <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="no-data" style="text-align:center; padding:40px;">
                                <i class="fa fa-users" style="font-size: 48px; color: #cbd5e0; margin-bottom: 15px;"></i>
                                <p style="color: #64748b; font-size: 16px;">No students found. Click "Add New Student" to get started!</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="photoModal" class="modal-overlay" onclick="closePhotoModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <span class="close-modal" onclick="closePhotoModal()">&times;</span>
        
        <div style="display: flex; flex-direction: column; align-items: center; gap: 15px; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px;">
            <div style="background: linear-gradient(135deg, #4361ee, #3a0ca3); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fa fa-user-graduate" style="font-size: 28px; color: white;"></i>
            </div>
            <div style="text-align: center;">
                <h3 id="modalStudentName" style="margin:0; color:#2d3748; font-size: clamp(18px, 4vw, 22px); font-weight:700;">Student Photo</h3>
                <p id="modalCourseName" style="margin:8px 0 0; color:#64748b; font-size:14px;"></p>
            </div>
        </div>
        
        <div style="background: #f8fafc; border-radius: 16px; padding: 15px; margin-bottom: 15px;">
            <img id="modalImage" src="" alt="Student Photo" 
                 style="width:100%; max-height:400px; border-radius:12px; object-fit:contain; background:#ffffff;"
                 onerror="this.src='<?= base_url('assets/images/default-avatar.png') ?>';">
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 15px; background: #f1f5f9; padding: 15px; border-radius: 12px; margin-top: 10px;">
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <p id="modalStudentPhone" style="margin:0; color:#475569; font-size:14px; word-break: break-word;"><i class="fa fa-phone"></i> <span></span></p>
                <p id="modalStudentEmail" style="margin:0; color:#475569; font-size:14px; word-break: break-word;"><i class="fa fa-envelope"></i> <span></span></p>
            </div>
        </div>
        
        <p style="margin-top:15px; color:#94a3b8; font-size:12px;">
            <i class="fa fa-info-circle"></i> Click outside or press ESC to close
        </p>
    </div>
</div>

<style>
    * { box-sizing: border-box; }
    
    .filter-container { display: flex; flex-direction: column; gap: 15px; margin-bottom: 25px; }
    @media (min-width: 768px) { .filter-container { flex-direction: row; align-items: center; flex-wrap: wrap; } }
    @media (min-width: 992px) { .filter-container { flex-wrap: nowrap; } }
    
    .tabs-wrapper { background: #e2e8f0; padding: 5px; border-radius: 12px; display: flex; width: 100%; }
    @media (min-width: 768px) { .tabs-wrapper { width: auto; } }
    
    .filter-tab { border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; background: transparent; color: #4a5568; transition: 0.3s; flex: 1; white-space: nowrap; }
    @media (min-width: 480px) { .filter-tab { padding: 8px 18px; font-size: 14px; } }
    .filter-tab.active { background: #fff; color: #1b00ff; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    
    .search-wrapper { position: relative; width: 100%; }
    @media (min-width: 768px) { .search-wrapper { flex: 1; min-width: 250px; } }
    .search-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #a0aec0; z-index: 1; }
    .search-wrapper input { width: 100%; padding: 12px 15px 12px 40px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; font-size: 14px; transition: 0.3s; }
    
    .stats-wrapper { display: flex; gap: 10px; width: 100%; }
    @media (min-width: 768px) { .stats-wrapper { width: auto; } }
    .stat-mini { padding: 8px 12px; border-left: 4px solid; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; flex-direction: column; flex: 1; }
    @media (min-width: 480px) { .stat-mini { padding: 8px 18px; flex: none; } }
    .stat-mini .label { font-size: 10px; color: #718096; font-weight: 700; }
    .stat-mini .val { font-size: 18px; font-weight: 800; color: #2d3748; }
    
    .btn-add-new { background: #1b00ff; color: #fff; padding: 12px 20px; border-radius: 10px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; box-shadow: 0 4px 12px rgba(27, 0, 255, 0.2); border: none; font-size: 14px; width: 100%; justify-content: center; }
    @media (min-width: 480px) { .btn-add-new { width: auto; justify-content: flex-start; padding: 12px 24px; } }
    
    .table-card { background: #fff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; min-width: 650px; }
    th { padding: 15px 12px; text-align: left; font-size: 12px; text-transform: uppercase; color: #4a5568; background: #f8fafc; border-bottom: 2px solid #edf2f7; }
    td { padding: 15px 12px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    
    .hide-mobile { display: none; }
    .show-mobile { display: block; }
    @media (min-width: 768px) { .hide-mobile { display: table-cell; } .show-mobile { display: none; } }
    
    .enrollment-badge { background: #eef2ff; color: #4338ca; padding: 4px 8px; border-radius: 4px; font-weight: 800; font-family: monospace; border: 1px solid #c7d2fe; display: inline-block; font-size: 11px; }
    .std-name { font-weight: 700; color: #2d3748; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px; }
    .std-meta { font-size: 12px; color: #718096; margin-top: 2px; }
    .course-badge { background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; }
    
    .status-pill { padding: 4px 8px; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: uppercase; display: inline-block; white-space: nowrap; }
    .status-complete { background: #d1fae5; color: #065f46; }
    .status-progress { background: #fef3c7; color: #92400e; }
    
    .action-group { display: flex; justify-content: center; gap: 4px; flex-wrap: wrap; align-items: center; }
    .action-btn { width: 28px; height: 28px; display: flex !important; align-items: center; justify-content: center; border-radius: 6px; color: #fff !important; text-decoration: none; border: none; cursor: pointer; font-size: 12px; }
    @media (min-width: 480px) { .action-btn { width: 34px; height: 34px; font-size: 14px; } }
    
    /* SPECIAL CERTIFICATE RIBBON STYLE */
    .cert-ribbon-btn { position: relative; width: 34px; height: 38px; display: flex; flex-direction: column; align-items: center; text-decoration: none; cursor: pointer; margin-right: 5px; }
    .cert-stamp { background: #00bcd4; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; z-index: 2; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
    .cert-ribbon-tails { position: absolute; top: 15px; width: 22px; height: 18px; z-index: 1; }
    .cert-ribbon-tails::before, .cert-ribbon-tails::after { content: ''; position: absolute; width: 8px; height: 16px; background: #0097a7; top: 0; }
    .cert-ribbon-tails::before { left: 0; transform: rotate(20deg); clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 50% 80%, 0% 100%); }
    .cert-ribbon-tails::after { right: 0; transform: rotate(-20deg); clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 50% 80%, 0% 100%); }

    .btn-view { background: #6366f1; }
    .btn-done { background: #059669; }
    .btn-undo { background: #718096; }
    .btn-edit { background: #3b82f6; }
    .btn-del { background: #ef4444; }
    
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 99999; justify-content: center; align-items: center; backdrop-filter: blur(5px); padding: 15px; }
    .modal-content { background: #fff; padding: 20px; border-radius: 24px; width: 100%; max-width: 600px; position: relative; text-align: center; animation: modalZoom 0.3s ease; max-height: 90vh; overflow-y: auto; }
    @keyframes modalZoom { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .close-modal { position: absolute; top: 10px; right: 15px; font-size: 24px; cursor: pointer; color: #64748b; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; z-index: 10; }
</style>

<script>
function openPhotoModal(imgSrc, studentName, courseName, phone, email) {
    document.getElementById('modalImage').src = imgSrc;
    document.getElementById('modalStudentName').innerText = studentName;
    document.getElementById('modalCourseName').innerHTML = '<i class="fa fa-book"></i> ' + courseName;
    document.getElementById('modalStudentPhone').querySelector('span').innerText = phone;
    document.getElementById('modalStudentEmail').querySelector('span').innerText = email;
    document.getElementById('photoModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closePhotoModal() {
    document.getElementById('photoModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") closePhotoModal();
});

function updateCourseStatus(studentId, currentStatus) {
    if(confirm('Are you sure you want to change course status?')) {
        fetch('<?= base_url('center/update-course-status') ?>/' + studentId, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) { location.reload(); } 
            else { alert('Error: ' + data.message); }
        })
        .catch(error => { alert('Something went wrong!'); });
    }
}

function searchTable() {
    let input = document.getElementById("studentSearch").value.toLowerCase();
    let rows = document.querySelectorAll(".student-row");
    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

function filterStudents(status) {
    const rows = document.querySelectorAll('.student-row');
    const buttons = document.querySelectorAll('.filter-tab');
    buttons.forEach(btn => btn.classList.remove('active'));
    document.getElementById('btn-' + status).classList.add('active');
    rows.forEach(row => {
        if (status === 'all') row.style.display = ''; 
        else row.style.display = row.getAttribute('data-status') === status ? '' : 'none'; 
    });
}
</script>

<?= $this->endSection() ?>