<?php if (isset($_COOKIE['user'])) header('Location: /'); ?>
<!DOCTYPE html>
<html lang="pl">
<?php include 'presets/head.php'; ?>
<body <?= $mobile ? "class='flex-column-reverse' data-mobile='1'" : ""; ?>>
    <div id="main" class="d-flex flex-column justify-content-center">
        <div id="loginForm" class="container d-flex flex-column rounded-5 shadow pt-3 pb-5">
            <img src="/public/img/logo.png" alt="Logo">
            <h1 class="text-center fs-1 text-capitalize">Register</h1>
            <form action="" class="d-flex flex-column gap-sm-3" novalidate>
                <div class="container">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control form-control-lg" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control form-control-lg" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control form-control-lg" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control form-control-lg" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <button type="submit" class="btn btn-block btn-primary fw-bold fs-4 shadow"><i class="fas fa-pen me-2"></i>Sign Up</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="d-flex flex-column text-center">
                <div class="d-flex justify-content-center">
                    <a href="/login" class="text-decoration-none mb-1">Already have an account? Login here</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>