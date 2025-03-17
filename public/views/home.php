<!DOCTYPE html>
<html lang="pl">
<?php require_once 'presets/head.php'; ?>
<?php $mobile = preg_match('/Mobile|Android|iPhone|iPad/', $_SERVER['HTTP_USER_AGENT']) ? true : false; ?>
<body <?= $mobile ? "class='flex-column-reverse'" : ""; ?>>
    <?php require_once 'presets/sidebar.php'; ?>

    <div id="main">
        <div class="h-100 d-flex flex-column p-4 rounded-5 shadow">

        </div>
    </div>
</body>
</html>