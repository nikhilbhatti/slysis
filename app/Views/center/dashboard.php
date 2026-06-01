<?= $this->extend('center/layout/pages-layout') ?>
<?= $this->section('content') ?>

<!-- Modern Welcome Header with Glassmorphism - Center Theme -->
 <?php if (!empty($birthday_students)): ?>
    <div class="alert shadow-sm d-flex align-items-center mb-4" 
         style="border-radius: 16px; background: #ffffff; border: 1px solid #ffe4e9; padding: 25px; position: relative; overflow: hidden; border-left: 5px solid #ff4d6d;">
        
        <div style="position: absolute; right: -15px; top: -15px; opacity: 0.07; font-size: 100px; transform: rotate(15deg); pointer-events: none;">🎉</div>

        <div class="birthday-icon-pulse me-4 d-flex align-items-center justify-content-center flex-shrink-0" 
             style="width: 70px; height: 70px; background: #fff1f3; border-radius: 15px; font-size: 35px; border: 1px solid #ffccd5;">
            🎂
        </div>

        <div style="z-index: 1;">
            <h5 class="mb-1" style="font-weight: 800; color: #ff4d6d; letter-spacing: -0.5px; font-family: 'Segoe UI', Roboto, sans-serif;">
                Special Day Celebrations!
            </h5>
            <p class="mb-0 text-secondary" style="font-size: 15px; font-weight: 500;">
                Today we are celebrating the birthday of 
                <span style="color: #ff4d6d; font-weight: 700;"><?= count($birthday_students) ?></span> 
                remarkable student<?= count($birthday_students) > 1 ? 's' : '' ?>:
                
                <div class="mt-2 d-flex flex-wrap gap-2">
                    <?php foreach ($birthday_students as $st): ?>
                        <span class="badge border-0 px-3 py-2" 
                              style="background: #fff1f3; color: #ff4d6d; border-radius: 10px; font-weight: 700; font-size: 13px; box-shadow: 0 2px 4px rgba(255, 77, 109, 0.1);">
                            🎈 <?= strtoupper(esc($st['student_name'])) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </p>
        </div>
    </div>

    <style>
        .birthday-icon-pulse {
            animation: pulse-pink 2s infinite;
        }
        @keyframes pulse-pink {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 77, 109, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(255, 77, 109, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 77, 109, 0); }
        }
        .gap-2 { gap: 0.5rem !important; }
    </style>
<?php endif; ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="modern-welcome-card">
            <div class="welcome-bg-pattern"></div>
            <div class="d-flex justify-content-between align-items-center position-relative">
                <div>
                    <div class="greeting-badge mb-2">
                        <span class="live-dot"></span>
                        CENTER DASHBOARD
                    </div>
                    <h2 class="welcome-title">
                      <span class="text-gradient">Welcome, <?= esc($center_name) ?></span> 👋
                    </h2>
                    <p class="welcome-subtitle mb-0">
                        <i class="bi bi-building me-1"></i> Code: <?= esc(session()->get('center_code') ?? 'N/A') ?> • 
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

