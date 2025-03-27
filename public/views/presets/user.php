<div class="card cardPlace" data-id="<?= $user->ID_special; ?>" data-name="<?= $user->name; ?>" data-email="<?= $user->email; ?>" data-roles="<?= implode(',', $user->roles); ?>">
    <div class="card-body d-flex p-0">
        <div class="d-flex w-100 p-3">
            <div class="d-flex flex-column justify-content-center">
                <div class="p-3 badge badge-secondary rounded-4 d-flex flex-column justify-content-center">
                    <i class="fas fa-user fa-lg text-black fa-fw d-flex flex-column justify-content-center"></i>
                </div>
            </div>
            <div class="w-100 ms-2 d-flex flex-column justify-content-center">
                <h2 class="card-title mb-0">Name: <?= $user->name; ?></h2>
                <p class="mb-0">Email: <?= $user->email; ?></p>
                <p class="mb-0">Type: <span class="roles"><?= count($user->roles) && $user->roles[0] ? implode(', ', $user->roles) : "normal"; ?></span></p>
            </div>
        </div>
    </div>
</div>