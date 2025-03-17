<?php
    $url = $_SERVER['REQUEST_URI'];
?>
<nav id="menuBottom" class="shadow">
    <div id="links">
        <?php
            require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/Link.php';

            $links = [
                new Link("home", "fa-home", "/"),
                new Link("places", "fa-city", "/places"),
                new Link("map", "fa-map-location-dot", "/map"),
                new Link("favourite", "fa-star", "/favourite", false, true),
                new Link("moderator", "fa-user-gear", "/moderator", false, true, ["admin", "moderator"]),
                new Link("admin", "fa-user-shield", "/admin", false, true, ["admin"]),
                new Link("user", "fa-user", "/user", false, true, ["admin"]),
                new Link("login", "fa-right-to-bracket", "/login", false)
            ];

            foreach ($links as $link) {
                if ($link->logged && !isset($_COOKIE['user'])) {
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
