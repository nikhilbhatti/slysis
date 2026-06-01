<?= $this->extend('backend/layout/pages-layout') ?>
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
    .main-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 30px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding-top: 60px;
        box-shadow: 0px 40px 80px rgba(0, 0, 0, 0.05);
    }
    .event-row {
        margin: 10px 20px;
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .event-row:hover {
        background: white;
        transform: translateY(-5px);
        box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.05);
    }
    .date-box {
        background: #f4f7fe;
        border-radius: 18px;
        width: 70px;
        height: 75px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .event-row:hover .date-box { background: var(--primary); color: white; }
    .avatar-glow {
        width: 60px; height: 60px;
        object-fit: cover; border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
    }
    .today-highlight {
        background: linear-gradient(90deg, rgba(5, 205, 153, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
        border-left: 5px solid var(--success) !important;
    }
    .btn-whatsapp {
        background: var(--success); color: white; border-radius: 15px;
        padding: 10px 20px; font-weight: 700; border: none;
        display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
    }
    .btn-whatsapp:hover { background: #04b386; color: white; transform: scale(1.05); }
</style>

<div class="celebration-container">
    <div class="row">
        <div class="col-lg-11 mx-auto">
            
            <div class="glass-header shadow-lg d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-800 mb-0">🎁 Upcoming Celebrations</h1>
                    <p class="opacity-75 mb-0 fw-600">Making every student feel special</p>
                </div>
                <div class="text-end d-none d-md-block">
                    <div class="h2 fw-800 mb-0"><?= is_array($birthdays) ? count($birthdays) : 0 ?></div>
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
                                <?php if(!empty($birthdays) && is_array($birthdays)): ?>
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
                                                </div>
                                                <div>
                                                    <div class="text-dark fw-800 fs-5 mb-0"><?= esc($row['student_name']) ?></div>
                                                    <div class="text-muted small fw-600"><?= esc($row['center_name'] ?? 'General') ?></div>
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
                                                <span class="badge rounded-pill py-2 px-3 fw-800" style="background:#e6fff5; color:#05cd99">🎉 IT'S PARTY TIME!</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill py-2 px-3 fw-800 text-secondary" style="background:#f1f5f9">⏳ Coming Soon</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-5">
                                            <a href="https://wa.me/91<?= esc($row['phone']) ?>?text=Happy Birthday <?= urlencode($row['student_name']) ?>! 🎂" target="_blank" class="btn-whatsapp shadow-sm">
                                                <i class="fab fa-whatsapp fs-5"></i>
                                                <span>Send Wishes</span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/no-data-found-8867280-7223910.png" style="max-width:200px" class="mb-3">
                                            <h3 class="text-dark fw-800">No Birthdays Found!</h3>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent text-center py-4">
                    <a href="<?= base_url('superadmin/dashboard') ?>" class="btn btn-link text-secondary fw-800 text-decoration-none small">
                        <i class="fas fa-chevron-left me-2"></i> RETURN TO DASHBOARD
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>