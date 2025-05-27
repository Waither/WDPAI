<!DOCTYPE html>
<html lang="pl">
<?php require_once 'presets/head.php'; ?>
<?php
    if (!isset($user) || (isset($user) && !in_array("moderator", $user->roles))) {
        echo "<script>window.location.href = '/';</script>";
        die();
    }
    else {
        echo "<input type='hidden' id='moderatorID' value='{$user->ID_special}'>";
    }
?>
<body <?= $mobile ? "class='flex-column-reverse' data-mobile='1'" : ""; ?>>
    <?php require_once 'presets/sidebar.php'; ?>

    <div id="main" class="animation fade-in-default animation-500ms">
        <div class="h-100 d-flex flex-column p-4 rounded-5 shadow">
            <h1 class="text-center">Moderator panel</h1>
            <div class="container w-max-none d-flex flex-column container-flex-1">
                <div id="moderatorPanel" class="row container-flex-1">
                    <div class="col-lg-4 p-2 shadow rounded bg-light">
                        <h2 class="text-center">Confirm places</h2>
                        <div id="usersDiv" class="row container-flex-1">
                            <div class="col-12">
                                <section class="scroll-box"></section>
                            </div>
                        </div>
                    </div>
                    <?php
                        $numbers = query("SELECT (SELECT COUNT(*) FROM tb_place) AS places, (SELECT COUNT(*) FROM tb_user) AS users, (SELECT COUNT(*) FROM tb_comment WHERE accepted IS TRUE) AS comments;")[0];
                    ?>
                    <div id="moderatorPanelRight" class="col-lg-8 d-flex flex-column">
                        <div class="row d-flex p-2 pb-3 justify-content-around shadow rounded bg-light">
                            <div class="col-lg-3">
                                <h2 class="text-center mb-1">Saved places</h2>
                                <div class="d-flex justify-content-center">
                                    <div class="bg-warning text-white text-center rounded shadow fs-3 fw-bold w-fit-content px-4"><?= $numbers["places"]; ?></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <h2 class="text-center mb-1">Registered users</h2>
                                <div class="d-flex justify-content-center">
                                    <div class="bg-warning text-white text-center rounded shadow fs-3 fw-bold w-fit-content px-4"><?= $numbers["users"]; ?></div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <h2 class="text-center mb-1">New comments</h2>
                                <div class="d-flex justify-content-center">
                                    <div class="bg-warning text-white text-center rounded shadow fs-3 fw-bold w-fit-content px-4"><?= $numbers["comments"]; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-2 shadow rounded container-flex-1 bg-light">
                            <h2 class="text-center">Commends for approval</h2>
                            <div id="usersDiv" class="row container-flex-1">
                                <div class="col-12">
                                    <section class="scroll-box"></section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $mobile ? include_once 'presets/mobileTop.php' : ""; ?>
</body>
</html>
