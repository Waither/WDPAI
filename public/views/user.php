<?php if (!isset($_COOKIE['user'])) header('Location: /login'); ?>
<!DOCTYPE html>
<html lang="pl">
<?php require_once 'presets/head.php'; ?>
<body <?= $mobile ? "class='flex-column-reverse' data-mobile='1'" : ""; ?>>
    <?php require_once 'presets/sidebar.php'; ?>

    <?php
        $comments = query('SELECT * FROM truckstop.vw_comment WHERE "user" = :user ORDER BY date DESC LIMIT 10;', [':user' => $user->name], "Comment");
        $commentsCount = query('SELECT COUNT(*) AS "count" FROM truckstop.tb_comment WHERE "ID_user" = :id AND accepted = true;', [':id' => $user->ID_user])[0]['count'];
    ?>

    <div id="main" class="animation fade-in-default animation-500ms">
        <div class="h-100 d-flex p-4 rounded-5 shadow flex-wrap <?= $mobile ? "flex-column" : ""; ?>">
            <div id="placeLeft" class="col-12 col-md-4 d-flex flex-column <?= $mobile ? "h-max-content" : ""; ?>">
                <div class="d-flex flex-column">
                    <div class="d-flex justify-content-center <?= $mobile ? "d-none" : ""; ?>">
                        <img id="modalImage" class="img-fluid rounded" src="" alt="User Image">
                    </div>
                    <h4>Name: <span class="fw-normal"><?= $user->name; ?></span></h4>
                    <h4>Company: <span class="fw-normal"><?= $user->company ?? "no company"; ?></span></h4>
                    <h4>Email: <span class="fw-normal"><?= $user->email; ?></span></h4>
                    <h4>Nationality: <span class="fw-normal"><?= $user->nationality; ?></span></h4>
                    <h4>Comments: <span class="fw-normal"><?= $commentsCount; ?></span></h4>
                    <div id="modalRating"></div>
                </div>

                <div class="d-flex flex-column <?= !$mobile ? "me-3 mt-auto" : ""; ?>">
                    <?php
                        if ($mobile) {
                            $roles = $user->getRoles();
                            $buttons = [
                                'moderator' => [
                                    'url' => '/moderator',
                                    'icon' => 'fas fa-user-cog',
                                    'label' => 'Moderator Panel'
                                ],
                                'admin' => [
                                    'url' => '/admin',
                                    'icon' => 'fas fa-user-shield',
                                    'label' => 'Admin Panel'
                                ]
                            ];

                            foreach ($buttons as $role => $button) {
                                if (in_array($role, $roles)) {
                                    ?>
                                    <button type="button" class="btn btn-primary fs-6 mb-3" onclick="window.location.href='<?= $button['url'] ?>'">
                                        <i class="<?= $button['icon'] ?> me-2"></i><?= $button['label'] ?>
                                    </button>
                                    <?php
                                }
                            }
                        }
                    ?>

                    <button type="button" id="logout" class="btn btn-danger fs-6"><i class="fas fa-sign-out me-2"></i>Logout</button>
                </div>
            </div>

            <div id="placeComments" class="col-12 col-md-8 d-flex container-flex-1 <?= $mobile ? "mt-3" : ""; ?>">
                <div class="container d-flex flex-column">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="text-center">Last comments</h2>
                        </div>
                    </div>
                    <div class="row d-flex container-flex-1">
                        <div class="col-12 d-flex flex-column scroll-box">
                            <?php
                                foreach ($comments as $comment) {
                                    include 'presets/comment.php';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $mobile ? include_once 'presets/mobileTop.php' : ""; ?>
</body>
</html>