<!DOCTYPE html>
<html lang="pl">
<?php include 'presets/head.php'; ?>
<?php $mobile = preg_match('/Mobile|Android|iPhone|iPad/', $_SERVER['HTTP_USER_AGENT']) ? true : false; ?>
<body <?= $mobile ? "class='flex-column-reverse'" : ""; ?>>
    <?php require_once 'presets/sidebar.php'; ?>

    <div id="main">
        <div class="h-100 d-flex flex-column p-4 rounded-5 shadow">
            <h1 class="text-center">Map</h1>
            <div id="containerMap" class="container d-flex flex-column">
                <div class="row">
                    <div class="col-12 mb-3">
                        SEARCH BAR
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div id="map" class="rounded-5">
                            <script>
                                (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({ key: "AIzaSyD7kG0WIy9bhFmVKV_qhrLkVwL-0Mt1CXM", v: "weekly" });

                                let map;
                                
                                async function getLocation() {
                                    return new Promise((resolve, reject) => {
                                        if (navigator.geolocation) {
                                            navigator.geolocation.getCurrentPosition(
                                                (position) => {
                                                    resolve({
                                                        lat: position.coords.latitude,
                                                        lng: position.coords.longitude
                                                    });
                                                },
                                                () => {
                                                    console.log("User denied the request for Geolocation.");
                                                    resolve({
                                                        lat: 52.210,
                                                        lng: 20.982
                                                    });
                                                }
                                            );
                                        }
                                        else {
                                            reject(new Error("Geolocation is not supported by this browser."));
                                        }
                                    });
                                }

                                async function initMap() {
                                    const { Map } = await google.maps.importLibrary("maps");
                                    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

                                    const position = await getLocation();

                                    map = new Map(document.getElementById("map"), {
                                        zoom: 4,
                                        center: position,
                                        mapId: "DEMO_MAP_ID",
                                    });

                                    const marker = new AdvancedMarkerElement({
                                        map: map,
                                        position: { lat: -25.344, lng: 131.031 },
                                        title: "Uluru",
                                    });
                                }

                                initMap();
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>