<!-- Admin Messages Section - Premium Design -->
<?php if (!empty($adminMessages)): ?>
    <?php 
        $latestMsg = $adminMessages[0]; 
        $totalMsgs = count($adminMessages);
    ?>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="premium-message-card <?= ($latestMsg['status'] == 'unread') ? 'msg-unread' : 'msg-read' ?>">
                <div class="message-overlay"></div>
                <div class="message-content-wrapper">
                    <div class="message-icon-container">
                        <?php if($latestMsg['status'] == 'unread'): ?>
                            <i class="bi bi-megaphone-fill"></i>
                        <?php else: ?>
                            <i class="bi bi-check-circle-fill"></i>
                        <?php endif; ?>
                    </div>
                    
                    <div class="message-details">
                        <div class="message-header">
                            <div class="message-title-group">
                                <span class="message-badge <?= ($latestMsg['status'] == 'unread') ? 'badge-gradient-danger' : 'badge-gradient-success' ?>">
                                    <?= ($latestMsg['status'] == 'unread') ? '🔔 LATEST NOTICE' : '✓ ACKNOWLEDGED' ?>
                                    <a href="<?= base_url('center/notifications') ?>" class="btn btn-sm btn-outline-primary">View All History</a>
                                </span>
                                <span class="message-time">
                                    <i class="bi bi-clock-history"></i>
                                    <?= date('d M, h:i A', strtotime($latestMsg['created_at'])) ?>
                                </span>
                            </div>
                            
                            <div class="message-actions-group">
                                <?php if($totalMsgs > 1): ?>
                                    <button type="button" class="premium-btn-outline" data-bs-toggle="modal" data-bs-target="#allNoticesModal">
                                        <i class="bi bi-bell"></i>
                                        View All (<?= $totalMsgs ?>)
                                    </button>
                                <?php endif; ?>
                                
                                <a href="<?= base_url('center/deleteMsg/' . $latestMsg['id']) ?>" 
                                   class="premium-btn-icon" 
                                   onclick="return confirm('Delete this notice?');">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            </div>
                        </div>
                        
                        <p class="message-text"><?= esc($latestMsg['message']) ?></p>
                        
                        <div class="message-footer">
                            <span class="message-expiry">
                                <i class="bi bi-hourglass-split"></i>
                                Auto-removes: <?= date('d M, h:i A', strtotime($latestMsg['expires_at'])) ?>
                            </span>
                            
                            <?php if($latestMsg['status'] == 'unread'): ?>
                                <a href="<?= base_url('center/markMsgRead/' . $latestMsg['id']) ?>" class="premium-btn-mark">
                                    <i class="bi bi-check2-circle"></i>
                                    Mark as Read
                                </a>
                            <?php else: ?>
                                <span class="read-confirmation">
                                    <i class="bi bi-patch-check-fill"></i>
                                    Acknowledged
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Premium Stats Cards - Center Theme -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <a href="<?= base_url('center/students') ?>" class="premium-stat-card stat-gradient-green">
            <div class="stat-overlay"></div>
            <div class="stat-content">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-badge">Total Students</div>
                        <h2 class="stat-number"><?= number_format($totalStudents ?? 0) ?></h2>
                        <div class="stat-trend">
                            <i class="bi bi-people me-1"></i>
                            <span>Enrolled Students</span>
                        </div>
                    </div>
                    <div class="stat-icon-circle">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="stat-action">
                        View All Students <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <a href="<?= base_url('center/completed') ?>" class="premium-stat-card stat-gradient-emerald">
            <div class="stat-overlay"></div>
            <div class="stat-content">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-badge">Completed</div>
                        <h2 class="stat-number"><?= number_format($completedCount ?? 0) ?></h2>
                        <div class="stat-trend">
                            <i class="bi bi-check-circle me-1"></i>
                            <span>Course Completed</span>
                        </div>
                    </div>
                    <div class="stat-icon-circle">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="stat-action">
                        View Completed <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <a href="<?= base_url('center/in-progress') ?>" class="premium-stat-card stat-gradient-orange">
            <div class="stat-overlay"></div>
            <div class="stat-content">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-badge">In Progress</div>
                        <h2 class="stat-number"><?= number_format($pendingCount ?? 0) ?></h2>
                        <div class="stat-trend">
                            <i class="bi bi-hourglass me-1"></i>
                            <span>Active Learning</span>
                        </div>
                    </div>
                    <div class="stat-icon-circle">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="stat-action">
                        View In Progress <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <a href="<?= base_url('center/my-courses') ?>" class="premium-stat-card stat-gradient-blue">
            <div class="stat-overlay"></div>
            <div class="stat-content">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-badge">Active Courses</div>
                        <h2 class="stat-number"><?= number_format($totalCourses ?? 0) ?></h2>
                        <div class="stat-trend">
                            <i class="bi bi-journal-bookmark me-1"></i>
                            <span>Available Programs</span>
                        </div>
                    </div>
                    <div class="stat-icon-circle">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="stat-action">
                        View Courses <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- ✅ FINAL FIXED: Recent Students Card - 100% WORKING -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">
                            <i class="bi bi-people-fill me-2" style="color: #10b981;"></i>
                            Recent Students
                        </h5>
                        <p class="card-subtitle">Latest 5 enrolled students</p>
                    </div>
                    <a href="<?= base_url('center/students') ?>" class="btn-premium-small">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="modern-card-body p-0">
                <div class="table-responsive">
                    <table class="table premium-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="30%">Student</th>
                                <th width="25%">Course</th>
                                <th width="20%">Enroll Date</th>
                                <th width="15%">Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // ✅ FIX: Use $recent_students variable (from controller)
                            if(!empty($recent_students) && is_array($recent_students)): 
                                foreach($recent_students as $student): 
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="student-avatar-premium" style="background: linear-gradient(135deg, #10b981, #0b8a5c);">
                                                <?php if(!empty($student['photo'])): ?>
                                                    <img src="<?= base_url('uploads/students/'.$student['photo']) ?>" 
                                                         alt="student photo" 
                                                         style="width: 42px; height: 42px; border-radius: 12px; object-fit: cover;">
                                                <?php else: ?>
                                                    <span><?= substr($student['student_name'] ?? 'S', 0, 1) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <span class="fw-600"><?= esc($student['student_name'] ?? 'N/A') ?></span>
                                                <span class="d-block text-muted" style="font-size: 11px;">
                                                    <?= !empty($student['enrollment_no']) ? '#'.esc($student['enrollment_no']) : '' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="course-badge-premium">
                                            <?= esc($student['course_name'] ?? 'Not Assigned') ?>
                                        </span>
                                        <?php if(!empty($student['course_duration'])): ?>
                                            <br><small class="text-muted"><?= esc($student['course_duration']) ?> months</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="date-text-premium">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            <?= !empty($student['enroll_date']) ? date('d M Y', strtotime($student['enroll_date'])) : 'N/A' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $status = $student['course_status'] ?? 'pending';
                                        if($status == 'completed'): ?>
                                            <span class="status-badge-premium status-completed-premium">Completed</span>
                                        <?php else: ?>
                                            <span class="status-badge-premium status-pending-premium">In Progress</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('center/edit-student/' . $student['id']) ?>" 
                                           class="action-btn-premium" 
                                           title="View Student Details">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php 
                                endforeach; 
                            else: 
                            ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state-premium">
                                            <i class="bi bi-people" style="font-size: 48px;"></i>
                                            <p class="mt-3 fw-bold">No recent students found</p>
                                            <p class="text-muted small">Students will appear here once they enroll</p>
                                            <a href="<?= base_url('center/add-student') ?>" class="btn-premium-small mt-3">
                                                <i class="bi bi-plus-circle me-1"></i>
                                                Add New Student
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modern-card-footer text-center">
                <a href="<?= base_url('center/students') ?>" class="view-all-link" style="color: #10b981;">
                    <i class="bi bi-grid-3x3-gap-fill me-1"></i>
                    View All Students (<?= number_format($totalStudents ?? 0) ?>)
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- All Notices Modal - Premium Design -->
<div class="modal fade premium-modal" id="allNoticesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-bell-fill me-2" style="color: #10b981;"></i>
                    All Notifications (<?= count($adminMessages ?? []) ?>)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-0 py-0">
                <div class="notifications-premium-list">
                    <?php if(!empty($adminMessages)): ?>
                        <?php foreach ($adminMessages as $msg): ?>
                            <div class="notification-premium-item <?= ($msg['status'] == 'unread') ? 'notification-unread-premium' : '' ?>">
                                <div class="notification-premium-icon">
                                    <?php if($msg['status'] == 'unread'): ?>
                                        <i class="bi bi-envelope-fill" style="color: #10b981;"></i>
                                    <?php else: ?>
                                        <i class="bi bi-envelope-open-fill" style="color: #94a3b8;"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="notification-premium-content">
                                    <p class="notification-premium-text"><?= esc($msg['message']) ?></p>
                                    <div class="notification-premium-meta">
                                        <span class="notification-premium-time">
                                            <i class="bi bi-clock"></i>
                                            <?= date('d M Y, h:i A', strtotime($msg['created_at'])) ?>
                                        </span>
                                        <span class="notification-premium-expiry">
                                            <i class="bi bi-hourglass-split"></i>
                                            Expires: <?= date('d M Y, h:i A', strtotime($msg['expires_at'])) ?>
                                        </span>
                                        <div class="notification-premium-actions">
                                            <?php if($msg['status'] == 'unread'): ?>
                                                <a href="<?= base_url('center/markMsgRead/' . $msg['id']) ?>" class="notification-premium-btn mark-read">
                                                    <i class="bi bi-check2"></i>
                                                    Mark Read
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('center/deleteMsg/' . $msg['id']) ?>" 
                                               class="notification-premium-btn delete" 
                                               onclick="return confirm('Delete this notification?');">
                                                <i class="bi bi-trash"></i>
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-bell-slash" style="font-size: 48px; color: #cbd5e1;"></i>
                            <p class="mt-3 text-muted">No notifications</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-premium-outline" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== PREMIUM CENTER DASHBOARD STYLES ===== */
