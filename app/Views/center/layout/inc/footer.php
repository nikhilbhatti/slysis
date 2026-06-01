<!-- ===== DASHBOARD CONTENT ===== -->
<div class="app-main">
    <!-- 🔴 ORIGINAL DASHBOARD TABLES / DATA GO HERE -->
</div>

<!-- ===== FIXED FOOTER ===== -->
<footer class="app-footer">
    <!-- LEFT: Brand / Info -->
    <div class="footer-left">
        © <?= date('Y') ?> <strong>Slysis Academy ERP</strong><br>
        <small>Education Management System</small>
    </div>

    <!-- CENTER: Powered by -->
    <div class="footer-center">
        Powered by <strong>Slysis solutions</strong>
    </div>

    <!-- RIGHT: Links with icons (Hover effect) -->
    <div class="footer-right">
        <a href="<?= base_url('center/dashboard') ?>" class="footer-link">
            <i class="dw dw-home"></i>
            <span class="footer-text">Dashboard</span>
        </a>
        <a href="<?= base_url('center/students') ?>" class="footer-link">
            <i class="dw dw-user"></i>
            <span class="footer-text">Students</span>
        </a>
    </div>
</footer>

<style>
/* ===== RESET ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #f4f6f9;
}

/* ===== DASHBOARD CONTENT ===== */
.app-main {
    margin-left: 260px;   /* sidebar width */
    padding: 20px;
    padding-bottom: 100px; /* space for footer */
}

/* ===== FOOTER ===== */
.app-footer {
    position: fixed;
    bottom: 0;
    left: 260px;          /* aligns after sidebar */
    right: 0;
    background: #ffffff;
    border-top: 1px solid #e3e6ea;
    padding: 12px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12.5px;
    color: #555;
    z-index: 999;
    box-shadow: 0 -2px 6px rgba(0,0,0,0.1);
}

.footer-left strong {
    color: #222;
    font-size: 13px;
}
.footer-left small {
    color: #888;
    font-size: 11px;
}

.footer-center {
    font-size: 12px;
    color: #666;
}
.footer-center strong {
    color: #007bff;
    font-weight: 600;
}

.footer-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* ===== FOOTER LINKS WITH HOVER EFFECT ===== */
.footer-link {
    color: #555;
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 4px;
    position: relative;
}

.footer-link i {
    font-size: 14px; /* icon size */
}

/* TEXT HIDDEN BY DEFAULT */
.footer-text {
    opacity: 0;
    max-width: 0;
    overflow: hidden;
    transition: all 0.3s ease;
    white-space: nowrap;
    display: inline-block;
}

/* SHOW TEXT ON HOVER */
.footer-link:hover .footer-text {
    opacity: 1;
    max-width: 200px; /* enough to fit text */
    margin-left: 6px; /* space between icon and text */
}

/* HOVER COLOR */
.footer-link:hover {
    color: #007bff;
    text-decoration: none;
}

/* ===== RESPONSIVE ===== */
@media(max-width:768px) {
    .app-main {
        margin-left: 0;
        padding-bottom: 120px; /* space for footer */
    }
    .app-footer {
        left: 0;
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    .footer-right {
        justify-content: center;
        gap: 8px;
    }
}
</style>
