<?php
$home = home;
if (!isset($_GET['pid'])) {
    header("Location: /$home");
    exit;
}
if (!intval($_GET['pid'])) {
    header("Location: /$home");
    exit;
}
$prod = getData('item', $_GET['pid']);
if ($prod == false) {
    header("Location: /$home");
    exit;
}
if ($prod['item_group'] != 'package') {
    header("Location: /$home");
    exit;
}
$pv = obj($prod);

$jsn = json_decode($pv->jsn);
$selected_items = [];
$selected_countries = [];
if (isset($jsn->items)) {
    $selected_items = $jsn->items;
}
if (isset($jsn->countries)) {
    $selected_countries = $jsn->countries;
}
// myprint($jsn);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">



        <main>
            <div class="container-fluid px-4">
                <!-- <h1 class="mt-4">Dashboard</h1> -->
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active">Edit Package</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-md-8">

                            <form class="material-form" id="package_form" action="/<?php echo home; ?>/update-package-ajax" method="POST">
                                <div class="form-group row mb-4">
                                    <div class="col-lg-6">
                                        <label for="">Package Image</label>
                                        <input class="form-control my-2 valid" type="file" id="image-input" name="image">

                                    </div>
                                    <div class="col-lg-6">
                                        <img id="banner" style="width:100%; object-fit:contain;" src="/<?php echo MEDIA_URL . "/upload/items/" . $pv->image; ?>" alt="">
                                    </div>
                                </div>


                                <div class="form-group row mb-4">

                                    <div class="row my-3">
                                        <div class="col-md-12">
                                            <h3>Select Country</h3>


                                            <div class="form-group my-3" style="height: 200px; overflow-y:scroll; background-color:white;">
                                                <?php
                                                // $json_data = file_get_contents("./jsondata/country.json");
                                                // $countries = json_decode($json_data);
                                                $shipping_charges = [];
                                                $plobj = new Model('countries');
                                                $countries = $plobj->index();
                                                foreach ($countries as $cnt) :
                                                    $cnt = obj($cnt);
                                                    $isCountryChecked = false;
                                                    // Check if the current item is selected
                                                    foreach ($selected_countries as $ccode) {
                                                        if (in_array($cnt->code, array($ccode))) {
                                                            $shpcost = calculate_shipping_cost($db = new Dbobjects, $gram=$pv->total_gram, $ccode=$ccode);
                                                            $shipping_charges[] = array(
                                                                "ccode"=>$ccode,
                                                                "shipping_cost"=>$shpcost
                                                            );
                                                            $isCountryChecked = true;
                                                            break;
                                                        }
                                                    }
                                                ?>

                                                    <input <?php echo $isCountryChecked ? 'checked' : null; ?> class="pointer" type="checkbox" name="countries[]" value="<?php echo $cnt->code; ?>">
                                                    <?php echo $cnt->name; ?> [Tax: <?php echo $cnt->min_tax; ?>% to <?php echo $cnt->max_tax; ?>%] <br>

                                                <?php endforeach; ?>

                                            </div>






                                            <div class="col-md-12">
                                                <h3>Select Product, quantity and prices</h3>

                                                <div class="form-group" style="height: 200px; overflow-y:scroll; background-color:white;">
                                                    <?php
                                                    $total_net_price = 0;
                                                    $total_cust_net_price = 0;

                                                    $prods = new Model('item');
                                                    $all_active_products = $prods->filter_index(['item_group' => 'product', 'is_active' => 1]);
                                                    $total_gm = 0;
                                                    foreach ($all_active_products as $item) :
                                                        $item = obj($item);
                                                        $isChecked = false;
                                                        $qty = null;
                                                        // Check if the current item is selected
                                                        foreach ($selected_items as $selectedItem) {
                                                            if ($item->id == $selectedItem->item) {

                                                                $isChecked = true;
                                                                $qty = $selectedItem->qty;
                                                                switch ($item->unit) {
                                                                    case "g":
                                                                        $total_gm += $item->qty * $qty;
                                                                        break;
                                                                    case "kg":
                                                                        $total_gm += $item->qty * $qty * 1000;
                                                                        break;
                                                                    case "lb":
                                                                        // Convert pounds to grams (1 lb = 453.592 grams)
                                                                        $total_gm += $item->qty * $qty * 453.592;
                                                                        break;
                                                                    case "oz":
                                                                        // Convert ounces to grams (1 oz = 28.3495 grams)
                                                                        $total_gm += $item->qty * $qty * 28.3495;
                                                                        break;
                                                                }

                                                                $net_price = isset($selectedItem->net_price) ? floatval($selectedItem->net_price) : 0;
                                                                $cust_net_price = isset($selectedItem->cust_net_price) ? floatval($selectedItem->cust_net_price) : 0;
                                                                $total_net_price += $net_price * $qty;
                                                                $total_cust_net_price += floatval($cust_net_price) * $qty;
                                                                break;
                                                            } else {
                                                                $isChecked = false;
                                                                $qty = null;
                                                                $net_price = null;
                                                                $cust_net_price = null;
                                                            }
                                                        }
                                                    ?>

                                                        <input onchange="total_net_price()" onkeyup="total_net_price()" <?php echo $isChecked ? 'checked' : null; ?> class="pointer items" type="checkbox" name="items[]" value="<?php echo $item->id; ?>">
                                                        <input placeholder="Qty" onchange="total_net_price()" style="width:90px;" min='0' type="number" class="qtys itemQtys" scope="any" name="qty<?php echo $item->id; ?>" value="<?php echo $qty; ?>">
                                                        <input placeholder="Net Price" onchange="total_net_price()" onkeyup="total_net_price()" onblur="total_net_price()" style="width:90px;" min='0' type="number" class="qtys netpr" scope="any" name="net_price<?php echo $item->id; ?>" value="<?php echo $net_price; ?>">
                                                        <input placeholder="Cust. Net Price" onchange="total_net_price()" onkeyup="total_net_price()" onblur="total_net_price()" style="width:90px;" min='0' type="number" class="qtys custnetpr" scope="any" name="cust_net_price<?php echo $item->id; ?>" value="<?php echo $cust_net_price; ?>">
                                                        <!-- <img style="height: 40px; width:40px; object-fit:cover;" src="/<?php //echo home; 
                                                                                                                            ?>/media/upload/items/<?php // echo $item->image; 
                                                                                                                                                    ?>" alt="items"> -->
                                                        <?php echo $item->name; ?> <br>

                                                    <?php endforeach; ?>


                                                </div>



                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-12">
                                            <label for="">Name of Package</label>
                                            <input class="form-control my-2 valid" type="text" required="" name="name" value="<?php echo $pv->name; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <!-- <div class="col-md">
                                            <label for="">Quantity</label>
                                            <input class="form-control my-2 valid" type="text" scope="any" min="0" name="qty">
                                        </div> -->
                                        <div class="col-md">
                                            <label for="">Net Price</label>
                                            <input id="netPrice" class="form-control my-2 valid" type="number" scope="any" min="0" name="net_price" value="<?php echo $total_net_price; ?>">
                                        </div>
                                        <div class="col-md">
                                            <label for="">Customer Net Price</label>
                                            <input id="custNetPrice" class="form-control my-2 valid" type="number" scope="any" min="0" name="cust_price" value="<?php echo $total_cust_net_price; ?>">
                                        </div>
                                        <div class="col-md">
                                            <label for="">PV</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" name="pv" value="<?php echo $pv->pv; ?>">
                                        </div>
                                        <div class="col-md">
                                            <label for="">Rannk Advance (RV)</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" name="rv" value="<?php echo $pv->rv; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="">Direct Bonus</label>
                                            <input class="form-control my-2 valid" type="number" value="<?php echo $pv->direct_bonus; ?>" scope="any" min="0" name="direct_bonus">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6" style="align-items: center; display:flex; justify-content:space-between">
                                            <label for="">Show on customer side</label>
                                            <input style="height:30px; width:30px;" type="checkbox" name="show_to_cust" <?php echo $pv->show_to_cust ? 'checked' : null; ?>>
                                        </div>



                                        <div class="col-lg-6" style="align-items: center; display:flex; justify-content:space-between">
                                            <label for="">Mark as active</label>
                                            <input style="height:30px; width:30px;" type="checkbox" <?php echo $pv->is_active ? 'checked' : null; ?> name="is_active">
                                        </div>


                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-12">
                                            <label for="">Details</label>
                                            <textarea name="details" class="form-control"><?php echo $pv->details; ?></textarea>
                                        </div>

                                    </div>
                                    <div id="uplpr"></div>
                                    <!-- <div style="font-weight: 600;">
                                            Total : <?php // echo "Net price: ". $total_net_price. ", Customer Net Price: ".$total_cust_net_price ;
                                                    ?>
                                        </div> -->
                                    <input type="hidden" name="product_id" value="<?php echo $pv->id; ?>">
                                    <div class="col-md-4">
                                        <!-- <label for="">Total Grams</label> -->
