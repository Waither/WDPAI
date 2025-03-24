<!DOCTYPE html>
<html lang="pl">
<?php include 'presets/head.php'; ?>
<?php $mobile = preg_match('/Mobile|Android|iPhone|iPad/', $_SERVER['HTTP_USER_AGENT']) ? true : false; ?>
<body <?= $mobile ? "class='flex-column-reverse' data-mobile='1'" : ""; ?>>
    <?php require_once 'presets/sidebar.php'; ?>

    <div id="main" class="animation fade-in-default animation-500ms">
        <div class="h-100 d-flex flex-column p-4 rounded-5 shadow">
            <h1 class="text-center">Map</h1>
            <div id="containerMap" class="container d-flex flex-column container-flex-1">
                <div class="row">
                    <div class="col-12 mb-3">
                        <input type="text" id="seachBar" class="form-control" placeholder="Search for places...">
                    </div>
                </div>
                <div class="row container-flex-1">
                    <div class="col-12 d-flex h-100">
                        <!-- NO API KEY -->
                        <div id="map" class="rounded-5 container-flex-1">
                            <script>
                                (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({ key: "<?= getenv("APIKEY"); ?>", v: "weekly" });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $mobile ? include_once 'presets/mobileTop.php' : ""; ?>
</body>
</html>