<?php 
    $session = session();
    $db = \Config\Database::connect();
    
    $isSuperAdmin = $session->get('superadmin_id') ? true : false;
    $centerId = $session->get('center_id');

    // Current URI segments check karne ke liye standard CI4 way
    $uri = service('uri');
    $segment2 = $uri->getSegment(2); // e.g., 'dashboard', 'courses', 'centers'

    // --- NEW LOGIC: Fetching Expiry Count for Sidebar Badge ---
    $currentDate = date('Y-m-d');
    $sevenDaysLater = date('Y-m-d', strtotime('+7 days'));
    
    $expiryCount = $db->table('enrollments')
                      ->where('expiry_date >=', $currentDate)
                      ->where('expiry_date <=', $sevenDaysLater)
                      ->countAllResults();
?>

<div class="left-side-bar">
    <div class="brand-logo" style="border-bottom: 1px solid #f0f0f0; margin-bottom: 10px;">
        <a href="<?= $isSuperAdmin ? base_url('superadmin/dashboard') : base_url('center/dashboard') ?>">
            <img src="<?= base_url('assets/images/slysis_academy.png');?>" alt="Logo" class="dark-logo" style="max-height: 40px;">
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>

    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                
                <?php if($isSuperAdmin): ?>
                    <li class="menu-label">Main Control</li>
                    
                    <li>
                        <a href="<?= base_url('superadmin/dashboard') ?>" class="dropdown-toggle no-arrow <?= ($segment2 == 'dashboard') ? 'active' : '' ?>">
                            <span class="micon bi bi-grid-1x2"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="dropdown <?= in_array($segment2, ['centers', 'add-center', 'edit-center']) ? 'show' : '' ?>">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-building"></span><span class="mtext">Centers</span>
                        </a>
                        <ul class="submenu" style="<?= in_array($segment2, ['centers', 'add-center', 'edit-center']) ? 'display: block;' : '' ?>">
                            <li><a href="<?= base_url('superadmin/add-center') ?>" class="<?= ($segment2 == 'add-center') ? 'active' : '' ?>">Add New Center</a></li>
                            <li><a href="<?= base_url('superadmin/centers') ?>" class="<?= ($segment2 == 'centers') ? 'active' : '' ?>">View All Centers</a></li>
                        </ul>
                    </li>

                    <li class="dropdown <?= in_array($segment2, ['courses', 'add-course', 'edit-course']) ? 'show' : '' ?>">
                        <a href="javascript:;" class="dropdown-toggle">
                            <span class="micon bi bi-journal-bookmark"></span><span class="mtext">Courses</span>
                        </a>
                        <ul class="submenu" style="<?= in_array($segment2, ['courses', 'add-course', 'edit-course']) ? 'display: block;' : '' ?>">
                            <li><a href="<?= base_url('superadmin/add-course') ?>" class="<?= ($segment2 == 'add-course') ? 'active' : '' ?>">Add Course</a></li>
                            <li><a href="<?= base_url('superadmin/courses') ?>" class="<?= ($segment2 == 'courses') ? 'active' : '' ?>">Manage Courses</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="<?= base_url('superadmin/all-students') ?>" class="dropdown-toggle no-arrow <?= ($segment2 == 'all-students') ? 'active' : '' ?>">
                            <span class="micon bi bi-people"></span><span class="mtext">Students</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('superadmin/enrollments') ?>" class="dropdown-toggle no-arrow <?= ($segment2 == 'enrollments') ? 'active' : '' ?>">
                            <span class="micon bi bi-file-earmark-check"></span><span class="mtext">Enrollments</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('superadmin/upcoming-events') ?>" class="dropdown-toggle no-arrow <?= ($segment2 == 'upcoming-events') ? 'active' : '' ?>">
                            <span class="micon bi bi-calendar-check"></span>
                            <span class="mtext">Upcoming Events</span>
                            <?php if($expiryCount > 0): ?>
                                <span class="badge badge-pill badge-danger shadow-sm" style="font-size: 10px; margin-left: 5px; background: #ff5b5b;">
                                    <?= $expiryCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>

                <?php endif; ?>

                <?php if($centerId): ?>
                    <li class="menu-label">Center Management</li>
                    <li>
                        <a href="<?= base_url('center/dashboard') ?>" class="dropdown-toggle no-arrow <?= (url_is('center/dashboard')) ? 'active' : '' ?>">
                            <span class="micon bi bi-house-door"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <?php endif; ?>

                <hr style="margin: 10px 20px; border-top: 1px solid #eee;">
                
                <li>
                    <a href="<?= base_url('logout') ?>" class="dropdown-toggle no-arrow" style="color: #ff5b5b !important;">
                        <span class="micon bi bi-box-arrow-right" style="color: #ff5b5b;"></span><span class="mtext">Logout</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>

<style>
    /* Professional Sidebar Styling (Preserved) */
    .sidebar-menu .dropdown-toggle.active, 
    .sidebar-menu .submenu li a.active {
        background: #f8f9ff !important;
        color: #1b00ff !important;
        font-weight: 700;
        border-right: 4px solid #1b00ff;
    }
    .sidebar-menu .submenu li a.active {
        border-right: none;
        padding-left: 60px; 
        position: relative;
    }
    .sidebar-menu .submenu li a.active::before {
        content: "";
        position: absolute;
        left: 40px;
        top: 50%;
        width: 6px;
        height: 6px;
        background: #1b00ff;
        border-radius: 50%;
        transform: translateY(-50%);
    }
</style>