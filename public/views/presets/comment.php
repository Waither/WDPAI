<div class="card cardPlace mb-2" data-id="<?= $comment->id; ?>">
    <div class="card-body d-flex p-0">
        <div class="d-flex w-100 p-3">
            <div class="d-flex flex-column justify-content-center">
                <div class="p-3 badge badge-secondary rounded-4 d-flex flex-column justify-content-center">
                    <i class="fas fa-comments fa-lg text-black fa-fw d-flex flex-column justify-content-center"></i>
                </div>
            </div>
            <div class="w-100 ms-2 d-flex flex-column justify-content-center">
                <h2 class="card-title mb-0"><?= $comment->place; ?> | <?= $comment->company; ?></h2>
                <p class="mb-0 fw-bold">Address: <span class="fw-normal"><?= $comment->address; ?></span></p>
                <p class="mb-0 fw-bold">Date: <span class="fw-normal"><?= $comment->date->format("Y-m-d"); ?></span></p>
                <p class="mb-0"><?= $comment->text; ?></p>
                <div><?= $comment->getRatingStars(); ?></div>
            </div>
        </div>
    </div>
</div>
