<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
$prmadrs = get_my_primary_address(USER['id']);
if (!$prmadrs) {
    $_SESSION['msg'][] = "Please update primary address";
    echo js_alert(msg_ssn(return: true));
}
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <!-- <h1 class="mt-4">Dashboard</h1> -->
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active">Single Purchase</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-3">
                            <h5>Single Purchase</h5>
                        </div>
                        <div class="col-6">
                            <label for="">
                                <p>Delivery Address</p>
                            </label>
                            <?php
                            $userObj = new Model('pk_user');
                            $arr = null;
                            $arr['id'] = $_SESSION['user_id'];
                            $user_address = $userObj->filter_index($assoc_arr = $arr, $ord = 'DESC', $limit = 99999, $change_order_by_col = "");
                            ?>
                            <select class="col-md-6 form-control mb-3 addrs-data" name="pr_address">
                                <?php
                                $addrs = new Model('address');
                                $myaddress_list = $addrs->filter_index(['user_id' => $_SESSION['user_id']]);
                                foreach ($myaddress_list as $ad) {
                                    $ad = (object) $ad;
                                ?>
                                    <option <?php echo $ad->address_type == 'primary' ? 'selected' : null; ?> value="<?php echo $ad->id; ?>"><?php echo $ad->address_type != "" ? "($ad->address_type)" : null; ?> <?php echo $ad->city; ?> - <?php echo $ad->zipcode; ?></option>

                                <?php
                                }
                                ?>
                            </select>
                            <div id="res"></div>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">Add New Address</button>
                            <button id="cnfrmadrs-btn" class="btn btn-info">Shop Now</button>
                            <?php pkAjax("#cnfrmadrs-btn", "/make-this-address-primary-ajax", ".addrs-data", "#res"); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
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
                                        <label for="">Country</label>
                                        <input class="form-control my-2 valid" type="text" id="cntrysrch" name="key_ctry">
                                        <div id="res-cntr">
                                            <select name="country" class="form-control" id="">
                                                <?php
                                                $json_data = file_get_contents("./jsondata/country.json");
                                                $items = json_decode($json_data, true);
                                                if (count($items) != 0) {
                                                    foreach ($items as $item) {
                                                ?>

                                                        <option selected value="<?= $item['name']; ?>"><?= $item['name']; ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php pkAjax("#cntrysrch", "/country-search-ajax", "#cntrysrch", "#res-cntr", 'keyup'); ?>

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

            </div> -->


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
                                            <select name="country_code" class="form-control">
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
        </main>
        <?php import("apps/view/inc/footer-credit.php"); ?>
    </div>
</div>
<?php
import("apps/view/inc/footer.php");
?>