<?php 
// myprint($shipping_charges);
?>
                                    </div>
                                    <div class="col-md-8">

                                        <!-- <input type="text" readonly class="form-control mb-3" value="<?php //echo $pv->total_gm; ?>"> -->
                                    </div>
                                    <button id="mypackage_btn" class="btn btn-light btn-block" name="upload_product_btn" type="button">UPDATE</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-dark" href="/<?php echo home; ?>/list-all-packages">Back</a>
                        <button class="btn btn-link text-danger my-5" data-bs-toggle="modal" data-bs-target="#withdrawMoney">Delete</button>
                    </div>
                </div>
            </div>
    </div>

    <?php pkAjax_form("#mypackage_btn", "#package_form", "#uplpr"); ?>

    </main>
    <?php import("apps/view/inc/footer-credit.php"); ?>
</div>
</div>
<script>
    var quantityInputs = document.querySelectorAll('.qtys');

    function total_net_price() {
        const netprice = document.querySelectorAll('.netpr');
        const custNetPrice = document.querySelectorAll('.custnetpr');
        const items = document.querySelectorAll('.items');
        const qtys = document.querySelectorAll('.itemQtys');
        let tnpr = [];
        let tcnpr = [];
        for (let i = 0; i < items.length; i++) {
            if (items[i].checked && qtys[i].value) {
                // var qty = qtys[i].value;
                tnpr.push(netprice[i].value ? parseFloat(netprice[i].value) * qtys[i].value : 0);
                tcnpr.push(custNetPrice[i].value ? parseFloat(custNetPrice[i].value) * qtys[i].value : 0);
            }

        }
        document.getElementById('netPrice').value = arraySum(tnpr);
        document.getElementById('custNetPrice').value = arraySum(tcnpr);
    }

    quantityInputs.forEach(function(input) {
        input.addEventListener('input', handleQuantityInput);
    });

    function handleQuantityInput(event) {
        var quantityInput = event.target;
        var checkbox = quantityInput.previousElementSibling;

        // Check if the quantity input is empty
        if (quantityInput.value === '') {
            checkbox.checked = false;
        } else {
            checkbox.checked = true;
        }
    }

    var quantityInputs = document.querySelectorAll('.qtys');
    quantityInputs.forEach(function(input) {
        input.addEventListener('input', handleQuantityInput);
    });


    // function handleQuantityInput(event) {
    //     var quantityInput = event.target;
    //     var checkbox = quantityInput.previousElementSibling;

    //     // Check if the quantity input is empty
    //     if (quantityInput.value === '') {
    //         checkbox.checked = false;
    //     } else {
    //         quantityInputs.forEach((e)=>{
    //             if (quantityInput!=e) {
    //                 e.value=null;
    //             }

    //         })
    //         checkbox.checked = true;
    //     }
    // }

    const imageInputPost = document.getElementById('image-input');
    const imagePost = document.getElementById('banner');

    imageInputPost.addEventListener('change', (event) => {
        const file = event.target.files[0];
        const fileReader = new FileReader();

        fileReader.onload = () => {
            imagePost.src = fileReader.result;
        };

        fileReader.readAsDataURL(file);
    });
</script>
<!-- Modal -->
<div class="modal" id="withdrawMoney" tabindex="-1" aria-labelledby="delProdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure ? </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="res"></div>
                <form id="withdraw-amt" action="/<?php echo home; ?>/delete-package">
                    <p class="text-danger">Be carefull, all your invoices related to this package will raise error!</p>
                    <input type="hidden" name="delpid" value="<?php echo $pv->id; ?>" min="0" scope="any" class="form-control">
                    <button id="submit-withdraw" type="button" class="btn btn-danger my-3">Delete</button>
                </form>
                <?php pkAjax_form("#submit-withdraw", "#withdraw-amt", "#res"); ?>
            </div>
        </div>
    </div>
</div>
<!-- Modal end -->
<?php
import("apps/view/inc/footer.php");
?>