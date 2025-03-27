<!DOCTYPE html>
<html lang="pl">
<?php include 'presets/head.php'; ?>
<?php
    if (!isset($user) || (isset($user) && !in_array("admin", $user->roles))) {
        echo "<script>window.location.href = '/';</script>";
        die();
    }
    else {
        echo "<input type='hidden' id='adminID' value='{$user->ID_special}'>";
    }
?>
<?php $mobile = preg_match('/Mobile|Android|iPhone|iPad/', $_SERVER['HTTP_USER_AGENT']) ? true : false; ?>
<body <?= $mobile ? "class='flex-column-reverse' data-mobile='1'" : ""; ?>>
    <?php require_once 'presets/sidebar.php'; ?>

    <div id="main" class="animation fade-in-default animation-500ms">
        <div class="h-100 d-flex flex-column p-4 rounded-5 shadow">
            <h1 class="text-center">Admin panel</h1>
            <div class="container d-flex flex-column container-flex-1">
                <div class="row">
                    <div class="col-12 mb-3">
                        <input type="text" id="seachBar" class="form-control" placeholder="Search for user...">
                    </div>
                </div>

                <div id="usersDiv" class="row container-flex-1">
                    <div class="col-12">
                        <section class="scroll-box">
                            <?php
                                $users = query('SELECT *, "truckstop"."fcn__getRoles"("ID_special")  FROM "tb_user" NATURAL INNER JOIN "tb_login";', [], "User");
                                foreach ($users as $user) {
                                    include 'presets/user.php';
                                }
                            ?>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $mobile ? include_once 'presets/mobileTop.php' : ""; ?>

    <div id="modalForm" class="modal animation animation-800ms" tabindex="-1" aria-labelledby="modalUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-xxl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="modalUserLabel" class="modal-title">User ID<span id="modalID"></span></h3>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row d-flex <?php $mobile ? "flex-column" : ""; ?>">
                    <div id="userLeft" class="col-md-4">
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-center">
                                <img id="modalImage" class="img-fluid rounded" src="" alt="">
                            </div>
                            <h4>Name: <span id="modalName" class="fw-normal"></span></h4>
                            <h4>Email: <span id="modalEmail" class="fw-normal"></span></h4>
                            <h4>Company: <span id="modalCompany" class="fw-normal"></span></h4>
                            <h4 class="mb-1">Roles: <div id="modalTypes" class="d-flex flex-wrap fw-normal"></div></h4>
                            <?php
                                $roles = query('SELECT * FROM truckstop.tb_role ORDER BY "ID_role" ASC;');
                                foreach ($roles as $i => $role) {
                                    echo "
                                    <div>
                                        <input type='checkbox' id='role_{$i}' class='rolesCheckbox me-1' data-id='{$role["ID_role"]}' data-name='{$role["name_role"]}'>
                                        <label for='role_{$i}'>".ucfirst($role["name_role"])."</label>
                                    </div>";
                                }
                            ?>
                        </div>
                    </div>

                    <div id="userComments" class="col-md-8">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <h2 class="text-center">Last comments</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <section id="commentsDiv" class="scroll-box">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="delButton" type="button" class="btn btn-danger"><i class="fas fa-trash-can me-2"></i>Delete user</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>