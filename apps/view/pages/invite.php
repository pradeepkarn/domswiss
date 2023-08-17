<?php 
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
?>
<div id="layoutSidenav">
<?php import("apps/view/inc/sidebar.php"); ?>
<div id="layoutSidenav_content">


<?php 
$my_username = null;

if (isset($_SESSION['user_id'])) {
    $userid = $_SESSION['user_id'];
    $invite = getData("pk_user",$userid);
    $my_username = $invite!=false?$invite['username']:null;
}
if (authenticate()==true) {
    $userObj = new Model('pk_user');

$arr=null;
$arr['ref'] = $_SESSION['user_id'];
$partner = $userObj->filter_index($assoc_arr=$arr,$ord = 'DESC',$limit = 2,              $change_order_by_col= "");
}
if(count($partner)>=8){
    echo go_to("");
    return;
}
?>


                <main>
                    <div class="container-fluid px-4">
                        <!-- <h1 class="mt-4">Dashboard</h1> -->
                        <ol class="breadcrumb mt-3 mb-4">
                            <li class="breadcrumb-item active">Affiliate Registration</li>
                        </ol>

                        <div class="container">
                            <div class="row mb-4">
                                <div class="col-6">
                                <div class="my-form">
                                    <h4 class="mb-3">Invitation details</h4>
                                    <form class="material-form" id="invite_form" action="/<?php echo home; ?>/invite-ajax" method="POST">
                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                        <input class="form-control valid" type="text" required="" placeholder="Inviting person's username" name="name" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $my_username; ?>" readonly aria-required="true" aria-invalid="false">
                                        </div>
                                        <hr style="padding: 0px 20px; width: 100%; margin-left: -4px; border-top: 1px dotted rgba(0,0,0,0.6); margin-top: 35px; margin-bottom: 30px;">
                                        
                                        <input type="hidden" name="ref" value="<?php echo $_SESSION['user_id']; ?>">
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
                                        <input type="date" min="1860-01-01" max="2023-04-07" class="form-control" placeholder="Date of Birth YYYY-MM-DD" id="birthday" name="birthday" required="" data-validation-required-message="Das ist ein Pflichtfeld" aria-required="true">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-12">
                                        <input class="form-control valid" type="text" required="" placeholder="Address 1" name="address_1" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-12">
                                        <input class="form-control valid" type="text" required="" placeholder="Address 2 (optional)" name="address_1" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                        <select name="country" class="form-control" id="">
                        <?php
                         $json_data = file_get_contents("./jsondata/country.json");
                         $items = json_decode($json_data, true);
                         if (count($items) !=0) {
                             foreach ($items as $item) {
                        ?>
                        
                          <option selected value="<?= $item['name']; ?>"><?= $item['name']; ?></option>
                        <?php 
                             }
                            }
                        ?>
                      </select>
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
                                        <select name="country_code" class="form-control" id="">
                        <?php
                         $json_data = file_get_contents("./jsondata/phonecode.json");
                         $items = json_decode($json_data, true);
                         if (count($items) !=0) {
                             foreach ($items as $item) {
                        ?>
                        
                          <option selected value="<?= $item['dial_code']; ?>"><?= $item['dial_code']; ?></option>
                        <?php 
                             }
                            }
                        ?>
                      </select>
                                        </div>
                                        <div class="col-lg-6">
                                        <input class="form-control valid" type="text" required="" placeholder="phone number" name="mobile" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                        
                                        <hr style="padding: 0px 20px; width: 100%; margin-left: -4px; border-top: 1px dotted rgba(0,0,0,0.6); margin-top: 35px; margin-bottom: 30px;">
                                    </div>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox">
                                        <label for="">I have read and accept the terms and conditions.</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox">
                                        <label for="">I have read and accepted the data security declaration.</label>
                                    </div>
                                    <div id="inv"></div>
                                    <button id="myinvite_btn" class="btn btn-light btn-block" name="invite_btn" type="button">FURTHER</button>
                                    </form>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    
<?php pkAjax_form("#myinvite_btn","#invite_form","#inv"); ?>
                       
                </main>
                <?php import("apps/view/inc/footer-credit.php");?>
            </div>
</div>
<?php 
import("apps/view/inc/footer.php");
?>