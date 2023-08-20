<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
if (!authenticate()) {
    die('Login required');
}
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <!-- <h1 class="mt-4">Dashboard</h1> -->
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active mycl">My Profile</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-lg-4">
                            <div class="spl-box">
                                <div class="new-box">
                                    <?php
                                    //  $dms = new Domswiss_tree_ctrl;
                                    //  $date = last_active_date($user_id=$_SESSION['user_id']);
                                    //  $mnger = $dms->handle_rv($user_id=USER['id'],$last_pmt=$date);
                                    //   echo total_bonus(user_id:USER['id']);
                                    ?>
                                    <img src="/<?php echo home; ?>/media/img/user-blank.png" class="img-circle" width="150px" alt="" srcset="">
                                    <!-- <h3 class="mt-2 mypl"><?php // echo $mnger; 
                                                                ?></h4> -->
                                    <h4 class="mt-2 mypl"><?php echo USER['username']; ?></h4>
                                    <h6 class="mypl1 mb-3"><?php echo USER['first_name']; ?> <?php echo USER['last_name']; ?></h6>
                                    <p class="mypl1">ID: <span><?php echo USER['id']; ?></span></p>
                                    <hr class="mypr">
                                </div>
                                <div class="tab">
                                    <button class="btn tablinks" onclick="openCity(event, 'profiles')" id="defaultOpen">Profiles</button>
                                    <button class="btn tablinks" onclick="openCity(event, 'wallet')" id="defaultOpen">Wallet</button>
                                    <!-- <button class="btn tablinks" onclick="openCity(event, 'settings')" id="defaultOpen">Personalized settings</button> -->
                                    <button class="btn tablinks" onclick="openCity(event, 'documents')" id="defaultOpen">KYC Documents</button>
                                    <button class="btn tablinks" onclick="openCity(event, 'referral')" id="defaultOpen">Referral List</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="spl-box">
                                <div id="profiles" class="tabcontent">
                                    <div class="card">
                                        <div class="card-header">
                                            <p class="my-pr">Profiles</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="nav_tabs">
                                                <button class="btn tablinks1" onclick="openProfile(event, 'account')" id="defaultOpen1">@ account</button>

                                                <button class="btn tablinks1" onclick="openProfile(event, 'personaldata')" id="defaultOpen1"><i class="bi bi-person"></i> primary address</button>

                                                <button class="btn tablinks1" onclick="openProfile(event, 'deliveryaddress')" id="defaultOpen1"><i class="bi bi-person"></i> all addresses</button>

                                                <button class="btn tablinks1" onclick="openProfile(event, 'bankaccounts')" id="defaultOpen1"><i class="bi bi-person"></i> bank accounts</button>

                                                <button class="btn tablinks1" onclick="openProfile(event, 'security')" id="defaultOpen1"><i class="bi bi-person"></i> security</button>
                                            </div>
                                            <div class="row mt-3 mb-3">
                                                <div id="account" class="tabcontent1">
                                                    <div class="col-lg-12">
                                                        <?php
                                                        // $slaeCtrl = new Sales_ctrl;
                                                        // $sale = $slaeCtrl->get_sale_volume();
                                                        // $shrdata = $slaeCtrl->distribute_share_by_pool($total_sale = 10000);
                                                        // myprint($shrdata);

                                                        $uctrl = new User_ctrl;
                                                      
                                                        $am_i_active = $uctrl->am_i_active(USER['id'])['active'];

                                                        // $pvctrl = new Pv_ctrl;
                                                        // $im_active = false;
                                                        // $dtt = $pvctrl->my_tree(USER['id'],1,$am_i_active);
                                                        // $dtts = $pvctrl->calculate_sum($dtt,1,10);
                                                        // // myprint($dtts);
                                                        
                                                        
                                                        ?>
                                                        <h5>Basic Info</h5>
                                                        <div class="box_r">
                                                            <div id="update-result"></div>
                                                            <form action="/<?php echo home; ?>/update-personal-data" id="update-p-data-form" method="post">
                                                                <!-- Form starts -->
                                                                <div class="row">
                                                                    <div class="col-8 new_boxn">
                                                                        <h6 class="m-0">id:</h6>
                                                                    </div>
                                                                    <div class="col-4 new_boxn">
                                                                        <span><?php echo USER['id']; ?></span>
                                                                    </div>
                                                                    <div class="col-8 new_boxn">
                                                                        <h6 class="m-0">Username:</h6>
                                                                    </div>
                                                                    <div class="col-4 new_boxn">
                                                                        <span><?php echo USER['username']; ?></span>
                                                                    </div>
                                                                    <div class="col-8 new_boxn">
                                                                        <h6 class="m-0">Sponser Username:</h6>
                                                                    </div>
                                                                    <div class="col-4 new_boxn">
                                                                        <span><?php echo sponser_username(USER['ref']); ?></span>
                                                                    </div>



                                                                    <div class="col-md-6">
                                                                        <label for="">First name</label>
                                                                        <input type="text" name="first_name" value="<?php echo USER['first_name']; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="">Last name</label>
                                                                        <input type="text" name="last_name" value="<?php echo USER['last_name']; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="">City</label>
                                                                        <input type="text" name="city" value="<?php echo USER['city']; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="">State</label>
                                                                        <input type="text" name="state" value="<?php echo USER['state']; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="">Zip code</label>
                                                                        <input type="text" name="zipcode" value="<?php echo USER['zipcode']; ?>" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="">Country</label>
                                                                        <select name="country_code" class="form-select">
                                                                            <?php
                                                                            // $json_data = file_get_contents("./jsondata/country.json");
                                                                            // $countries = json_decode($json_data);
                                                                            $plobj = new Model('countries');
                                                                            $countries = $plobj->index();
                                                                            foreach ($countries as $cnt) :
                                                                                $cnt = obj($cnt);
                                                                            ?>
                                                                                <option <?php echo USER['country_code'] == $cnt->code ? 'selected' : null; ?> value="<?php echo $cnt->code; ?>"><?php echo $cnt->name; ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-8 new_boxn">
                                                                        <h6 class="m-0">Status:</h6>
                                                                    </div>
                                                                    <div class="col-4 new_boxn">
                                                                        <span class="text-success"><?php echo USER['is_active'] == 1 ? 'Active' : 'In active'; ?></span>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <button id="update-p-data-btn" type="button" class="btn btn-primary">Update</button>
                                                                        <?php pkAjax_form("#update-p-data-btn", "#update-p-data-form", "#update-result"); ?>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            <!-- Form end -->
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div id="personaldata" class="tabcontent1">
                                                        <h5>Shipping Address</h5>
                                                        <?php $addrs = get_my_primary_address(USER['id']);
                                                        if ($addrs) { ?>


                                                            <form class="material-form pt-2" id="update-address" action="/<?php echo home; ?>/update-address-ajax" method="POST">
                                                                <div class="form-group row mb-4">
                                                                    <div class="col-lg-7">
                                                                        <input type="hidden" name="id" value="<?php echo $addrs->id; ?>">
                                                                        <input class="form-control valid" type="text" required="" placeholder="" name="person_name" value="<?php echo $addrs->name; ?>" aria-required="true" aria-invalid="false">
                                                                    </div>
                                                                    <div class="col-lg-5">
                                                                        <input class="form-control valid" type="text" required="" placeholder="Inviting person's username" name="state" value="<?php echo $addrs->state; ?>" aria-required="true" aria-invalid="false">
                                                                    </div>

                                                                </div>
                                                                <div class="form-group row mb-4">
                                                                    <div class="col-lg-12">
                                                                        <input class="form-control valid" type="text" required="" placeholder="Your Address" name="address_name" value="<?php echo $addrs->address_name; ?>" aria-required="true" aria-invalid="false">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row mb-4">

                                                                    <div class="col-md-12 my-3">
                                                                        <label for="">Country</label>
                                                                        <select name="country_code" class="form-select">
                                                                            <?php
                                                                            // $json_data = file_get_contents("./jsondata/country.json");
                                                                            // $countries = json_decode($json_data);
                                                                            $plobj = new Model('countries');
                                                                            $countries = $plobj->index();
                                                                            foreach ($countries as $cnt) :
                                                                                $cnt = obj($cnt);
                                                                            ?>
                                                                                <option <?php echo $addrs->country_code == $cnt->code ? 'selected' : null; ?> value="<?php echo $cnt->code; ?>"><?php echo $cnt->name; ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>



                                                                    <div class="col-md-6">
                                                                        <input class="form-control valid" type="text" required="" placeholder="Street (Optional)" name="street" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $addrs->street; ?>" aria-required="true" aria-invalid="false">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input class="form-control valid" type="text" required="" placeholder="Your City" name="city" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $addrs->city; ?>" aria-required="true" aria-invalid="false">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row mb-4">
                                                                    <div class="col-lg-8">
                                                                        <input class="form-control valid" type="text" required="" placeholder="Zip-Code" name="zipcode" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $addrs->zipcode; ?>" aria-required="true" aria-invalid="false">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-lg-2">
                                                                        <input class="form-control valid" placeholder="ISD Code" type="text" name="isd_code" value="<?php echo $addrs->isd_code; ?>">
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <input class="form-control valid" type="number" required="" placeholder="Mobile" name="mobile" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $addrs->mobile; ?>" aria-required="true" aria-invalid="false">
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 mb-4">
                                                                </div>


                                                            </form>

                                                            <div id="up_add"></div>
                                                            <button id="up_add_btn" class="btn btn-primary kyc_btn mt-3"><a href="">Save</a></button>
                                                            <?php pkAjax_form("#up_add_btn", "#update-address", "#up_add"); ?>
                                                        <?php } else {  ?>
                                                            <button class="btn btn-primary kyc_btn mt-3" onclick="openProfile(event, 'deliveryaddress')"> Set or add primary address</button>
                                                        <?php   }
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div id="deliveryaddress" class="tabcontent1">
                                                        <div class="deliver_add">
                                                            <h5>delivery addresses</h5>
                                                            <!-- Button trigger modal -->
                                                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">New shipping-address</button>
                                                        </div>
                                                        <form class="material-form pt-2">
                                                            <div class="form-group row">
                                                                <div class="col-lg-12">

                                                                    <div class="form-group my-3" style="height: 200px; overflow-y:scroll; background-color:white;">
                                                                        <?php
                                                                        $addrs = new Model('address');
                                                                        $myaddress_list = $addrs->filter_index(['user_id' => $_SESSION['user_id']]);
                                                                        foreach ($myaddress_list as $ad) {
                                                                            $ad = (object) $ad;
                                                                        ?>

                                                                            <input <?php echo $ad->address_type == 'primary' ? 'checked' : null; ?> type="radio" class="addrs-data" name="pr_address" value="<?php echo $ad->id; ?>">
                                                                            <?php echo $ad->address_type != "" ? "(<b>$ad->address_type</b>)" : null; ?> <?php echo $ad->city; ?> - <?php echo $ad->state; ?>, <?php echo $ad->name; ?> <?php echo $ad->zipcode; ?> <br>

                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <div id="res"></div>
                                                                </div>
                                                            </div>
                                                            <button id="cnfrmadrs-btn" class="btnmodal btn-primary kyc_btn mt-3"><a href="">Save</a></button>
                                                            <?php pkAjax("#cnfrmadrs-btn", "/primary-address-ajax", ".addrs-data", "#res"); ?>
                                                        </form>



                                                        <!-- Modal -->
                                                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form class="material-form" id="address_form" action="/<?php echo home; ?>/new-address-ajax" method="POST">
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <label for="">Name</label>
                                                                                    <input type="text" class="form-control my-2 valid" placeholder="" name="ad_name">
                                                                                </div>
                                                                                <div class="col">
                                                                                    <label for="">Address Name</label>
                                                                                    <input type="text" class="form-control my-2 valid" placeholder="" name="address">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <label for="">ISD Code</label>
                                                                                    <input class="form-control my-2 valid" placeholder="Search phone Code" type="text" id="phonesrch" name="key_ctry_code">
                                                                                    <div id="res-code">
                                                                                        <select name="country_code" class="form-select">
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
                                                                                <div class="col">
                                                                                    <label for="">Mobile</label>
                                                                                    <input type="number" class="form-control my-2 valid" placeholder="" name="mobile">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mt-2">
                                                                                <div class="col">
                                                                                    <label for="">City</label>
                                                                                    <input type="text" class="form-control my-2 valid" placeholder="" name="city">
                                                                                </div>

                                                                                <div class="col">
                                                                                    <label for="">State</label>
                                                                                    <input type="text" class="form-control my-2 valid" placeholder="" name="state">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <label for="">Street(Optional)</label>
                                                                                    <input type="text" class="form-control my-2 valid" placeholder="" name="street">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <label for="">Country</label>
                                                                                    <input class="form-control my-2 valid" type="text" placeholder="Search country" id="cntrysrch" name="key_ctry">
                                                                                    <div id="res-cntr">
                                                                                        <select name="country" class="form-control">
                                                                                            <?php
                                                                                            $json_data = file_get_contents("./jsondata/country.json");
                                                                                            $items = json_decode($json_data, true);
                                                                                            if (count($items) != 0) {
                                                                                                foreach ($items as $item) {
                                                                                            ?>

                                                                                                    <option value="<?= $item['code']; ?>"><?= $item['name']; ?></option>
                                                                                            <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                    <?php pkAjax("#cntrysrch", "/country-search-ajax-profile", "#cntrysrch", "#res-cntr", 'keyup'); ?>

                                                                                </div>
                                                                                <div class="col">
                                                                                    <label for="">Zipcode</label>
                                                                                    <input type="text" class="form-control my-2 valid" placeholder="" name="zipcode">
                                                                                </div>
                                                                            </div>

                                                                        </form>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <div id="uplad"></div>
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        <button type="button" id="myadd_btn" class="btn btn-primary">Save changes</button>
                                                                        <?php pkAjax_form("#myadd_btn", "#address_form", "#uplad"); ?>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>



                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div id="bankaccounts" class="tabcontent1">
                                                        <h5>Bank account</h5>
                                                        <?php
                                                        $bank_account = null;
                                                        $bank_name = null;
                                                        $swift_code = null;
                                                        $iban = null;
                                                        $country_code = null;
                                                        $bank_country_name = null;
                                                        $jsn = USER['jsn'];
                                                        $jsn = json_decode($jsn);
                                                        if (isset($jsn->banks)) {
                                                            $bank_account = isset($jsn->banks[0]->bank_account) ? $jsn->banks[0]->bank_account : null;
                                                            $bank_name = isset($jsn->banks[0]->bank_name) ? $jsn->banks[0]->bank_name : null;
                                                            $swift_code = isset($jsn->banks[0]->swift_code) ? $jsn->banks[0]->swift_code : null;
                                                            $iban = isset($jsn->banks[0]->iban) ? $jsn->banks[0]->iban : null;
                                                            $country_code = isset($jsn->banks[0]->country_code) ? $jsn->banks[0]->country_code : null;
                                                            $bank_country_name = isset($jsn->banks[0]->country_name) ? $jsn->banks[0]->country_name : null;
                                                        }
                                                        // myprint($jsn);
                                                        ?>
                                                        <div id="res-update-bank-account"></div>
                                                        <form class="material-form pt-2" id="update_bank_details_form" method="post" action="/<?php echo home; ?>/save-bank-account-ajax">
                                                            <div class="form-group row mb-4">
                                                                <div class="col-md-6">
                                                                    <label for="">Account Number</label>
                                                                    <input type="text" name="bank_account" value="<?php echo $bank_account; ?>" class="form-control" placeholder="Bank Account Number">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="">Bank Name</label>
                                                                    <input type="text" name="bank_name" value="<?php echo $bank_name; ?>" class="form-control" placeholder="Bank Name">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="">IBAN</label>
                                                                    <input type="text" name="iban" value="<?php echo $iban; ?>" class="form-control" placeholder="IBAN">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="">Swift Code</label>
                                                                    <input type="text" name="swift_code" value="<?php echo $swift_code; ?>" class="form-control" placeholder="Swift code">
                                                                </div>
                                                                <!-- <div class="col-md-12">
                                                                    <textarea name="bank_account" placeholder="Provide your bank account details" rows="5" class="form-control"></textarea>
                                                                </div> -->
                                                                <div class="col">
                                                                    <input class="form-control my-2 valid" type="text" placeholder="Search and select the country" id="cntrbnksrh" name="key_ctry">
                                                                    <div id="bank-cntry">
                                                                        <select name="country" class="form-select">
                                                                            <?php
                                                                            $json_data = file_get_contents("./jsondata/country.json");
                                                                            $items = json_decode($json_data, true);
                                                                            if (count($items) != 0) {
                                                                                foreach ($items as $item) {
                                                                            ?>
                                                                                    <option <?php echo $country_code == $item['code'] ? 'selected' : null; ?> value="<?= $item['code']; ?>"><?= $item['name']; ?></option>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <?php pkAjax("#cntrbnksrh", "/country-search-ajax-profile", "#cntrbnksrh", "#bank-cntry", 'keyup'); ?>

                                                                </div>
                                                            </div>
                                                            <!-- <div class="form-group row mb-4">
                                                                <div class="col-lg-6">
                                                                    <input class="form-control valid" type="text" required="" placeholder="account holder" name="name" data-validation-required-message="Das ist ein Pflichtfeld" aria-required="true" aria-invalid="false">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input class="form-control valid" type="text" required="" placeholder="bank name" name="name" data-validation-required-message="Das ist ein Pflichtfeld" aria-required="true" aria-invalid="false">
                                                                </div>
                                                            </div> -->

                                                            <!-- <div class="form-group row mb-4">
                                                                <div class="col-lg-7">
                                                                    <input class="form-control valid" type="text" required="" placeholder="IBAN (Just the number without spaces)" name="name" data-validation-required-message="Das ist ein Pflichtfeld" aria-required="true" aria-invalid="false">
                                                                </div>
                                                                <div class="col-lg-5">
                                                                    <input class="form-control valid" type="text" required="" placeholder="SWIFT / BIC (Just the number without spaces)" name="name" data-validation-required-message="Das ist ein Pflichtfeld" aria-required="true" aria-invalid="false">
                                                                </div>
                                                            </div> -->
                                                            <button id="update_bank_details_btn" class="btn btn-primary kyc_btn mt-3">Update</button>
                                                        </form>
                                                        <?php pkAjax_form("#update_bank_details_btn", "#update_bank_details_form", "#res-update-bank-account"); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div id="security" class="tabcontent1">
                                                        <h5>Change password</h5>
                                                        <div id="pass-change"></div>
                                                        <form id="change-pass-form" action="/<?php echo home; ?>/change-password-ajax" class="material-form pt-2">
                                                            <div class="form-group row mb-4">
                                                                <div class="col-lg-6">
                                                                    <label for="">Old Password</label>
                                                                    <input class="form-control valid" type="password" name="old_pass">
                                                                    <label for="">New Password</label>
                                                                    <input class="form-control valid" type="password" name="new_pass">
                                                                    <label for="">Confirm Password</label>
                                                                    <input class="form-control valid" type="password" name="cnf_pass">
                                                                </div>
                                                            </div>
                                                            <button id="change-pass-btn" class="btn btn-primary kyc_btn mt-3">Save</button>
                                                        </form>
                                                        <?php pkAjax_form("#change-pass-btn", "#change-pass-form", "#pass-change"); ?>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <?php
                                $wallet = obj(liveWallet($userid = USER['id']));
                                ?>
                                <div id="wallet" class="tabcontent">
                                    <div class="card">
                                        <div class="card-header">
                                            <p class="my-pr">Wallets</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-3 mb-3">
                                                <div class="col-lg-12">
                                                    <h5>Wallet Info</h5>
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <div class="live_wallet">
                                                                <h5>Lifetime Balance</h5>
                                                                <span style="font-size: 36px;"><i class="bi bi-arrow-down" style="color: #00c292;"></i>
                                                                    <?php echo $wallet->lifetime_amt; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="live_wallet">
                                                                <h5>Current Balance</h5>
                                                                <span style="font-size: 36px;"><i class="bi bi-arrow-right" style="color: #00c292;"></i>
                                                                    <?php echo $wallet->amt_left; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="live_wallet">
                                                                <h5>Cash Withdrawal</h5>
                                                                <span style="font-size: 36px;"><i class="bi bi-arrow-up" style="color: #e46a76;"></i>
                                                                    <?php echo $wallet->amt_paid; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row mb-4">
                                                <div class="col-lg-12">
                                                    <table id="datatablesSimple" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Trans. ID</th>
                                                                <th>Date</th>
                                                                <th>Request status</th>
                                                                <th>Amount</th>
                                                                <th>Info</th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Trans. ID</th>
                                                                <th>Date</th>
                                                                <th>Request status</th>
                                                                <th>Amount</th>
                                                                <th>Info</th>
                                                            </tr>
                                                        </tfoot>
                                                        <tbody>
                                                            <?php
                                                            $db = new Dbobjects;
                                                            $sql = "select * from credits where user_id = {$userid} and status = 'paid'";
                                                            $cmsn = $db->show($sql);
                                                            ?>
                                                            <?php foreach ($cmsn as $cms) {
                                                                $cms = obj($cms);
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $cms->id; ?></td>
                                                                    <td><?php echo $cms->paid_on; ?></td>
                                                                    <td><?php echo $cms->remark; ?></td>
                                                                    <td><?php echo $cms->amt; ?></td>
                                                                    <td><?php echo $cms->info; ?></td>
                                                                </tr>
                                                            <?php } ?>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="settings" class="tabcontent">
                                    <div class="card">
                                        <div class="card-header">
                                            <p class="my-pr">Personalized Settings</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-3 mb-3">
                                                <div class="col-lg-6">
                                                    <h5>Default Binary position</h5>
                                                    <label for="">default position</label>
                                                    <select class="form-control mt-2" name="" id="">
                                                        <option value="">Alternate Left/Right</option>
                                                        <option value="">Default Left</option>
                                                        <option value="">Default Right</option>
                                                        <option value="">Weakest Leg</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="documents" class="tabcontent">
                                    <div class="card">
                                        <div class="card-header">
                                            <p class="my-pr">KYC Documents</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-3 mb-3">
                                                <div class="col-lg-12">
                                                    <div class="card-header">
                                                        <h5>identity document</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <p>In order for us to be able to process your payout we need to verify your identity. Please upload this document here. After successful verification of your payout should be available in a few days.</p>
                                                        <p>Documents we accept for identity verification:<br>
                                                            passport, identity card</p>
                                                        <a class="upl_txt" href="">NOT UPLOADED</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 pt-3">
                                                    <div class="card-header">
                                                        <h5>Proof of address</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <p>In order for us to be able to process your Payout we need to verify your address. Please upload this document here. After successful verification of your payout should be available in a few days.</p>
                                                        <p>Documents we accept for identity verification:<br>
                                                            passport, identity cardUtility Bill (Electric, Mobile Phone, Internet, ...)</p>
                                                        <a class="upl_txt" href="">NOT UPLOADED</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary kyc_btn mt-3"><a href="/<?php echo home; ?>/kyc-upload">KYC upload</a></button>
                                </div>
                                <div id="referral" class="tabcontent">
                                    <div class="card">
                                        <div class="card-header">
                                            <p class="my-pr">Referral List</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-3 mb-3">
                                                <div class="col-lg-6">
                                                    <h5>Referral List Info</h5>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-lg-12">
                                                    <table id="datatablesSimple1">
                                                        <thead>
                                                            <tr>
                                                                <th>User ID</th>
                                                                <th>username</th>
                                                                <th>First name</th>
                                                                <th>Last name</th>
                                                                <th>referal date</th>
                                                                <th>status</th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                            <tr>
                                                                <th>User ID</th>
                                                                <th>username</th>
                                                                <th>First name</th>
                                                                <th>Last name</th>
                                                                <th>referal date</th>
                                                                <th>status</th>
                                                            </tr>
                                                        </tfoot>
                                                        <tbody>
                                                            <?php
                                                            if (authenticate() == true) {
                                                                $userObj = new Model('pk_user');

                                                                $arr = null;
                                                                $arr['ref'] = $_SESSION['user_id'];
                                                                $partner = $userObj->filter_index($assoc_arr = $arr, $ord = 'DESC', $limit = 9999999,                                         $change_order_by_col = "");
                                                            }
                                                            
                                                                $pvctrl = new Pv_ctrl;
                                                                $pvctrl->db = new Dbobjects;
                                                            foreach ($partner as $value) {
                                                                $is_active = $pvctrl->check_active($value['id']);
                                                            ?>
                                                                <tr>
                                                                    <th><?php echo $value['id']; ?></th>
                                                                    <th><?php echo $value['username']; ?></th>
                                                                    <th><?php echo $value['first_name']; ?></th>
                                                                    <th><?php echo $value['last_name']; ?></th>
                                                                    <th><?php echo $value['created_at']; ?></th>
                                                                    <th><?php echo $is_active ? 'Active' : 'In active'; ?></th>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </main>
        <?php import("apps/view/inc/footer-credit.php"); ?>
    </div>
</div>
<?php
import("apps/view/inc/footer.php");
?>