<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>
<?php if (!empty($birthday_students)): ?>
    <div class="alert shadow-lg mb-4 alert-dismissible fade show border-0" 
         style="border-radius: 24px; background: linear-gradient(135deg, #ffffff 0%, #fff5f7 100%); padding: 25px; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(255, 77, 109, 0.1) !important;" role="alert">
        
        <div class="floating-emoji" style="position: absolute; right: 10%; top: 10%; opacity: 0.2; font-size: 40px; pointer-events: none;">🎈</div>
        <div class="floating-emoji" style="position: absolute; right: 5%; bottom: 15%; opacity: 0.2; font-size: 30px; pointer-events: none;">✨</div>
        <div style="position: absolute; left: -20px; bottom: -20px; width: 100px; height: 100px; background: rgba(255, 77, 109, 0.05); border-radius: 50%;"></div>

        <div class="d-flex align-items-start align-items-md-center flex-column flex-md-row">
            <div class="birthday-glow me-md-4 mb-3 mb-md-0 d-flex align-items-center justify-content-center" 
                 style="width: 70px; height: 70px; background: #ff4d6d; border-radius: 20px; font-size: 35px; flex-shrink: 0; box-shadow: 0 8px 20px rgba(255, 77, 109, 0.3);">
                <span class="icon-jump">🎂</span>
            </div>

            <div style="z-index: 1; width: 100%;">
                <h4 class="mb-1" style="font-weight: 850; background: linear-gradient(90deg, #ff4d6d, #ff8fa3); -webkit-background-clip: text; -webkit-text-fill-color: transparent; letter-spacing: -1px;">
                    Special Day Celebrations!
                </h4>
                
                <p class="mb-0 text-dark" style="font-size: 15px; font-weight: 600; opacity: 0.8;">
                    <?php 
                        $todayCount = 0;
                        foreach ($birthday_students as $st) {
                            if (date('m-d', strtotime($st['dob'])) == date('m-d')) $todayCount++;
                        }
                    ?>
                    We found <strong><?= count($birthday_students) ?></strong> hero(es) in our upcoming calendar 
                    <?= ($todayCount > 0) ? "<span class='badge bg-danger ms-1' style='font-size: 10px; vertical-align: middle; border-radius: 50px;'>$todayCount TODAY</span>" : "" ?>:
                </p>

                <div class="mt-3 d-flex flex-wrap gap-2">
                    <?php foreach ($birthday_students as $st): 
                        $isToday = (date('m-d', strtotime($st['dob'])) == date('m-d'));
                        $displayDate = date('d M', strtotime($st['dob']));
                        // Direct Redirect Link
                        $targetUrl = base_url('superadmin/all-students?edit_id=' . $st['id']);
                    ?>
                        <a href="<?= $targetUrl ?>" class="text-decoration-none" style="display: contents;">
                            <div class="student-card <?= $isToday ? 'today-active' : '' ?>" 
                                 style="padding: 8px 15px; border-radius: 12px; transition: all 0.3s ease; display: flex; align-items: center; background: <?= $isToday ? '#ff4d6d' : '#ffffff' ?>; border: 1px solid <?= $isToday ? '#ff4d6d' : '#eee' ?>; box-shadow: <?= $isToday ? '0 5px 15px rgba(255, 77, 109, 0.4)' : 'none' ?>;">
                                
                                <div style="font-size: 18px;" class="me-2"><?= $isToday ? '⭐' : '🎁' ?></div>
                                
                                <div>
                                    <div style="font-weight: 800; font-size: 13px; color: <?= $isToday ? '#fff' : '#333' ?>; line-height: 1.1;">
                                        <?= strtoupper($st['student_name']) ?>
                                    </div>
                                    <div style="font-size: 10px; color: <?= $isToday ? 'rgba(255,255,255,0.8)' : '#888' ?>; font-weight: 600;">
                                        <?= $isToday ? 'Happy Birthday!' : 'Coming on '.$displayDate ?> 
                                        <?php if(isset($st['center_name'])): ?>
                                            • <?= $st['center_name'] ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" 
                style="position: absolute; top: 25px; right: 25px; filter: grayscale(1) invert(0); opacity: 0.5;"></button>
    </div>

    <style>
        .icon-jump { display: inline-block; animation: jump 1.5s infinite; }
        @keyframes jump { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

        .floating-emoji { animation: float 3s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0) rotate(15deg); } 50% { transform: translateY(-15px) rotate(25deg); } }

        .birthday-glow { animation: glow-pulse 2s infinite; }
        @keyframes glow-pulse { 0% { box-shadow: 0 0 0 0 rgba(255, 77, 109, 0.4); } 70% { box-shadow: 0 0 0 15px rgba(255, 77, 109, 0); } 100% { box-shadow: 0 0 0 0 rgba(255, 77, 109, 0); } }

        .student-card:hover { transform: translateY(-5px); background: #ff4d6d !important; border-color: #ff4d6d !important; cursor: pointer; box-shadow: 0 10px 20px rgba(255, 77, 109, 0.2); }
        .student-card:hover div { color: #fff !important; }

        .today-active { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both infinite; }
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-2px, 0, 0); } 40%, 60% { transform: translate3d(2px, 0, 0); } }
    </style>
