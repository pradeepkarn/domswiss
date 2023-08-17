<?php
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
                    <li class="breadcrumb-item active">Add Products</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-md-8">

                            <form class="material-form" id="package_form" action="/<?php echo home; ?>/create-package-ajax" method="POST">
                                <div class="form-group row mb-4">
                                    <div class="col-lg-6">
                                        <label for="">Package Image</label>
                                        <input class="form-control my-2 valid" type="file" id="image-input" name="image">

                                    </div>
                                    <div class="col-lg-6">
                                        <img id="banner" style="width:100%; object-fit:contain;" src="" alt="">
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
                                                $plobj = new Model('countries');
                                                $countries = $plobj->index();
                                                foreach ($countries as $cnt) :
                                                    $cnt = obj($cnt);
                                                ?>

                                                    <input class="pointer" type="checkbox" name="countries[]" value="<?php echo $cnt->code; ?>">
                                                    <?php echo $cnt->name; ?> [Tax: <?php echo $cnt->min_tax; ?>% to <?php echo $cnt->max_tax; ?>%] <br>

                                                <?php endforeach; ?>

                                            </div>

                                        </div>




                                        <div class="col-md-12">
                                            <h3>Select Product, quantity and prices</h3>

                                            <div class="form-group" style="height: 200px; overflow-y:scroll; background-color:white;">
                                                <?php

                                                $json_data = file_get_contents("./jsondata/country.json");
                                                $countries = json_decode($json_data);
                                                $prods = new Model('item');
                                                $all_active_products = $prods->filter_index(['item_group' => 'product', 'is_active' => 1]);
                                                foreach ($all_active_products as $item) :
                                                    $item = obj($item);
                                                ?>
                                                    <input onchange="total_net_price()" class="pointer items" type="checkbox" name="items[]" value="<?php echo $item->id; ?>">
                                                    <input placeholder="Qty" onchange="total_net_price()" onkeyup="total_net_price()" onblur="total_net_price()" style="width:90px;" min='0' type="number" class="qtys itemQtys" scope="any" name="qty<?php echo $item->id; ?>">
                                                    <input placeholder="Net Price" onchange="total_net_price()" onkeyup="total_net_price()" onblur="total_net_price()" style="width:90px;" min='0' type="number" class="qtys netpr" scope="any" name="net_price<?php echo $item->id; ?>">
                                                    <input placeholder="Cust. Net Price" onchange="total_net_price()" onkeyup="total_net_price()" onblur="total_net_price()" style="width:90px;" min='0' type="number" class="qtys custnetpr" scope="any" name="cust_net_price<?php echo $item->id; ?>">
                                                    <!-- <img style="height: 40px; width:40px; object-fit:cover;" src="/<?php // echo home; 
                                                                                                                        ?>/media/upload/items/<?php // echo $item->image; 
                                                                                                                                                ?>" alt="items"> -->
                                                    <?php echo $item->name; ?> <br>
                                                <?php endforeach; ?>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-12">
                                            <label for="">Name of Package</label>
                                            <input class="form-control my-2 valid" type="text" required="" name="name">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <!-- <div class="col-md">
                                            <label for="">Quantity</label>
                                            <input class="form-control my-2 valid" type="text" scope="any" min="0" name="qty">
                                        </div> -->
                                        <div class="col-md">
                                            <label for="">Net Price</label>
                                            <input id="netPrice" readonly class="form-control my-2 valid" type="number" scope="any" min="0" name="net_price">
                                        </div>
                                        <div class="col-md">
                                            <label for="">Customer Net Price</label>
                                            <input readonly id="custNetPrice" class="form-control my-2 valid" type="number" scope="any" min="0" name="cust_price">
                                        </div>
                                        <div class="col-md">
                                            <label for="">PV</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" name="pv">
                                        </div>
                                        <div class="col-md">
                                            <label for="">Rannk Advance (RV)</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" name="rv">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="">Direct Bonus</label>
                                            <input class="form-control my-2 valid" type="number" value="0" scope="any" min="0" name="direct_bonus">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6" style="align-items: center; display:flex; justify-content:space-between">
                                            <label for="">Show on customer side</label>
                                            <input style="height:30px; width:30px;" type="checkbox" checked name="show_to_cust">
                                        </div>



                                        <div class="col-lg-6" style="align-items: center; display:flex; justify-content:space-between">
                                            <label for="">Mark as active</label>
                                            <input style="height:30px; width:30px;" type="checkbox" checked name="is_active">
                                        </div>


                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-12">
                                            <label for="">Details</label>
                                            <textarea name="details" class="form-control"></textarea>
                                        </div>

                                    </div>
                                    <div id="uplpr"></div>
                                    <button onclick="total_net_price()" id="mypackage_btn" class="btn btn-light btn-block" name="upload_product_btn" type="button">UPLOAD</button>
                            </form>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-dark" href="/<?php echo home; ?>/list-all-packages">Back</a>
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
    // function total_net_price() {
    //     const netprice = document.querySelectorAll('.netpr');
    //     const custNetPrice = document.querySelectorAll('.custnetpr');
    //     const items = document.querySelectorAll('.items');
    //     let tnpr = 0;
    //     let tcnpr = 0;
    //     for (let i = 0; i < items.length; i++) {
    //         if(items[i].checked){
    //             tnpr += netprice[i].value?parseFloat(netprice[i].value):0;
    //             tcnpr += custNetPrice[i].value?parseFloat(custNetPrice[i].value):0;
    //         }

    //     }
    //     document.getElementById('netPrice').value =  tnpr;
    //     document.getElementById('custNetPrice').value =  tcnpr;
    // }
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
    // function handleQuantityInput(event) {
    //     var quantityInput = event.target;
    //     var checkbox = quantityInput.previousElementSibling;

    //     // Check if the quantity input is empty
    //     if (quantityInput.value === '') {
    //         checkbox.checked = false;
    //     } else {
    //         quantityInputs.forEach((e) => {
    //             if (quantityInput != e) {
    //                 e.value = null;
    //             }

    //         })
    //         checkbox.checked = true;
    //     }
    // }


    // 

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
<?php
import("apps/view/inc/footer.php");
?>