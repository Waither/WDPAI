<!DOCTYPE html>
<html lang="pl">
<?php require_once 'presets/head.php'; ?>
<body <?= $mobile ? "class='flex-column-reverse' data-mobile='1'" : ""; ?>>
    <?php require_once 'presets/sidebar.php'; ?>

    <div id="main" class="animation fade-in-default animation-500ms">
        <div class="h-100 d-flex flex-column p-4 rounded-5 shadow">
            <div class="under-construction container d-flex flex-column justify-content-center">
                <h1 class="text-center">UNDER CONSTRUCTION</h1>
            </div>
        </div>
    </div>

    <?php $mobile ? include_once 'presets/mobileTop.php' : ""; ?>
</body>
</html>