<?php endif; ?>
<!-- Modern Welcome Header with Glassmorphism -->
<div class="row mb-4">
    <div class="col-12">
        <div class="modern-welcome-card">
            <div class="welcome-bg-pattern"></div>
            <div class="d-flex justify-content-between align-items-center position-relative">
                <div>
                    <div class="greeting-badge mb-2">
                        <span class="live-dot"></span>
                        SUPER ADMIN DASHBOARD
                    </div>
                    <h2 class="welcome-title"><span class="text-gradient"> Welcome back, Admin</span> 👋</h2>
                    <p class="welcome-subtitle mb-0">
                        <i class="bi bi-calendar3 me-1"></i> <?= date('l, d F Y') ?> • 
                        <i class="bi bi-activity ms-1 me-1"></i> System is live
                    </p>
                </div>
                <div class="glass-clock">
                    <div class="clock-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <span id="liveClock" class="clock-time"><?= date('h:i A') ?></span>
                        <span class="clock-timezone">IST</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Premium Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Total Centers Card -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="premium-stat-card stat-gradient-blue">
            <div class="stat-overlay"></div>
            <div class="stat-content">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-badge">Total Centers</div>
                        <h2 class="stat-number"><?= number_format($totalCenters) ?></h2>
                        <div class="stat-trend">
                            <i class="bi bi-building me-1"></i>
                            <span>Active Centers</span>
                        </div>
                    </div>
                    <div class="stat-icon-circle">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <a href="<?= base_url('superadmin/centers') ?>" class="stat-action">
                        View All Centers <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Students Card (Unique Students) -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="premium-stat-card stat-gradient-green">
            <div class="stat-overlay"></div>
            <div class="stat-content">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-badge">Total Students</div>
                        <!-- Ab yahan unique students ka count dikhega -->
                        <h2 class="stat-number"><?= number_format($totalStudents) ?></h2>
                        <div class="stat-trend">
                            <i class="bi bi-people me-1"></i>
                            <span>Unique Registered Students</span>
                        </div>
                    </div>
                    <div class="stat-icon-circle">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <a href="<?= base_url('superadmin/all-students') ?>" class="stat-action">
                        Student Directory <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Courses Card -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="premium-stat-card stat-gradient-purple">
            <div class="stat-overlay"></div>
            <div class="stat-content">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-badge">Total Courses</div>
                        <h2 class="stat-number"><?= number_format($totalCourses) ?></h2>
                        <div class="stat-trend">
                            <i class="bi bi-journal-bookmark me-1"></i>
                            <span>Available Programs</span>
                        </div>
                    </div>
                    <div class="stat-icon-circle">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <a href="<?= base_url('superadmin/courses') ?>" class="stat-action">
                        Course Catalog <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Enrollments Card (Total Rows) -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="premium-stat-card stat-gradient-orange">
            <div class="stat-overlay"></div>
            <div class="stat-content">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-badge">Total Enrollments</div>
                        <!-- Yahan total enrollments (113) dikhega -->
                        <h2 class="stat-number"><?= number_format($totalEnrollments) ?></h2>
                        <div class="stat-trend">
                            <i class="bi bi-file-earmark-check me-1"></i>
                            <span>Currently Active</span>
                        </div>
                    </div>
                    <div class="stat-icon-circle">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <a href="<?= base_url('superadmin/enrollments') ?>" class="stat-action">
                        Track Enrollments <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Centers & Quick Stats Row -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">
                            <i class="bi bi-building me-2"></i>
                            Recent Centers
                        </h5>
                        <p class="card-subtitle">Real-time center activity and metrics</p>
                    </div>
                    <div class="header-actions">
                        <span class="live-badge">
                            <span class="live-indicator"></span>
                            LIVE
                        </span>
                    </div>
                </div>
            </div>
            <div class="modern-card-body p-0">
                <div class="centers-grid">
                    <?php foreach ($recentCenters as $center): ?>
                        <?php
                            $logo = (!empty($center['logo']) && file_exists(FCPATH.'assets/images/'.$center['logo'])) 
                                    ? base_url('assets/images/'.$center['logo']) 
                                    : base_url('assets/images/slysis_academy.png');
                            
                            $studentCount = $center['student_count'] ?? 0;
                            $courseCount = $center['course_count'] ?? 0;
                        ?>
                        <div class="center-modern-card" 
                             data-center-id="<?= $center['id'] ?>" 
                             data-center-name="<?= esc($center['center_name']) ?>"
                             data-center-code="<?= esc($center['center_code']) ?>">
                            <div class="center-card-inner">
                                <div class="center-header">
                                    <div class="center-logo-container">
                                        <img src="<?= $logo ?>" alt="<?= esc($center['center_name']) ?>" class="center-logo">
                                    </div>
                                    <div class="center-status">
                                        <span class="status-badge status-active">Active</span>
                                    </div>
                                </div>
                                <div class="center-body">
                                    <h6 class="center-name"><?= esc($center['center_name']) ?></h6>
                                    <span class="center-code">#<?= esc($center['center_code']) ?></span>
                                    <div class="center-metrics mt-3">
                                        <div class="metric-item">
                                            <div class="metric-icon">
                                                <i class="bi bi-people"></i>
                                            </div>
                                            <div class="metric-info">
                                                <span class="metric-label">Students</span>
                                                <span class="metric-value"><?= $studentCount ?></span>
                                            </div>
                                        </div>
                                        <div class="metric-item">
                                            <div class="metric-icon">
                                                <i class="bi bi-book"></i>
                                            </div>
                                            <div class="metric-info">
                                                <span class="metric-label">Courses</span>
                                                <span class="metric-value"><?= $courseCount ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="center-footer">
                                    <button type="button" class="view-btn view-students-btn">
                                        <i class="bi bi-eye me-1"></i>
                                        View Recent 5 Students
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modern-card-footer text-center">
                <a href="<?= base_url('superadmin/centers') ?>" class="view-all-link">
                    View All Centers <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Quick Stats Card -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Quick Stats
                </h5>
            </div>
            <div class="modern-card-body">
                <div class="stats-mini-list">
                    <div class="stats-mini-item">
                        <div class="stats-mini-icon bg-primary-soft">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="stats-mini-content">
                            <span class="stats-mini-label">Active Centers</span>
                            <span class="stats-mini-value"><?= number_format($totalCenters) ?></span>
                        </div>
                    </div>
                    <div class="stats-mini-item">
                        <div class="stats-mini-icon bg-success-soft">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stats-mini-content">
                            <span class="stats-mini-label">Total Students</span>
                            <span class="stats-mini-value"><?= number_format($totalStudents) ?></span>
                        </div>
                    </div>
                    <div class="stats-mini-item">
                        <div class="stats-mini-icon bg-warning-soft">
                            <i class="bi bi-journal-bookmark"></i>
                        </div>
                        <div class="stats-mini-content">
                            <span class="stats-mini-label">Total Courses</span>
                            <span class="stats-mini-value"><?= number_format($totalCourses) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== FIXED: STUDENTS MODAL - NO ACTION FIELD, VIEW BUTTON REMOVED ===== -->
