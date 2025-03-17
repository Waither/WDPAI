<!DOCTYPE html>
<html lang="pl">
<?php include 'presets/head.php'; ?>
<?php $mobile = preg_match('/Mobile|Android|iPhone|iPad/', $_SERVER['HTTP_USER_AGENT']) ? true : false; ?>
<body <?= $mobile ? "class='flex-column-reverse'" : ""; ?>>
    <?php require_once 'presets/sidebar.php'; ?>

    <div id="main">
        <div class="h-100 d-flex flex-column p-4 rounded-5 shadow">
            <h1 class="text-center">Places</h1>
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-3">
                        <input type="text" id="seachBar" class="form-control" placeholder="Search for places...">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?php
                            require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/Place.php';
                            require_once $_SERVER['DOCUMENT_ROOT'].'/public/classes/DBconnect.php';
                            
                            $places = query("SELECT * FROM tb_places;", "Place");
                            var_dump($places);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>