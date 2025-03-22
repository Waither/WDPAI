<?php
    $url = $_SERVER['REQUEST_URI'];
?>
<nav id="<?= $mobile ? "menuBottom" : "menuLeft"; ?>" class="shadow">
    <?php
        if (!$mobile) {
            ?>
            <div id="logo">
                <img src="/public/img/logo.png" alt="">
            </div>
            <?php
        }
    ?>
    <div id="links">
        <?php
            require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/Link.php';

            $links = [
                new Link("home", "fa-home", "/"),
                new Link("places", "fa-city", "/places"),
                new Link("map", "fa-map-location-dot", "/map"),
                new Link("favourite", "fa-star", "/favourite", true),
                new Link("moderator", "fa-user-gear", "/moderator", true, ["admin", "moderator"]),
                new Link("admin", "fa-user-shield", "/admin", true, ["admin"]),
                new Link("user", "fa-user", "/user", true),
                new Link("login", "fa-right-to-bracket", "/login")
            ];

            if (isset($_COOKIE['user'])) {
                require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';

                $user = query('SELECT * FROM tb_user WHERE "ID_special" = :ID', [ ":ID" => $_COOKIE['user'] ]);
            }

            foreach ($links as $link) {
                if ($link->logged && !isset($_COOKIE['user'])) {
                    continue;
                }
                if (count($link->roles) && isset($_COOKIE['user'])) {
                    continue;
                }

                if ($mobile && count($link->roles)) {
                    continue;
                }
                
                if (isset($_COOKIE['user']) && $link->name == "login") {
                    continue;
                }

                $active = $url == $link->url ? true : false;
                ?>
                <div class="link">
                    <a id="menu_<?= $link->name; ?>" class="buttonMenu <?= $active ? "shadow" : ""; ?>" href="<?= $link->url; ?>" current-page="<?= $active ? "true" : "false"; ?>">
                        <i class="fas <?= $link->icon; ?> fa-2x"></i>
                    </a>
                    <label for="menu_<?= $link->name; ?>"><?= ucfirst($link->name); ?></label>
                </div>
                <?php
            }
        ?>
    </div>
</nav>
