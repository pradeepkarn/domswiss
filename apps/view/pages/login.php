<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - SB Admin</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="/<?php echo home; ?>/media/img/lizenz-logo.jpg">
        <link href="/<?php echo home; ?>/static/css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>



<div class="container mt-5" style="max-width: 800px;">
    <div class="row text-center">
        <div class="col-12 mt-5">
            <img src="/<?php echo home; ?>/media/img/logo-dom-swiss.svg" width="400px" alt="" srcset="">
        </div>
    </div>
    <div class="row mt-5 justify-content-center">
    <div class="col-6">
            <div class="login_box">
                <form class="material-form" id="user_login_form" action="/<?php echo home; ?>/login-ajax" method="POST">
                    <div class="form-group row mb-4">
                        <div class="col-12">
                            <input class="form-control valid" type="text" required="" placeholder="Username / Email" name="username" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false" style="font-size: 0.875rem;">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="col-12">
                            <input class="form-control valid" type="password" required="" placeholder="Password" name="password" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false" style="font-size: 0.875rem;">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                    <div class="col-md-12 mylog">
                        <div class="d-flex no-block align-items-center">
                            <a href="/<?php echo home; ?>/forgot-password">Forget Password</a>
                        </div>
                        <div class="ml-auto">
                        <div id="result"></div>
                            <button type="button" name="login_btn" id="mylogin_btn" class="btn btn-primary log_btn">Login</button>
                        </div>
                    </div>
                    </div>
                </form>
            </div>
        </div>  
        
    </div>
</div>
<?php pkAjax_form("#mylogin_btn","#user_login_form","#result"); ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/<?php echo home; ?>/static/js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="/<?php echo home; ?>/static/assets/demo/chart-area-demo.js"></script>
        <script src="/<?php echo home; ?>/static/assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="/<?php echo home; ?>/static/js/datatables-simple-demo.js"></script>
    </body>
</html>