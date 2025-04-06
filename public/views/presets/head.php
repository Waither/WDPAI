<head>
    <?php
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $segments = explode('/', $path);
    $title = 'Home';
    if (!empty($segments[0])) {
        $title = ucfirst($segments[0]);
    }
    ?>
    <title>TruckStopInfo | <?= $title ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Logo -->
    <link rel="icon" href="/public/img/logo.png" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="/public/styles/icons/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="/public/styles/main.css">

    <!-- JavaScript -->
    <script src="/public/scripts/js/main.js" type="module"></script>

    <?php
        $user = null;
        if (isset($_COOKIE['user'])) {
            require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';
            $user = query('SELECT * FROM truckstop.vw_user WHERE "ID_special" = :ID', [ ":ID" => $_COOKIE['user'] ], "User")[0];

            ?>
            <script>
                const userIdSpecial = <?= json_encode($user->ID_special) ?>;
            </script>
            <?php
        }

        $mobile = preg_match('/Mobile|Android|iPhone|iPad/', $_SERVER['HTTP_USER_AGENT']) ? true : false;
    ?>
</head>