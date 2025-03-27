<?php if (!isset($_COOKIE['user'])) header('Location: /login'); ?>
<!DOCTYPE html>
<html lang="pl">
<?php require_once 'presets/head.php'; ?>
<?php $mobile = preg_match('/Mobile|Android|iPhone|iPad/', $_SERVER['HTTP_USER_AGENT']) ? true : false; ?>
<body <?= $mobile ? "class='flex-column-reverse' data-mobile='1'" : ""; ?>>
    <?php require_once 'presets/sidebar.php'; ?>

    <div id="main" class="animation fade-in-default animation-500ms">
        <div class="h-100 d-flex flex-column p-4 rounded-5 shadow">
            <?php
                if ($mobile && in_array('moderator', $user->getRoles())) {
                    ?>
                    <button type="button" class="btn btn-primary fs-6 mb-3" onclick="window.location.href='/moderator'"><i class="fas fa-user-cog me-2"></i>Moderator Panel</button>
                    <?php
                }
                if ($mobile && in_array('admin', $user->getRoles())) {
                    ?>
                    <button type="button" class="btn btn-primary fs-6 mb-3" onclick="window.location.href='/admin'"><i class="fas fa-user-shield me-2"></i>Admin Panel</button>
                    <?php
                }
            ?>
            <button type="button" id="logout" class="btn btn-danger fs-6"><i class="fas fa-person-circle-exclamation me-2"></i>Logout</button>
        </div>
    </div>

    <?php $mobile ? include_once 'presets/mobileTop.php' : ""; ?>
</body>
</html>