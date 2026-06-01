<?php
$logo = base_url('assets/images/slysis_academy.png');
// ✅ Logic: Sirf latest 5 notifications nikalne ke liye
$dropdownNotifications = !empty($notifications) ? array_slice($notifications, 0, 5) : [];
?>

<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
        <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>

        <div class="header-search">
            <form onsubmit="return false;">
                <div class="form-group mb-0 position-relative">
                    <i class="dw dw-search2 search-icon"></i>
                    <input 
                        type="text" 
                        class="form-control search-input" 
                        id="globalSearch"
                        placeholder="Search Center / Student / Course..." 
                        autocomplete="off"
                    />
                    <div id="searchResultBox" class="search-result-box"></div>
                </div>
            </form>
        </div>
    </div>

    <div class="header-right">
        <div class="user-notification" style="position:relative;margin-right:20px;">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                    <i class="icon-copy dw dw-notification" style="font-size:22px;"></i>
                    <?php if (!empty($unreadCount) && $unreadCount > 0): ?>
                        <span class="notification-dot"></span>
                        <span class="notification-count"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-lg border-0" style="min-width: 300px; border-radius: 12px;">
                    <div class="dropdown-header d-flex justify-content-between align-items-center border-bottom">
                        <h6 class="mb-0 fw-bold">Recent Notifications</h6>
                        <?php if (!empty($unreadCount) && $unreadCount > 0): ?>
                            <a href="<?= base_url('superadmin/mark-all-read') ?>" class="small text-primary text-decoration-none">Mark all as read</a>
                        <?php endif; ?>
                    </div>
                    <div class="notification-list mx-h-350 customscroll">
                        <ul class="list-unstyled mb-0">
                            <?php if (!empty($dropdownNotifications)): ?>
                                <?php foreach ($dropdownNotifications as $note): ?>
                                    <li class="border-bottom <?= $note['status'] == 'unread' ? 'bg-light' : '' ?>">
                                        <a href="<?= base_url('superadmin/view-notification/'.$note['id']) ?>" class="dropdown-item p-3">
                                            <h3 class="h6 mb-1 fw-bold <?= $note['status'] == 'unread' ? 'text-dark' : 'text-muted' ?>" style="font-size: 14px;">
                                                <?= esc($note['title']) ?>
                                            </h3>
                                            <p class="mb-1 text-muted small text-wrap"><?= strlen($note['message']) > 60 ? substr(esc($note['message']), 0, 60).'...' : esc($note['message']) ?></p>
                                            <small class="text-secondary" style="font-size: 11px;">
                                                <i class="bi bi-clock"></i> <?= date('d M, h:i A', strtotime($note['created_at'])) ?>
                                            </small>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="text-center py-4">
                                    <p class="text-muted mb-0 small">No new notifications</p>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="dropdown-footer text-center border-top py-2">
                        <a href="<?= base_url('superadmin/notifications') ?>" class="text-primary fw-bold small text-decoration-none">
                            View All Notifications (<?= count($notifications ?? []) ?>)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="logo-circle">
                        <img src="<?= $logo ?>" alt="Logo">
                    </span>
                    <span class="user-name ms-2"><?= esc(session()->get('name')) ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="<?= base_url('superadmin/profile') ?>"><i class="dw dw-user1"></i> Profile</a>
                    <a class="dropdown-item" href="<?= base_url('superadmin/change-password') ?>"><i class="dw dw-settings2"></i> Change Password</a>
                    <a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="dw dw-logout"></i> Log Out</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Notification Styles */
.notification-dot {
    position: absolute;
    top: 5px;
    right: -2px;
    width: 10px;
    height: 10px;
    background: #ff3b3b;
    border-radius: 50%;
    border: 2px solid #fff;
}
.notification-count {
    position: absolute;
    top: -5px;
    right: -8px;
    background: #ff3b3b;
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: bold;
}
.bg-light { background-color: #f8f9fa !important; }

/* Existing Styles */
.logo-circle { 
    width:45px; height:45px; border-radius:50%; background:#fff; 
    display:flex; align-items:center; justify-content:center; 
    box-shadow:0 2px 6px rgba(0,0,0,0.2); 
}
.logo-circle img { max-height:28px; }

.search-result-box { 
    position:absolute; top:100%; left:0; width:100%; background:#fff; 
    border:1px solid #ddd; border-radius:6px;
    box-shadow:0 4px 10px rgba(0,0,0,0.15); display:none; z-index:9999;
    max-height: 400px; overflow-y: auto;
}
.search-item { padding:10px 15px; border-bottom:1px solid #f1f1f1; cursor:pointer; }
.search-item:hover { background:#f5f7fa; }
.search-item small { color:#777; }
.text-danger { color:red; text-align:center; padding:10px; }
</style>

<script>
const globalSearch = document.getElementById('globalSearch');
const searchResultBox = document.getElementById('searchResultBox');

globalSearch.addEventListener('keyup', function () {
    let keyword = this.value.trim();
    if (keyword.length < 2) {
        searchResultBox.style.display = 'none';
        searchResultBox.innerHTML = '';
        return;
    }

    fetch("<?= base_url('superadmin/global-search') ?>?query=" + encodeURIComponent(keyword))
        .then(res => res.json())
        .then(data => {
            searchResultBox.innerHTML = '';
            if (data.length === 0) {
                searchResultBox.innerHTML = '<div class="search-item text-danger">No result found</div>';
            } else {
                data.forEach(item => {
                    searchResultBox.innerHTML += `
                        <div class="search-item" data-id="${item.id}" data-type="${item.type}">
                            <strong>${item.name}</strong><br>
                            <small class="text-primary text-uppercase">${item.type}</small>
                        </div>
                    `;
                });

                document.querySelectorAll('.search-item').forEach(el => {
                    el.addEventListener('click', function () {
                        const id = this.dataset.id;
                        const type = this.dataset.type;
                        
                        fetch("<?= base_url('superadmin/get-details-by-id') ?>", {
                            method: 'POST',
                            headers: {'Content-Type':'application/json'},
                            body: JSON.stringify({id: id, type: type})
                        })
                        .then(res => res.json())
                        .then(details => {
                            console.log(details);
                            alert(type + ": " + (details.name || details.title) + " fetched!"); 
                        });
                        searchResultBox.style.display = 'none';
                        globalSearch.value = '';
                    });
                });
            }
            searchResultBox.style.display = 'block';
        });
});

document.addEventListener('click', function (e) {
    if (!globalSearch.contains(e.target) && !searchResultBox.contains(e.target)) {
        searchResultBox.style.display = 'none';
    }
});
</script>