:root {
    --center-primary: #10b981;
    --center-primary-dark: #0b8a5c;
    --center-primary-light: #d1fae5;
    --center-primary-soft: #e6f7e6;
    --center-gradient-start: #10b981;
    --center-gradient-end: #059669;
    --dark: #0f172a;
    --light: #f8fafc;
    --border: #f1f5f9;
    --card-shadow: 0 4px 20px rgba(0,0,0,0.02);
    --hover-shadow: 0 20px 30px -10px rgba(16, 185, 129, 0.15);
}

/* Modern Welcome Card - Center Theme */
.modern-welcome-card {
    background: linear-gradient(145deg, #0f172a, #1e293b);
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
    background-image: radial-gradient(circle at 30% 50%, rgba(16, 185, 129, 0.15) 0%, transparent 50%),
                      radial-gradient(circle at 70% 80%, rgba(5, 150, 105, 0.15) 0%, transparent 50%);
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
    background: var(--center-primary);
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

/* Premium Message Card */
.premium-message-card {
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: white;
    border: 1px solid var(--border);
}

.premium-message-card:hover {
    box-shadow: var(--hover-shadow);
    border-color: transparent;
    transform: translateY(-2px);
}

.message-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(16, 185, 129, 0.02) 0%, transparent 100%);
    pointer-events: none;
}

