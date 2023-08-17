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
if ($prod['item_group'] != 'product') {
    header("Location: /$home");
    exit;
}
$pv = obj($prod);

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
                    <li class="breadcrumb-item active">Edit Product</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="my-form">
                                <form class="material-form" id="product_form" action="/<?php echo home; ?>/update-product-ajax" method="POST">
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <!-- <label for="">Product Image</label>
                                            <input class="form-control my-2 valid" type="file" id="image-input" name="image"> -->
                                            <label for="">Category</label>
                                            <select name="parent_id" class="form-select">
                                                <?php
                                                $catobj = new Model('item');
                                                $cats = $catobj->filter_index(['item_group' => 'category']);
                                                foreach ($cats as $cv) {
                                                    $cv = obj($cv);
                                                ?>
                                                    <option <?php echo $cv->id == $pv->parent_id ? 'selected' : null; ?> value="<?php echo $cv->id; ?>"><?php echo $cv->name; ?></option>
                                                <?php   }
                                                ?>
                                            </select>
                                        </div>
                                        <!-- <div class="col-lg-6">
                                            <img id="banner" style="width:100%; object-fit:contain;" src="/<?php echo MEDIA_URL; ?>/upload/items/<?php echo $pv->image; ?>" alt="<?php echo $pv->name; ?>">
                                           
                                        </div> -->
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Name of Product</label>
                                            <input class="form-control my-2 valid" type="text" required="" name="name" value="<?php echo $pv->name; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="">Suppliment</label>
                                        <div class="row my-3">
                                            <div class="col-lg-6">
                                                <label for="">Yes</label>
                                                <input <?php echo ($pv->suppliment == 1) ? 'checked' : null; ?> type="radio" name="suppliment" value="1">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="">No</label>
                                                <input <?php echo ($pv->suppliment == 0) ? 'checked' : null; ?> type="radio" name="suppliment" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="">Weight</label>
                                        <div class="row my-3">
                                            <div class="col-lg-6">
                                                <label for="">Quantity</label>
                                                <input type="number" class="form-control" scope="any" min="0" name="qty" value="<?php echo $pv->qty; ?>">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="">Unit</label>
                                                <select id="massSelect" class="form-select" name="unit">
                                                    <option <?php echo $pv->unit == 'kg' ? 'selected' : null; ?> value="kg">Kilogram (kg)</option>
                                                    <option <?php echo $pv->unit == 'g' ? 'selected' : null; ?> value="g">Gram (g)</option>
                                                    <option <?php echo $pv->unit == 'lb' ? 'selected' : null; ?> value="lb">Pound (lb)</option>
                                                    <option <?php echo $pv->unit == 'oz' ? 'selected' : null; ?> value="oz">Ounce (oz)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="">Country of production</label>
                                        <div class="row my-3">
                                            <div class="col-md-6">
                                                <select id="massSelect" class="form-select" name="prod_country">
                                                    <?php
                                                    $json_data = file_get_contents("./jsondata/country.json");
                                                    $countries = json_decode($json_data);
                                                    foreach ($countries as $cnt) :
                                                    ?>
                                                        <option <?php echo $pv->prod_country == $cnt->code ? 'selected' : null; ?> class="pointer" value="<?php echo $cnt->code; ?>"> <?php echo $cnt->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>


                                            </div>
                                            <div class="col-md-6">

                                                <label for="">EAN Code</label>
                                                <input class="form-control my-2 valid" type="text" name="ean_code" value="<?php echo $pv->ean_code; ?>">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Zoll Tarif Number</label>
                                            <input class="form-control my-2 valid" type="text" scope="any" min="0" required="" name="zlf_num" value="<?php echo $pv->zlf_num; ?>">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Manufacturer Price</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" required="" name="mf_price" value="<?php echo $pv->mf_price; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Min price</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" required="" name="min_price" value="<?php echo $pv->min_price; ?>">
                                        </div>
                                        <!-- <div class="col-lg-6">
                                            <label for="">PV</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" name="pv" value="<?php //echo $pv->pv; 
                                                                                                                                        ?>">
                                        </div> -->

                                    </div>
                                    <!-- <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Tax</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" required="" name="tax" value="<?php //echo $pv->tax; 
                                                                                                                                                    ?>">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Product Detail</label>
                                            <input class="form-control my-2 valid" type="text" required="" name="details" value="<?php //echo $pv->details; 
                                                                                                                                    ?>">
                                        </div>
                                    </div> -->

                                    <div id="uplpr"></div>
                                    <input type="hidden" name="product_id" value="<?php echo $pv->id; ?>">
                                    <button id="myproduct_btn" class="btn btn-light btn-block" name="upload_product_btn" type="button">UPLOAD</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <a class="btn btn-dark" href="/<?php echo home; ?>/list-all-products">Back</a>
                        <button class="btn btn-link text-danger my-5" data-bs-toggle="modal" data-bs-target="#withdrawMoney">Delete</button>
                            
                        </div>
                    </div>
                </div>
            </div>

            <?php pkAjax_form("#myproduct_btn", "#product_form", "#uplpr"); ?>
            <script>
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
                        <form id="withdraw-amt" action="/<?php echo home; ?>/delete-product">
                            <p class="text-danger">Be carefull, all your packages and invoices related to this product will raise error!</p>
                            <input type="hidden" name="delpid" value="<?php echo $pv->id; ?>" min="0" scope="any" class="form-control">
                            <button id="submit-withdraw" type="button" class="btn btn-danger my-3">Delete</button>
                        </form>
                        <?php pkAjax_form("#submit-withdraw", "#withdraw-amt", "#res"); ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end -->
        </main>
        <?php import("apps/view/inc/footer-credit.php"); ?>
    </div>
</div>
<?php
import("apps/view/inc/footer.php");
?>