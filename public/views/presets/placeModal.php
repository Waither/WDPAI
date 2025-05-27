<div id="modalForm" class="modal animation animation-800ms" tabindex="-1" aria-labelledby="modalPlaceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-xxl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalPlaceLabel" class="modal-title">Place ID<span id="modalID"></span></h3>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row d-flex">
                <div id="placeLeft" class="col-md-4">
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-center">
                            <img id="modalImage" class="img-fluid rounded" src="" alt="Place main">
                        </div>
                        <h4>Name: <span id="modalName" class="fw-normal"></span></h4>
                        <h4>Company: <span id="modalCompany" class="fw-normal"></span></h4>
                        <h4>Category: <div id="modalTypes" class="d-flex flex-wrap fw-normal"></div></h4>
                        <h4>Address: <span id="modalAddress" class="fw-normal"></span></h4>
                        <h4>Tags: <div id="modalTags" class="d-flex flex-wrap fw-normal"></div></h4>
                        <div class="d-flex <?= $mobile ? "flex-column" : ""; ?> justify-content-between mt-2">
                            <div class="d-flex flex-column justify-content-center">
                                <div class="d-flex">
                                    <h4 class="me-2 mb-0">Rating:</h4><div id="modalRating"></div>
                                </div>
                            </div>
                            <div class="<?= $mobile ? "mt-2 d-flex justify-content-center" : ""; ?>">
                                <button id="addToFavourite" class="favorite-btn <?= isset($activeFavourite) ? "active" : ""; ?>"><i class="fa-solid fa-heart me-1"></i><span><?= isset($activeFavourite) ? 'Delete from Favourite' : 'Add to Favourite' ?></span></button>
                            </div>
                        </div>

                        <h4 class="text-center">Place on map</h4>
                        <div id="map" class="rounded-5">
                            <script>
                                (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({ key: "<?= getenv("APIKEY"); ?>", v: "weekly" });
                            </script>
                        </div>
                    </div>
                </div>

                <div id="placeComments" class="col-md-8">
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
                                        <div class="spinner-border">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