<div class="modal fade" id="studentsModal" tabindex="-1" aria-labelledby="studentsModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentsModalLabel">
                    <i class="bi bi-people me-2"></i>
                    <span id="modalCenterName"></span> 
                    <span id="modalCenterCode" class="badge bg-light text-dark ms-2"></span>
                    <span class="badge bg-primary ms-2">Recent 5 Students</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table modern-table align-middle">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Student Name</th>
                                <th width="20%">Email</th>
                                <th width="15%">Phone</th>
                                <th width="15%">Enrollment No</th>
                                <th width="20%">Join Date</th>
                                <!-- ✅ ACTION FIELD COMPLETELY REMOVED -->
                            </tr>
                        </thead>
                        <tbody id="modalStudentsTableBody">
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-container">
                                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted fs-5">Loading recent 5 students...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>
                    Close
                </button>
                <a href="#" class="btn btn-primary" id="viewAllCenterStudentsBtn">
                    <i class="bi bi-box-arrow-up-right me-1"></i>
                    View All Students
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Enrollments Card -->
<div class="modern-card mt-4">
    <div class="modern-card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title">
                    <i class="bi bi-file-earmark-check me-2"></i>
                    Recent Enrollments
                </h5>
                <p class="card-subtitle">Latest student course registrations</p>
            </div>
            <a href="<?= base_url('superadmin/enrollments') ?>" class="btn btn-sm btn-outline-primary">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    <div class="modern-card-body p-0">
        <div class="table-responsive">
            <table class="table modern-table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 25%">Student</th>
                        <th style="width: 25%">Course</th>
                        <th style="width: 25%">Center</th>
                        <th style="width: 15%">Enroll Date</th>
                        <th style="width: 10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($enrollments)): ?>
                        <?php foreach(array_slice($enrollments, 0, 5) as $enroll): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar">
                                            <span><?= substr($enroll['student_name'] ?? 'S', 0, 1) ?></span>
                                        </div>
                                        <div>
                                            <span class="fw-600"><?= esc($enroll['student_name'] ?? 'N/A') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="course-badge"><?= esc($enroll['course_name'] ?? 'N/A') ?></span>
                                </td>
                                <td>
                                    <span class="center-code-badge"><?= esc($enroll['center_name'] ?? 'N/A') ?></span>
                                </td>
                                <td>
                                    <span class="date-text">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?= date('d M Y', strtotime($enroll['enroll_date'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?= ($enroll['status'] == 'active') ? 'status-success' : 'status-secondary' ?>">
                                        <?= ucfirst($enroll['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-file-earmark-check display-4 text-muted"></i>
                                    <p class="mt-2 text-muted">No recent enrollments found</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== FIXED: CUSTOM CSS ===== -->
<style>
:root {
    --primary: #4361ee;
    --primary-dark: #3a56d4;
    --secondary: #6c757d;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;
    --dark: #1e293b;
    --light: #f8fafc;
}

/* Modern Welcome Card */
.modern-welcome-card {
    background: linear-gradient(145deg, #1a2639, #0f172a);
    padding: 30px;
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    color: white;
    box-shadow: 0 20px 30px -10px rgba(0,0,0,0.15);
}

.welcome-bg-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: radial-gradient(circle at 30% 50%, rgba(67, 97, 238, 0.2) 0%, transparent 50%),
                      radial-gradient(circle at 70% 80%, rgba(130, 71, 229, 0.2) 0%, transparent 50%);
    pointer-events: none;
}

.greeting-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    border: 1px solid rgba(255,255,255,0.1);
}

.live-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    margin-right: 8px;
    animation: pulse 2s infinite;
}

.welcome-title {
    font-size: 28px;
    font-weight: 700;
    margin: 12px 0 8px;
}

.text-gradient {
    background: linear-gradient(135deg, #60a5fa, #c084fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.welcome-subtitle {
    color: rgba(255,255,255,0.7);
    font-size: 14px;
}

.glass-clock {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    padding: 12px 20px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
    border: 1px solid rgba(255,255,255,0.1);
}

.clock-icon {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.clock-time {
    font-size: 22px;
    font-weight: 700;
    margin-right: 8px;
}

.clock-timezone {
    font-size: 12px;
    opacity: 0.7;
    font-weight: 600;
}

/* Premium Stat Cards */
.premium-stat-card {
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
}

.premium-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 30px -10px rgba(0,0,0,0.2);
}

.stat-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
    pointer-events: none;
}

.stat-gradient-blue { background: linear-gradient(145deg, #2563eb, #1d4ed8); }
.stat-gradient-green { background: linear-gradient(145deg, #059669, #047857); }
.stat-gradient-purple { background: linear-gradient(145deg, #7c3aed, #6d28d9); }
.stat-gradient-orange { background: linear-gradient(145deg, #ea580c, #c2410c); }

.stat-content {
    padding: 24px;
    position: relative;
    z-index: 1;
    color: white;
}

.stat-badge {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
    opacity: 0.9;
}

.stat-number {
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 8px;
    line-height: 1;
}

.stat-trend {
    display: flex;
    align-items: center;
    font-size: 13px;
    opacity: 0.9;
}

.stat-icon-circle {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    backdrop-filter: blur(5px);
}

.stat-footer {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid rgba(255,255,255,0.2);
}

.stat-action {
    color: white;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: 0.3s;
}

.stat-action:hover {
    opacity: 0.9;
    color: white;
    transform: translateX(5px);
}

/* Modern Card */
.modern-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    border: 1px solid #f1f5f9;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
}

.modern-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    border-color: #e2e8f0;
}

.modern-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    background: white;
}

.card-title {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 4px;
}

.card-subtitle {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 0;
}

.modern-card-body {
    padding: 20px;
}

.modern-card-footer {
    padding: 16px 24px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
}

/* Centers Grid */
.centers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    padding: 20px;
}

.center-modern-card {
    cursor: pointer;
}

.center-card-inner {
    background: white;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 20px;
    transition: all 0.3s ease;
}

.center-card-inner:hover {
    border-color: var(--primary);
    box-shadow: 0 10px 25px -5px rgba(67, 97, 238, 0.1);
    transform: translateY(-2px);
}

.center-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.center-logo-container {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #f1f5f9;
    padding: 8px;
    background: white;
}

.center-logo {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
}

.status-active {
    background: #e6f7e6;
    color: #0b6e4f;
}

.center-name {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 4px;
}

.center-code {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
}

.center-metrics {
    display: flex;
    gap: 20px;
    background: #f8fafc;
    padding: 12px;
    border-radius: 12px;
}

.metric-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.metric-icon {
    width: 32px;
    height: 32px;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
}

.metric-info {
    display: flex;
    flex-direction: column;
}

.metric-label {
    font-size: 11px;
    color: #64748b;
}

.metric-value {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}

.center-footer {
    margin-top: 16px;
}

.view-btn {
    width: 100%;
    padding: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    color: #334155;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.view-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Stats Mini List */
.stats-mini-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.stats-mini-item {
    display: flex;
    align-items: center;
    padding: 10px;
    background: #f8fafc;
    border-radius: 12px;
}

.stats-mini-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 20px;
}

.bg-primary-soft { background: #e6edfd; color: #2563eb; }
.bg-success-soft { background: #e6f7e6; color: #059669; }
.bg-warning-soft { background: #fef3c7; color: #d97706; }

.stats-mini-content {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stats-mini-label {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
}

.stats-mini-value {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
}

/* Modern Table */
.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead th {
    background: #f8fafc;
    padding: 16px 24px;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
    white-space: nowrap;
}

.modern-table tbody td {
    padding: 16px 24px;
    font-size: 14px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    vertical-align: middle;
}

.modern-table tbody tr:hover {
    background: #f8fafc;
}

/* Student Avatar */
.student-avatar {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    margin-right: 12px;
    flex-shrink: 0;
}

/* Student Avatar Small - For Modal */
.student-avatar-sm {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #4361ee, #3a56d4);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
    margin-right: 10px;
}

/* Badges */
.course-badge, .center-code-badge {
    background: #f1f5f9;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    display: inline-block;
    white-space: nowrap;
}

/* Status Badges */
.status-badge {
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    white-space: nowrap;
}

.status-success {
    background: #e6f7e6;
    color: #059669;
}

.status-secondary {
    background: #f1f5f9;
    color: #64748b;
}

/* Date Text */
.date-text {
    font-size: 13px;
    color: #475569;
    white-space: nowrap;
}

/* Live Badge */
.live-badge {
    background: #f1f5f9;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.live-indicator {
    display: inline-block;
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 30px;
}

.empty-state i {
    font-size: 48px;
    color: #cbd5e1;
    margin-bottom: 12px;
}

/* View All Link */
.view-all-link {
    color: var(--primary);
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: 0.3s;
}

.view-all-link:hover {
    color: var(--primary-dark);
    transform: translateX(5px);
}

/* Modal - FIXED */
.modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    background: white;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
}

.modal-body {
    max-height: 500px;
    overflow-y: auto;
    padding: 24px;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    background: #f8fafc;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

.btn-close {
    background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
    opacity: 0.5;
}

.btn-close:hover {
    opacity: 0.75;
}

/* Badge Primary */
.badge.bg-primary {
    background: var(--primary) !important;
    color: white;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
}

/* Badge Light */
.badge.bg-light {
    background: #f1f5f9 !important;
    color: #334155 !important;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
}

/* Badge Secondary Subtle */
.badge.bg-secondary-subtle {
    background: #f1f5f9 !important;
    color: #475569 !important;
    font-weight: 500;
    padding: 5px 12px;
    border-radius: 50px;
    display: inline-block;
}

/* Spinner Container */
.spinner-container {
    padding: 40px;
    text-align: center;
}

/* Fw-600 */
.fw-600 {
    font-weight: 600;
}

/* Animations */
@keyframes pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
    100% { opacity: 1; transform: scale(1); }
}

/* Page Layout Fixes */
.row {
    margin-left: 0;
    margin-right: 0;
}

.col-lg-8, .col-lg-4 {
    padding-left: 12px;
    padding-right: 12px;
}

/* Fix for long content */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-title { font-size: 22px; }
    .glass-clock { padding: 10px 15px; }
    .clock-time { font-size: 18px; }
    .stat-number { font-size: 28px; }
    .centers-grid { grid-template-columns: 1fr; }
    .modern-table thead th {
        padding: 12px 16px;
        font-size: 11px;
    }
    .modern-table tbody td {
        padding: 12px 16px;
        font-size: 13px;
    }
}

/* Ensure consistent spacing */
.mb-4 {
    margin-bottom: 1.5rem !important;
}

.mt-4 {
    margin-top: 1.5rem !important;
}
</style>

<!-- ===== FIXED: JAVASCRIPT - NO ACTION FIELD, VIEW BUTTON REMOVED ===== -->
<script>
// Live Clock
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
    });
    document.getElementById('liveClock').textContent = timeString;
}
setInterval(updateClock, 1000);

// ===== RECENT 5 STUDENTS MODAL - NO ACTION FIELD =====
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    console.log('DOM fully loaded');
    
    // Get modal element
    const modalElement = document.getElementById('studentsModal');
    
    if (!modalElement) {
        console.error('Modal element not found!');
        return;
    }
    
    // Initialize Bootstrap Modal with proper options
    let studentsModal;
    try {
        studentsModal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        console.log('Modal initialized successfully');
    } catch (error) {
        console.error('Error initializing modal:', error);
        return;
    }
    
    // Get modal elements
    const modalCenterName = document.getElementById('modalCenterName');
    const modalCenterCode = document.getElementById('modalCenterCode');
    const modalTableBody = document.getElementById('modalStudentsTableBody');
    const viewAllBtn = document.getElementById('viewAllCenterStudentsBtn');
    
    if (!modalCenterName || !modalCenterCode || !modalTableBody || !viewAllBtn) {
        console.error('Modal elements not found!');
        return;
    }
    
    // Get all View Students buttons
    const viewButtons = document.querySelectorAll('.view-students-btn');
    console.log('Found', viewButtons.length, 'View Students buttons');
    
    if (viewButtons.length === 0) {
        console.warn('No .view-students-btn buttons found!');
    }
    
    // Attach click event to each button
    viewButtons.forEach((button, index) => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            console.log('Button', index + 1, 'clicked');
            
            // Get parent center card
            const centerCard = this.closest('.center-modern-card');
            if (!centerCard) {
                console.error('Center card not found!');
                alert('Error: Center card not found!');
                return;
            }
            
            // Get center data from data attributes
            const centerId = centerCard.dataset.centerId;
            const centerName = centerCard.dataset.centerName;
            const centerCode = centerCard.dataset.centerCode;
            
            console.log('Center ID:', centerId);
            console.log('Center Name:', centerName);
            console.log('Center Code:', centerCode);
            
            if (!centerId) {
                console.error('Center ID not found!');
                alert('Error: Center ID not found!');
                return;
            }
            
            // Set modal title and center info
            modalCenterName.textContent = centerName || 'Center';
            modalCenterCode.textContent = centerCode ? '#' + centerCode : '';
            
            // Set View All button link
            viewAllBtn.href = `<?= base_url('superadmin/all-students') ?>?center_id=${centerId}`;
            
            // Show loading spinner
            modalTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="spinner-container">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted fs-5">Loading recent 5 students...</p>
                        </div>
                    </td>
                </tr>
            `;
            
            // Show the modal
            try {
                studentsModal.show();
                console.log('Modal shown');
            } catch (error) {
                console.error('Error showing modal:', error);
            }
            
            // Fetch recent 5 students from server
            const baseUrl = '<?= base_url() ?>';
            const fetchUrl = baseUrl + '/superadmin/get-center-students/' + centerId;
            console.log('Fetching URL:', fetchUrl);
            
            fetch(fetchUrl)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Students data received:', data);
                    
                    if (data && Array.isArray(data) && data.length > 0) {
                        let html = '';
                        
                        data.forEach((student, index) => {
                            // Format join date
                            let joinDate = student.created_at || 'N/A';
                            if (joinDate !== 'N/A' && joinDate.includes('-')) {
                                try {
                                    const date = new Date(joinDate);
                                    joinDate = date.toLocaleDateString('en-IN', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    });
                                } catch(e) {
                                    console.warn('Date parsing error:', e);
                                }
                            }
                            
                            // Get first character of student name for avatar
                            let nameChar = 'S';
                            if (student.student_name && typeof student.student_name === 'string' && student.student_name.length > 0) {
                                nameChar = student.student_name.charAt(0).toUpperCase();
                            }
                            
                            html += `
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark px-3 py-2">
                                            ${index + 1}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="student-avatar-sm">
                                                <span>${nameChar}</span>
                                            </div>
                                            <span class="fw-600">${student.student_name || 'N/A'}</span>
                                        </div>
                                    </td>
                                    <td>${student.email || 'N/A'}</td>
                                    <td>${student.phone || 'N/A'}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                            ${student.enrollment_no || 'N/A'}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="date-text">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            ${joinDate}
                                        </span>
                                    </td>
                                    <!-- ✅ ACTION FIELD COMPLETELY REMOVED -->
                                </tr>
                            `;
                        });
                        
                        modalTableBody.innerHTML = html;
                    } else {
                        modalTableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-people display-1 text-muted"></i>
                                        <p class="mt-3 fs-5 text-muted">No students found in this center</p>
                                        <p class="text-muted small">Add students to see them here</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    modalTableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-5 text-danger">
                                <i class="bi bi-exclamation-triangle display-1"></i>
                                <p class="mt-3 fs-5">Error loading students</p>
                                <p class="small text-muted">${error.message}</p>
                                <button class="btn btn-outline-primary mt-3" onclick="location.reload()">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    Retry
                                </button>
                            </td>
                        </tr>
                    `;
                });
        });
    });
    
    // Center card click - trigger View Students button
    document.querySelectorAll('.center-modern-card').forEach(card => {
        card.addEventListener('click', function(event) {
            // Don't trigger if clicking on button itself
            if (!event.target.closest('.view-students-btn')) {
                const viewBtn = this.querySelector('.view-students-btn');
                if (viewBtn) {
                    viewBtn.click();
                }
            }
        });
    });
    
    // Manual close button handler (fallback)
    const closeButtons = document.querySelectorAll('[data-bs-dismiss="modal"]');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            try {
                studentsModal.hide();
            } catch (error) {
                console.error('Error hiding modal:', error);
                // Fallback: manually hide modal
                const modal = document.getElementById('studentsModal');
                if (modal) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                }
            }
        });
    });
    
    console.log('Recent 5 Students modal initialized successfully!');
});
</script>

<!-- Include Bootstrap JS (if not already included in layout) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->endSection() ?>