.msg-unread {
    border-left: 6px solid #ef4444;
}

.msg-read {
    border-left: 6px solid var(--center-primary);
}

.message-content-wrapper {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 24px;
}

.message-icon-container {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.05));
    color: var(--center-primary);
    flex-shrink: 0;
}

.msg-unread .message-icon-container {
    color: #ef4444;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.05));
}

.message-details {
    flex: 1;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.message-title-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.message-badge {
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: white;
}

.badge-gradient-danger {
    background: linear-gradient(145deg, #ef4444, #dc2626);
}

.badge-gradient-success {
    background: linear-gradient(145deg, var(--center-primary), var(--center-primary-dark));
}

.message-time {
    font-size: 12px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}

.message-actions-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.premium-btn-outline {
    background: white;
    border: 1px solid var(--center-primary);
    color: var(--center-primary);
    padding: 8px 16px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    text-decoration: none;
}

.premium-btn-outline:hover {
    background: var(--center-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -10px var(--center-primary);
}

.premium-btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    color: #64748b;
    transition: all 0.3s;
    text-decoration: none;
}

.premium-btn-icon:hover {
    background: #fee2e2;
    color: #ef4444;
}

.message-text {
    font-size: 16px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 16px;
    line-height: 1.6;
}

.message-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.message-expiry {
    font-size: 12px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}

.premium-btn-mark {
    background: linear-gradient(145deg, var(--center-primary), var(--center-primary-dark));
    color: white;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.premium-btn-mark:hover {
    background: linear-gradient(145deg, var(--center-primary-dark), #0a7a4f);
    transform: translateX(5px);
    color: white;
}

.read-confirmation {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--center-primary);
    font-size: 13px;
    font-weight: 600;
}

/* Premium Stat Cards - Center Theme */
.premium-stat-card {
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: block;
    text-decoration: none;
}

.premium-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 30px -10px rgba(16, 185, 129, 0.3);
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

.stat-gradient-green { background: linear-gradient(145deg, #10b981, #0b8a5c); }
.stat-gradient-emerald { background: linear-gradient(145deg, #059669, #047857); }
.stat-gradient-orange { background: linear-gradient(145deg, #f59e0b, #d97706); }
.stat-gradient-blue { background: linear-gradient(145deg, #3b82f6, #2563eb); }

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
    transform: translateX(5px);
}

/* Modern Card */
.modern-card {
    background: white;
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    border: 1px solid var(--border);
    overflow: hidden;
    transition: all 0.3s ease;
}

.modern-card:hover {
    box-shadow: var(--hover-shadow);
    border-color: transparent;
}

.modern-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    background: white;
}

.card-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 4px;
}

.card-subtitle {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 0;
}

.modern-card-body {
    padding: 0;
}

.modern-card-footer {
    padding: 16px 24px;
    background: #f8fafc;
    border-top: 1px solid var(--border);
}

/* Premium Table */
.premium-table {
    width: 100%;
    border-collapse: collapse;
}

.premium-table thead th {
    background: #f8fafc;
    padding: 16px 24px;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--border);
}

.premium-table tbody td {
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}

.premium-table tbody tr:hover {
    background: #f8fafc;
}

/* Student Avatar Premium */
.student-avatar-premium {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    margin-right: 14px;
    flex-shrink: 0;
    font-size: 16px;
    box-shadow: 0 6px 12px rgba(16, 185, 129, 0.2);
    overflow: hidden;
}

/* Course Badge Premium */
.course-badge-premium {
    background: var(--center-primary-soft);
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    color: var(--center-primary-dark);
    display: inline-block;
    white-space: nowrap;
}

/* Date Text Premium */
.date-text-premium {
    font-size: 13px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Status Badge Premium */
.status-badge-premium {
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.status-completed-premium {
    background: #e6f7e6;
    color: #059669;
}

.status-pending-premium {
    background: #fff3cd;
    color: #856404;
}

/* Action Button Premium */
.action-btn-premium {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    background: #f8fafc;
    transition: all 0.3s;
    text-decoration: none;
}

.action-btn-premium:hover {
    background: var(--center-primary);
    color: white;
    transform: scale(1.1);
}

/* Button Premium Small */
.btn-premium-small {
    background: linear-gradient(145deg, var(--center-primary), var(--center-primary-dark));
    color: white;
    padding: 8px 18px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s;
}

.btn-premium-small:hover {
    background: linear-gradient(145deg, var(--center-primary-dark), #0a7a4f);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -10px var(--center-primary);
    color: white;
}

/* View All Link */
.view-all-link {
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: 0.3s;
}

.view-all-link:hover {
    transform: translateX(5px);
}

/* Empty State Premium */
.empty-state-premium {
    text-align: center;
    padding: 40px;
}

.empty-state-premium i {
    font-size: 48px;
    color: #cbd5e1;
    margin-bottom: 16px;
}

.empty-state-premium p {
    color: #64748b;
    font-size: 14px;
    margin: 0;
}

/* Premium Modal */
.premium-modal .modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
}

.premium-modal .modal-header {
    background: white;
    border-bottom: 1px solid var(--border);
    padding: 20px 24px;
}

.premium-modal .modal-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
}

.premium-modal .modal-footer {
    background: #f8fafc;
    border-top: 1px solid var(--border);
    padding: 16px 24px;
}

/* Notifications Premium List */
.notifications-premium-list {
    max-height: 450px;
    overflow-y: auto;
}

.notification-premium-item {
    display: flex;
    gap: 16px;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    transition: all 0.3s;
}

.notification-premium-item:hover {
    background: #f8fafc;
}

.notification-unread-premium {
    background: linear-gradient(to right, rgba(16, 185, 129, 0.02), white);
    border-left: 4px solid var(--center-primary);
}

.notification-premium-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    background: #f8fafc;
    flex-shrink: 0;
}

.notification-premium-content {
    flex: 1;
}

.notification-premium-text {
    font-size: 14px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 8px;
    line-height: 1.5;
}

.notification-premium-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.notification-premium-time,
.notification-premium-expiry {
    font-size: 12px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 4px;
}

.notification-premium-actions {
    display: flex;
    gap: 12px;
}

.notification-premium-btn {
    font-size: 11px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.3s;
}

.notification-premium-btn.mark-read {
    background: rgba(16, 185, 129, 0.1);
    color: var(--center-primary);
}

.notification-premium-btn.mark-read:hover {
    background: var(--center-primary);
    color: white;
}

.notification-premium-btn.delete {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.notification-premium-btn.delete:hover {
    background: #ef4444;
    color: white;
}

/* Button Premium Outline */
.btn-premium-outline {
    background: white;
    border: 1px solid var(--border);
    color: #64748b;
    padding: 10px 24px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-premium-outline:hover {
    border-color: var(--center-primary);
    color: var(--center-primary);
    background: #f8fafc;
}

/* Animations */
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-title { font-size: 22px; }
    .glass-clock { padding: 10px 15px; }
    .clock-time { font-size: 18px; }
    .stat-number { font-size: 28px; }
    .message-content-wrapper { flex-direction: column; }
    .message-header { flex-direction: column; gap: 12px; }
    .message-actions-group { width: 100%; justify-content: space-between; }
    .premium-table thead th { padding: 12px 16px; font-size: 11px; }
    .premium-table tbody td { padding: 12px 16px; font-size: 13px; }
    .notification-premium-meta { flex-direction: column; align-items: flex-start; }
    .notification-premium-actions { width: 100%; justify-content: flex-start; }
}

/* Utility Classes */
.fw-600 { font-weight: 600; }
.text-muted { color: #64748b; }
</style>

<script>
// Live Clock
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        second: '2-digit',
        hour12: true 
    });
    document.getElementById('liveClock').textContent = timeString;
}
setInterval(updateClock, 1000);

// Auto-hide read messages after 8 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.querySelectorAll('.premium-message-card.msg-read').forEach(function(card) {
            card.style.transition = 'opacity 0.5s ease';
            card.style.opacity = '0';
            setTimeout(function() {
                card.style.display = 'none';
            }, 500);
        });
    }, 8000);
    
    // Bootstrap 5 Modal initialization
    const modalElement = document.getElementById('allNoticesModal');
    if (modalElement) {
        new bootstrap.Modal(modalElement);
    }
});
</script>

<?= $this->endSection() ?>