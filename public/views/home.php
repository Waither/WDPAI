<!DOCTYPE html>
<html lang="pl">
<head>
    <title>TruckStopInfo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Logo -->
    <link rel="icon" href="/public/img/logo.png" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="/public/styles/main.css">
    <link rel="stylesheet" href="/public/styles/icons/fontawesome-free-6.7.2-web/css/fontawesome.min.css">

    <!-- JavaScript -->
    <script src="/public/scripts/main.js" type="module"></script>
</head>
<body>
    <?php require __DIR__.'/presets/menu.html'; ?>
    
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-8">
                <label class="form-label" for="formControl">Form control lg</label>
                <input type="text" id="formControl" class="form-control" />
            </div>
        </div>
    </div>

    <div class="form-outline" data-mdb-input-init>
        <input type="text" id="formControlLg2" class="form-control form-control-lg" />
        <label class="form-label" for="formControlLg2">Form control lg</label>
    </div>
    
    <select class="form-select" name="" id="">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
    </select>
    
    
    <!--
        Przykładowe wartości $_SERVER['HTTP_USER_AGENT'] dla różnych przeglądarek:
        
        Chrome (Windows 10)
        Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36

        Chrome (Android)
        Mozilla/5.0 (Linux; Android 13; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36

        Firefox (Windows 10)
        Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0

        Firefox (Android)
        Mozilla/5.0 (Android 13; Mobile; rv:123.0) Gecko/123.0 Firefox/123.0

        Edge (Windows 10)
        Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 Edg/120.0.0.0

        Safari (Mac)
        Mozilla/5.0 (Macintosh; Intel Mac OS X 13_3) AppleWebKit/537.36 (KHTML, like Gecko) Version/16.3 Safari/537.36

        Safari (iPhone)
        Mozilla/5.0 (iPhone; CPU iPhone OS 16_3 like Mac OS X) AppleWebKit/537.36 (KHTML, like Gecko) Version/16.3 Mobile/15E148 Safari/537.36 
    -->
</body>
</html>