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


<?php
$sponserid = "";
$userid = 1;
$spons_username = null;

if (isset($_GET['sponserid']) && strval($_GET['sponserid'])) {
    $sponserid = $_GET['sponserid'];
    $userobj = new Model('pk_user');
    $user = $userobj->filter_index(['username' => $sponserid]);
    $userid = count($user) > 0 ? $user[0]['id'] : 1;
    // myprint($sponserid);
    // $spons = getData("pk_user",$sponserid);
    // $spons_username = $spons!=false?$spons['username']:null;
}
// $userObj = new Model('pk_user');
// $partner = $userObj->filter_index(array('is_active' => true));
// if(count($partner)>=8){
//     echo go_to("");
//     return;
// }
?>


<div class="container mt-5" style="max-width: 800px;">
    <div class="row text-center">
        <div class="col-12">
            <img src="/<?php echo home; ?>/media/img/logo-dom-swiss.svg" width="260px" alt="" srcset="">
        </div>
        <div class="card justify-content-between mt-5">
            <div class="card-header"></div>
            <div class="card-body">
                <p style="color: #212529; font-size: 14px; font-weight: 300;">”Our future prosperity will be measured above all by well-being, the time we have and the freedom to do things that previously only existed as dreams. All of these things don't have to remain a luxury, we make them a matter of course."</p>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="my-para1">
                <p>Our products are inspired by nature and our sales channel is fair and direct!</p>
                <p>We are currently finalizing our preparation and are now preparing for our launch. If YOU would like to become an important part of our network, then register for more information and then find out how to proceed.</p>
                <p>You consent to the use of the information provided on this form to be in touch with you and to provide you with updates on our launch and marketing information.</p>
                <p>Please let us know how you would like to hear from us:</p>
            </div>
        </div>
        <div class="my-form">

            <h4 class="mb-3">Invitation details</h4>
            <form class="material-form" id="user_sign_form" action="/<?php echo home; ?>/signup-ajax" method="POST">
                <div class="form-group row">
                    <div class="col-lg-6">
                        <input class="form-control valid" type="text" required="" placeholder="Inviting person's username" name="inviting_user" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $sponserid; ?>" readonly aria-required="true" aria-invalid="false">
                    </div>
                    <hr style="padding: 0px 20px; width: 100%; margin-left: -4px; border-top: 1px dotted rgba(0,0,0,0.6); margin-top: 35px; margin-bottom: 30px;">
                    <?php if ($userid != 0 && intval($userid)) : ?>
                        <input type="hidden" name="ref" value="<?php echo $userid; ?>">
                    <?php endif; ?>
                </div>
                <h4 class="mb-3">Account details</h4>
                <div class="form-group row mb-4">
                    <div class="col-lg-6">
                        <input class="form-control valid" type="text" required="" placeholder="User name" name="username" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <div class="col-lg-6">
                        <input class="form-control valid" type="email" required="" placeholder="e-mail" name="email" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control valid" type="email" required="" placeholder="Repeat E-mail" name="cnuser_email" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-6">
                        <input class="form-control valid" type="password" required="" placeholder="password" name="password" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control valid" type="password" required="" placeholder="Repeat password" name="cnf_password" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                    <hr style="padding: 0px 20px; width: 100%; margin-left: -4px; border-top: 1px dotted rgba(0,0,0,0.6); margin-top: 35px; margin-bottom: 30px;">
                </div>
                <h4 class="mb-3">Profile Setup</h4>
                <div class="form-group row mb-4">
                    <div class="col-lg-7">
                        <input class="form-control valid" type="text" required="" placeholder="Company name (optional)" name="company_name" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                    <div class="col-lg-5">
                        <input class="form-control valid" type="text" required="" placeholder="Tax number (optional)" name="tax_no" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <div class="col-lg-6">
                        <input class="form-control valid" type="text" required="" placeholder="First name" name="first_name" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control valid" type="text" required="" placeholder="Last name" name="last_name" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <div class="col-lg-4">
                        <select name="gender" class="form-control" required="" data-validation-required-message="Das ist ein Pflichtfeld" aria-required="true">
                            <option value="f">Feminine</option>
                            <option value="m">Masculine</option>
                            <option value="o">Not Specified</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <input type="date" class="form-control" placeholder="Date of Birth YYYY-MM-DD" id="birthday" name="birthday" required="" data-validation-required-message="Das ist ein Pflichtfeld" aria-required="true">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <div class="col-lg-12">
                        <input class="form-control valid" type="text" required="" placeholder="Address 1" name="address" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <div class="col-lg-12">
                        <input class="form-control valid" type="text" required="" placeholder="Address 2 (optional)" name="address2" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <div class="col-lg-6">
                        <input class="form-control my-2 valid" placeholder="Search Your Country" type="text" id="cntrysrch" name="key_ctry">
                        <div id="res-cntr">
                            <select name="country" class="form-control">
                                <?php
                                // $json_data = file_get_contents("./jsondata/country.json");
                                // $items = json_decode($json_data, true);
                                $plobj = new Model('countries');
                                $items = $plobj->index();
                                if (count($items) != 0) {
                                    foreach ($items as $item) {
                                ?>
                                        <option selected value="<?= $item['id']; ?>"><?= $item['name']; ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <?php pkAjax("#cntrysrch", "/country-search-ajax", "#cntrysrch", "#res-cntr", 'keyup'); ?>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control valid" type="text" required="" placeholder="State" name="state" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <div class="col-lg-4">
                        <input class="form-control valid" type="text" required="" placeholder="Postal code" name="zipcode" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                    <div class="col-lg-8">
                        <input class="form-control valid" type="text" required="" placeholder="city" name="city" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <div class="col-lg-2">
                        <input class="form-control my-2 valid" placeholder="Phone Code" type="text" id="phonesrch" name="key_ctry_code">
                        <div id="res-code">
                            <select name="country_code" class="form-control" id="">
                                <?php
                                $json_data = file_get_contents("./jsondata/phonecode.json");
                                $items = json_decode($json_data, true);
                                if (count($items) != 0) {
                                    foreach ($items as $item) {
                                ?>

                                        <option selected value="<?= $item['dial_code']; ?>"><?= $item['dial_code']; ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <?php pkAjax("#phonesrch", "/country-code-search-ajax", "#phonesrch", "#res-code", 'keyup'); ?>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control valid" type="text" required="" placeholder="phone number" name="mobile" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                    </div>
                    <hr style="padding: 0px 20px; width: 100%; margin-left: -4px; border-top: 1px dotted rgba(0,0,0,0.6); margin-top: 35px; margin-bottom: 30px;">
                </div>
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox">
                    <label for="">I have read and accept <a href="/<?php echo home; ?>/terms-and-conditions" target="_blank">terms and conditions</a>.</label>
                </div>
                
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox">
                    <label for="">I have read the <a href="/<?php echo home; ?>/privacy-policy" target="_blank">Privacy Policy</a>.</label>
                </div>
                <div id="res"></div>
               
                <button id="mysignup_btn" class="btn btn-light btn-block" name="signup_btn" type="button">FURTHER</button>
            </form>
        </div>
    </div>
</div>
<?php pkAjax_form("#mysignup_btn", "#user_sign_form", "#res", 'click');
ajaxActive(".spinner-border");
?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="/<?php echo home; ?>/static/js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="/<?php echo home; ?>/static/assets/demo/chart-area-demo.js"></script>
<script src="/<?php echo home; ?>/static/assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="/<?php echo home; ?>/static/js/datatables-simple-demo.js"></script>
</body>

</html>