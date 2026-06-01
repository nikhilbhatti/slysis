<?= $this->extend('center/layout/pages-layout') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f4f7fe; }
    
    :root {
        --primary: #4318ff;
        --secondary: #6ad2ff;
        --success: #05cd99;
        --danger: #ee5d50;
        --dark-text: #1b2559;
    }

    .celebration-container { padding: 30px; }

    /* Premium Card Header */
    .glass-header {
        background: linear-gradient(135deg, #4318ff 0%, #b088ff 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        margin-bottom: -50px;
        position: relative;
        z-index: 1;
        box-shadow: 0px 20px 40px rgba(67, 24, 255, 0.25);
    }

    /* Main Table Card */
    .main-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 30px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding-top: 60px;
        box-shadow: 0px 40px 80px rgba(0, 0, 0, 0.05);
    }

    /* Row Styling */
    .event-row {
        margin: 10px 20px;
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid transparent;
    }

    .event-row:hover {
        background: white;
        transform: translateY(-5px) scale(1.01);
        box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.05);
        border-color: #e2e8f0;
    }

    /* Date Box Premium Look */
    .date-box {
        background: #f4f7fe;
        border-radius: 18px;
        width: 70px;
        height: 75px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: 0.3s;
    }

    .event-row:hover .date-box {
        background: var(--primary);
        color: white;
    }

    /* Avatar Glow */
    .avatar-glow {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Today Special Row */
    .today-highlight {
        background: linear-gradient(90deg, rgba(5, 205, 153, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
        border-left: 5px solid var(--success) !important;
    }

    /* Floating Icons Animation */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    .floating-emoji { animation: float 3s ease-in-out infinite; display: inline-block; }

    /* Custom Button */
    .btn-whatsapp {
        background: var(--success);
        color: white;
        border-radius: 15px;
        padding: 12px 25px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-whatsapp:hover {
        background: #04b386;
        transform: scale(1.05);
        color: white;
        box-shadow: 0px 10px 20px rgba(5, 205, 153, 0.3);
    }

    .empty-state img {
        filter: drop-shadow(0px 20px 30px rgba(0,0,0,0.1));
        max-width: 250px;
    }
</style>

<div class="celebration-container">
    <div class="row">
        <div class="col-lg-11 mx-auto">
            
            <div class="glass-header shadow-lg d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-800 mb-0"><span class="floating-emoji">🎁</span> Upcoming Celebrations</h1>
                    <p class="opacity-75 mb-0 fw-600">Making every student feel special this week</p>
                </div>
                <div class="text-end d-none d-md-block">
                    <div class="h2 fw-800 mb-0"><?= count($birthdays) ?></div>
                    <div class="text-xs opacity-75 fw-700">TOTAL EVENTS</div>
                </div>
            </div>

            <div class="main-card card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="text-secondary opacity-50 small fw-800 text-uppercase">
                                <tr>
                                    <th class="ps-5 py-4">Student Profile</th>
                                    <th class="text-center">Birthday Date</th>
                                    <th class="text-center">Vibe Check</th>
                                    <th class="text-end pe-5">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($birthdays)): ?>
                                    <?php foreach($birthdays as $row): 
                                        $isToday = (date('m-d') == date('m-d', strtotime($row['dob'])));
                                    ?>
                                    <tr class="event-row <?= $isToday ? 'today-highlight' : '' ?>">
                                        <td class="ps-5 py-4">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative me-4">
                                                    <?php if(!empty($row['photo'])): ?>
                                                        <img src="<?= base_url('uploads/students/'.$row['photo']) ?>" class="avatar-glow">
                                                    <?php else: ?>
                                                        <div class="avatar-glow bg-primary d-flex align-items-center justify-content-center text-white fw-bold h4 mb-0">
                                                            <?= substr($row['student_name'], 0, 1) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if($isToday): ?>
                                                        <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle p-2 shadow-sm" title="Active Celebration"></span>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <div class="text-dark fw-800 fs-5 mb-0"><?= esc($row['student_name']) ?></div>
                                                    <div class="text-muted small fw-600">ID: #<?= esc($row['id']) ?> • Student</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="date-box shadow-sm">
                                                    <span class="small fw-800 opacity-50"><?= date('M', strtotime($row['dob'])) ?></span>
                                                    <span class="h4 mb-0 fw-800"><?= date('d', strtotime($row['dob'])) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if($isToday): ?>
                                                <div class="badge rounded-pill bg-soft-success py-2 px-3 fw-800 text-success" style="background:#e6fff5">
                                                    🎉 IT'S PARTY TIME!
                                                </div>
                                            <?php else: ?>
                                                <div class="badge rounded-pill bg-soft-light py-2 px-3 fw-800 text-secondary" style="background:#f1f5f9">
                                                    ⏳ Coming Soon
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-5">
                                            <a href="https://wa.me/91<?= esc($row['phone']) ?>?text=Happy Birthday <?= urlencode($row['student_name']) ?>! 🎂 May your day be as bright as your future at our academy. 🎉" 
                                               target="_blank" class="btn-whatsapp shadow-sm">
                                                <i class="fab fa-whatsapp fs-5"></i>
                                                <span>Send Wishes</span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 empty-state">
                                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/no-data-found-8867280-7223910.png" class="mb-4">
                                            <h3 class="text-dark fw-800">No Birthdays This Week!</h3>
                                            <p class="text-muted fw-600">Don't worry, every day is a reason to learn something new.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer border-0 bg-transparent text-center py-5">
                    <a href="<?= base_url('center/dashboard') ?>" class="btn btn-link text-secondary fw-800 text-decoration-none small">
                        <i class="fas fa-chevron-left me-2"></i> RETURN TO DASHBOARD
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>