<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?= $title ?? 'Super Admin' ?></title>

    <link rel="stylesheet" type="text/css" href="<?= base_url('backend/vendors/styles/core.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('backend/vendors/styles/icon-font.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('backend/vendors/styles/style.css') ?>">
    
    <?= $this->renderSection('stylesheets') ?>
</head>
<body>

    <?= $this->include('backend/layout/inc/header') ?>
    <?= $this->include('backend/layout/inc/left-sidebar') ?>
    <?= $this->include('backend/layout/inc/right-sidebar') ?>

    <div class="main-container">
        <div class="pd-ltr-20">
            <?= $this->renderSection('content') ?>
            
            <div class="footer-wrap pd-20 mb-20 card-box mt-4">
                <?= $this->include('backend/layout/inc/footer') ?>
            </div>
        </div>
    </div>

    <script src="<?= base_url('backend/vendors/scripts/core.js') ?>"></script>
    
    <script src="<?= base_url('backend/vendors/scripts/script.min.js') ?>"></script>
    <script src="<?= base_url('backend/vendors/scripts/layout-settings.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>

</body>
</html>