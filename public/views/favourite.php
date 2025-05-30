<?php
    if (!isset($_COOKIE['user'])) {
        header('Location: /login');
    }
?>
<!DOCTYPE html>
<html lang="pl">
<?php require_once 'presets/head.php'; ?>
<body <?= $mobile ? "class='flex-column-reverse' data-mobile='1'" : ""; ?>>
    <?php require_once 'presets/sidebar.php'; ?>

    <div id="main" class="animation fade-in-default animation-500ms">
        <div class="h-100 d-flex flex-column p-4 rounded-5 shadow">
            <h1 class="text-center">Favourite</h1>
            <div class="container d-flex flex-column">
                <div class="row">
                    <div class="col-12 mb-3">
                        <input type="text" id="seachBar" class="form-control" placeholder="Search for places...">
                    </div>
                </div>
                <div id="placesDiv" class="row container-flex-1">
                    <div class="col-12">
                        <section class="scroll-box">
                            <?php
                                $places = query('SELECT * FROM "vw_place" NATURAL JOIN "tb_favourite" NATURAL JOIN "tb_user" WHERE "tb_user"."ID_special" = :id;', [":id" => $_COOKIE['user']], "Place");
                                $activeFavourite = true;
                                foreach ($places as $place) {
                                    include 'presets/place.php';
                                }
                            ?>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $mobile ? include_once 'presets/mobileTop.php' : ""; ?>
    <?php include_once 'presets/placeModal.php'; ?>
</body>
</html>
