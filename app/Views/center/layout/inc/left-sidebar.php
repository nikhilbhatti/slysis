<?php 
    /**
     * Direct Database Check with is_allowed filter.
     */
    $session = session();
    $db = \Config\Database::connect();
    $centerId = $session->get('center_id');

    // Permissions logic
    $permsData = $db->table('center_permissions')
                    ->where('center_id', $centerId)
                    ->where('is_allowed', 1) 
                    ->get()
                    ->getResultArray();
    
    $permissions = array_map('strtolower', array_column($permsData, 'module_name')); 
?>

<div class="left-side-bar">
    <div class="brand-logo" style="border-bottom: 1px solid #f0f0f0; margin-bottom: 10px;">
        <a href="<?= base_url('center/dashboard') ?>">
            <img src="<?= base_url('assets/images/slysis_academy.png');?>" alt="" class="dark-logo" style="max-height: 40px;">
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>

    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">

                <li class="menu-label">Main Navigation</li>

                <li>
                    <a href="<?= base_url('center/dashboard') ?>" class="dropdown-toggle no-arrow <?= (url_is('center/dashboard')) ? 'active' : '' ?>">
                        <span class="micon bi bi-grid-1x2"></span><span class="mtext">Dashboard</span>
                    </a>
                </li>

                

                <?php if (in_array('students', $permissions)): ?>
                <li class="dropdown <?= (url_is('center/students') || url_is('center/add-student')) ? 'show' : '' ?>">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-people"></span><span class="mtext">Students</span>
                    </a>
                    <ul class="submenu" style="<?= (url_is('center/students') || url_is('center/add-student')) ? 'display: block;' : '' ?>">
                        <li><a href="<?= base_url('center/students') ?>" class="<?= (url_is('center/students')) ? 'active' : '' ?>">View Students</a></li>
                        <li><a href="<?= base_url('center/add-student') ?>" class="<?= (url_is('center/add-student')) ? 'active' : '' ?>">Add Student</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if (in_array('courses', $permissions)): ?>
                <li>
                    <a href="<?= base_url('center/courses') ?>" class="dropdown-toggle no-arrow <?= (url_is('center/courses')) ? 'active' : '' ?>">
                        <span class="micon bi bi-journal-bookmark"></span><span class="mtext">My Courses</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('center/upcoming-events') ?>" class="dropdown-toggle no-arrow <?= (url_is('center/upcoming-events')) ? 'active' : '' ?>">
                        <span class="micon bi bi-calendar-event"></span><span class="mtext">Upcoming Events</span>
                    </a>
                </li>
                <?php endif; ?>

                <li class="menu-label">Account</li>

                <?php if (in_array('profile', $permissions)): ?>
                <li>
                    <a href="<?= base_url('center/profile') ?>" class="dropdown-toggle no-arrow <?= (url_is('center/profile')) ? 'active' : '' ?>">
                        <span class="micon bi bi-person-circle"></span><span class="mtext">My Profile</span>
                    </a>
                </li>
                <?php endif; ?>

                <li>
                    <a href="<?= base_url('logout') ?>" class="dropdown-toggle no-arrow logout-btn">
                        <span class="micon bi bi-box-arrow-right"></span><span class="mtext">Logout</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>

<style>
    /* Clean Sidebar Styling */
    .menu-label {
        padding: 20px 20px 8px !important;
        font-size: 11px;
        text-transform: uppercase;
        color: #888;
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    /* Active State (Hostinger Look) */
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
    }

    .logout-btn {
        color: #ff5b5b !important;
    }

    .logout-btn:hover {
        background: #fff5f5 !important;
    }
</style>