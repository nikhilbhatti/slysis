<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
        <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
    </div>

    <div class="header-right">

        <!-- SETTINGS ICON -->
        <div class="dashboard-setting user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" 
                   href="<?= base_url('center/settings') ?>" 
                   title="Settings / Change Password">
                    <i class="dw dw-settings2"></i>
                </a>
            </div>
        </div>

        <!-- USER PROFILE -->
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">

                    <!-- LOGO -->
                    <?php $logo = base_url('assets/images/slysis_academy.png'); ?>
                    <img src="<?= $logo ?>" alt="SLYSIS Logo" 
                        class="user-icon"
                        style="
                            display:inline-block;
                            width:60px;
                            height:60px;
                            border-radius:50%;
                            object-fit:cover;
                            box-shadow:0 4px 10px rgba(0,0,0,0.3);
                            transition: transform 0.3s, box-shadow 0.3s;
                        " />

                    <span class="user-name ml-2">
                        <?= esc(session()->get('name')) ?>
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="<?= base_url('center/profile') ?>">
                        <i class="dw dw-user1"></i> Profile
                    </a>
                    <a class="dropdown-item" href="<?= base_url('center/settings') ?>">
                        <i class="dw dw-settings2"></i> Change Password
                    </a>
                    <a class="dropdown-item" href="<?= base_url('logout') ?>">
                        <i class="dw dw-logout"></i> Logout
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Hover effect for logo image */
    .user-icon:hover {
        transform: scale(1.15);
        box-shadow: 0 6px 15px rgba(0,0,0,0.4);
    }
</style>
