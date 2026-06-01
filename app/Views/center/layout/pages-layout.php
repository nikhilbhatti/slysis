<!DOCTYPE html>
<html>
<head>
    <title><?= isset($pageTitle)?$pageTitle:'Center Dashboard' ?></title>
    <link rel="stylesheet" href="<?= base_url('backend/vendors/styles/core.css') ?>">
    <link rel="stylesheet" href="<?= base_url('backend/vendors/styles/icon-font.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('backend/vendors/styles/style.css') ?>">
</head>
<body>

<?= $this->include('center/layout/inc/header') ?>
<?= $this->include('center/layout/inc/left-sidebar') ?>

<div class="main-container">
    <div class="pd-ltr-20">
        <?= $this->renderSection('content') ?>
    </div>
</div>

<?= $this->include('center/layout/inc/right-sidebar') ?>
<?= $this->include('center/layout/inc/footer') ?>

<script src="<?= base_url('backend/vendors/scripts/core.js') ?>"></script>
<script src="<?= base_url('backend/vendors/scripts/script.min.js') ?>"></script>
<script src="<?= base_url('backend/vendors/scripts/layout-settings.js') ?>"></script>

</body>
</html>
