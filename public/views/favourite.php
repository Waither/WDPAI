<?php if (!isset($_COOKIE['user'])) header('Location: /login'); ?>
<!DOCTYPE html>
<html lang="pl">
<?php include 'presets/head.php'; ?>
<?php $mobile = preg_match('/Mobile|Android|iPhone|iPad/', $_SERVER['HTTP_USER_AGENT']) ? true : false; ?>
<body <?= $mobile ? "class='flex-column-reverse' data-mobile='1'" : ""; ?>>
    <?php require_once 'presets/sidebar.php'; ?>

    <div id="main" class="animation fade-in-default animation-500ms">
        <div class="h-100 d-flex flex-column p-4 rounded-5 shadow">
            <h1 class="text-center">Favourite</h1>
            <div class="under-construction container d-flex flex-column justify-content-center">
                <h1 class="text-center">UNDER CONSTRUCTION</h1>
            </div>
        </div>
    </div>
</body>
</html>