<!DOCTYPE html>
<html lang="pl">
<?php require_once __DIR__.'/../head.php'; ?>
<?php $mobile = preg_match('/Mobile|Android|iPhone|iPad/', $_SERVER['HTTP_USER_AGENT']) ? true : false; ?>
<body <?= $mobile ? "class='flex-column-reverse' data-mobile='1'" : ""; ?>>
    <?php require_once __DIR__.'/../sidebar.php'; ?>

    <div id="main" class="animation fade-in-default animation-500ms">
        <div class="h-100 d-flex flex-column p-4 rounded-5 shadow">
            <div class="under-construction container d-flex flex-column justify-content-center">
                <h1 class="text-center">501</h1>
                <h2 class="text-center">Not Implemented</h2>
                <p class="text-center">The server either does not recognize the request method, or it lacks the ability to fulfill the request.</p>
            </div>
        </div>
    </div>

    <?php $mobile ? include_once __DIR__.'/../mobileTop.php' : ""; ?>
</body>
</html>