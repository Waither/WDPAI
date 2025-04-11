<div class="card cardPlace" data-id="<?= $place->getId(); ?>">
    <div class="card-body d-flex p-0">
        <div class="d-flex w-100 p-3">
            <div class="d-flex flex-column justify-content-center">
                <div class="p-3 badge badge-secondary rounded-4 d-flex flex-column justify-content-center">
                    <i class="fas fa-city fa-lg text-black fa-fw d-flex flex-column justify-content-center"></i>
                </div>
            </div>
            <div class="w-100 ms-2 d-flex flex-column justify-content-center">
                <h2 class="card-title mb-0"><?= $place->getName(); ?></h2>
                <p class="mb-0"><?= $place->getCompany(); ?></p>
                <p class="mb-0"><?= $place->getAddress(); ?></p>
                <div><?= $place->getRatingStars(); ?></div>
            </div>
        </div>
        <div>
            <img class="h-100" src="https://place-hold.it/1321x583?text=Loading" alt="">
        </div>
    </div>
</div>
