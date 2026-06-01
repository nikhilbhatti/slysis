<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<style>
    /* ================= PREMIUM NOTIFICATION CARD ================= */
    .notification-card-box {
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.06);
        border: none;
        transition: 0.3s;
    }

    .page-title {
        font-weight: 800;
        letter-spacing: -0.8px;
        color: #0f172a;
        font-size: 26px;
    }

    /* ================= TABLE DESIGN (Course Style) ================= */
    .table-responsive {
        border-radius: 15px;
        overflow: hidden;
    }

    .table thead th {
        background-color: #f1f5f9;
        border-bottom: 2px solid #e2e8f0;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1.2px;
        font-weight: 700;
        color: #475569;
        padding: 18px;
    }

    .table tbody td {
        padding: 18px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .notif-title-cell {
        font-weight: 700;
        color: #1e293b;
        font-size: 15px;
        margin-bottom: 4px;
        display: block;
    }

    /* Unread Row Styling */
    .unread-row {
        background-color: #f8faff !important;
        position: relative;
    }
    
    .unread-row::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: #3b82f6;
    }

    /* ================= ICONS & BADGES ================= */
    .icon-container {
        width: 45px;
        height: 45px;
        background: #f1f5f9;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #64748b;
        transition: 0.3s;
    }

    .unread-row .icon-container {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-outline-date {
        border: 1px solid #cbd5e1;
        color: #64748b;
        background: #ffffff;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }

    .status-pill {
        font-weight: 700;
        font-size: 11px;
        padding: 6px 12px;
        border-radius: 8px;
        text-transform: uppercase;
    }

    /* ================= ACTION BUTTONS ================= */
    .btn-action-view {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
        transition: 0.3s all;
    }

    .btn-action-view:hover {
        background: #0369a1;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(3, 105, 161, 0.2);
    }

    /* Highlight Scroll */
    .highlight-scroll {
        animation: highlightAnim 3s ease-in-out;
    }

    @keyframes highlightAnim {
        0% { background-color: #fef9c3; }
        100% { background-color: transparent; }
    }

    @media (max-width: 768px) {
        .notification-card-box { padding: 15px; }
        .page-header .text-md-right { text-align: left !important; margin-top: 15px; }
    }
</style>

<div class="page-header mb-30">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h3 class="page-title mb-2">🔔 Notification Center</h3>
            <p class="text-muted" style="font-size: 15px;">Slysis Academy ki sabhi updates aur alerts ko manage karein.</p>
        </div>
        <div class="col-md-5 text-md-right">
            <a href="<?= base_url('superadmin/mark-all-read') ?>" 
               class="btn btn-primary shadow-lg" 
               style="border-radius: 12px; padding: 12px 25px; font-weight: 600; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none;">
               <i class="dw dw-checked mr-2"></i> Mark All As Read
            </a>
        </div>
    </div>
</div>

<div class="notification-card-box mb-30">
    <div class="table-responsive">
        <table class="table table-hover" id="notifications-table" style="width:100%">
            <thead>
                <tr>
                    <th>Update Info</th>
                    <th>Timestamp</th>
                    <th>Status</th>
                    <th class="text-center datatable-nosort">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($notifications)): ?>
                    <?php foreach($notifications as $noti): ?>
                        <tr class="<?= $noti['status'] == 'unread' ? 'unread-row' : '' ?>" id="notif-<?= $noti['id'] ?>">
                            
                            <td style="min-width: 320px;">
                                <div class="d-flex align-items-center">
                                    <div class="icon-container mr-3">
                                        <i class="dw <?= $noti['status'] == 'unread' ? 'dw-notification-1' : 'dw-notification' ?>"></i>
                                    </div>
                                    <div style="max-width: 400px;">
                                        <span class="notif-title-cell"><?= esc($noti['title']) ?></span>
                                        <div class="text-muted small text-truncate" style="max-width: 350px;">
                                            <?= esc($noti['message']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge-outline-date">
                                    <i class="bi bi-clock mr-1 text-primary"></i>
                                    <?= date('d M Y', strtotime($noti['created_at'])) ?>
                                    <span class="text-muted mx-1">|</span>
                                    <?= date('h:i A', strtotime($noti['created_at'])) ?>
                                </span>
                            </td>

                            <td>
                                <?php if($noti['status'] == 'unread'): ?>
                                    <span class="status-pill bg-primary text-white shadow-sm">
                                        <i class="bi bi-circle-fill mr-1" style="font-size: 7px;"></i> New Alert
                                    </span>
                                <?php else: ?>
                                    <span class="status-pill bg-light text-muted border">
                                        Seen
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <a href="<?= base_url('superadmin/view-notification/'.$noti['id']) ?>" 
                                   class="btn-action-view" 
                                   data-toggle="tooltip"
                                   title="View Details">
                                    <i class="dw dw-eye"></i>
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="mb-3">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100" style="opacity:0.2;">
                            </div>
                            <h5 class="text-muted">Inbox is empty!</h5>
                            <p class="text-muted small">Abhi koi nayi notification nahi aayi hai.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    // Tooltip initialize
    $('[data-toggle="tooltip"]').tooltip();

    if ($.fn.DataTable.isDataTable('#notifications-table')) {
        $('#notifications-table').DataTable().destroy();
    }

    var table = $('#notifications-table').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        order: [[1, "desc"]], // Default sort by date
        info: true,
        autoWidth: false,
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search notifications...",
            paginate: {
                next: '<i class="ion-chevron-right"></i>',
                previous: '<i class="ion-chevron-left"></i>'
            }
        }
    });

    // Custom CSS for search bar to match Course Style
    $('.dataTables_filter input').addClass('form-control shadow-sm').css({
        'border-radius': '10px',
        'border': '1px solid #e2e8f0',
        'padding': '8px 15px',
        'margin-left': '10px'
    });

    // Scroll and Highlight logic
    <?php if(isset($scrollToId)): ?>
        const element = document.getElementById("notif-<?= $scrollToId ?>");
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            element.classList.add('highlight-scroll');
        }
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>