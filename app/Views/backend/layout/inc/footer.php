<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Slysis Academy ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* ===== RESET ===== */
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: Arial, sans-serif;
        }

        body{
            background:#f4f6f9;
        }

        /* ===== MAIN DASHBOARD CONTENT ===== */
        .app-main{
            margin-left:260px;      /* sidebar space */
            padding:20px;
            padding-bottom:100px;   /* 🔥 LAST ROW FIX */
        }

        /* ===== FOOTER ===== */
        .app-footer{
            position:fixed;
            bottom:0;
            left:260px;
            right:0;
            background:#ffffff;
            border-top:1px solid #e3e6ea;
            padding:10px 22px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            font-size:12.5px;
            color:#555;
            z-index:999;
            box-shadow:0 -2px 6px rgba(0,0,0,0.1);
        }

        .footer-left strong{
            color:#222;
            font-size:13px;
        }
        .footer-left small{
            color:#888;
            font-size:11px;
        }

        .footer-center{
            font-size:12px;
            color:#666;
        }
        .footer-center strong{
            color:#007bff;
            font-weight:600;
        }

        /* ===== FOOTER LINKS ===== */
        .footer-right{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .footer-link{
            display:flex;
            align-items:center;
            gap:4px;
            color:#555;
            text-decoration:none;
            font-weight:500;
            position:relative;
        }

        .footer-link i{
            font-size:14px; /* icon size */
        }

        /* TEXT HIDDEN BY DEFAULT */
        .footer-text{
            opacity:0;
            max-width:0;
            overflow:hidden;
            white-space:nowrap;
            transition:all 0.3s ease;
            display:inline-block;
        }

        /* SHOW TEXT ON HOVER */
        .footer-link:hover .footer-text{
            opacity:1;
            max-width:150px;
            margin-left:6px;
        }

        /* HOVER COLOR */
        .footer-link:hover{
            color:#007bff;
            text-decoration:none;
        }

        /* ===== MOBILE ===== */
        @media(max-width:768px){
            .app-main{
                margin-left:0;
                padding-bottom:120px;
            }
            .app-footer{
                left:0;
                flex-direction:column;
                text-align:center;
                gap:6px;
            }
            .footer-right{
                justify-content:center;
                gap:8px;
            }
        }
    </style>
    <!-- Include icons library, for example using Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <!-- ===== DASHBOARD CONTENT ===== -->
    <div class="app-main">
        <!-- 🔴 Your dashboard content goes here -->
    </div>

    <!-- ===== FOOTER ===== -->
    <footer class="app-footer">
        <div class="footer-left">
            © <?= date('Y') ?> <strong>Slysis Academy ERP</strong><br>
            <small>Education Management System</small>
        </div>

        <div class="footer-center">
            Powered by <strong>Slysis solutions</strong>
        </div>

        <div class="footer-right">
            <a href="#" class="footer-link">
                <i class='bx bx-home'></i>
                <span class="footer-text">Dashboard</span>
            </a>
            <a href="#" class="footer-link">
                <i class='bx bx-building'></i>
                <span class="footer-text">Center</span>
            </a>
            <a href="#" class="footer-link">
                <i class='bx bx-book'></i>
                <span class="footer-text">Course</span>
            </a>
            <a href="#" class="footer-link">
                <i class='bx bx-user'></i>
                <span class="footer-text">Total Students</span>
            </a>
        </div>
    </footer>

</body>
</html>
