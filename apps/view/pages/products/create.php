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
                    <li class="breadcrumb-item active">Add Product</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="my-form">
                                <form class="material-form" id="product_form" action="/<?php echo home; ?>/create-product-ajax" method="POST">
                                    <!-- <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Product Image</label>
                                            <input class="form-control my-2 valid" type="file" id="image-input" name="image">
                                        </div>
                                        <div class="col-lg-6">
                                            <img id="banner" style="width:100%; object-fit:contain;" src="" alt="">
                                        </div>
                                    </div> -->
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Name of Product</label>
                                            <input class="form-control my-2 valid" type="text" required="" name="name" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                            <label for="">Category</label>
                                            <select name="parent_id" class="form-select">
                                                <?php
                                                $catobj = new Model('item');
                                                $cats = $catobj->filter_index(['item_group' => 'category']);
                                                foreach ($cats as $cv) {
                                                    $cv = obj($cv);
                                                //    $product_id = generate_product_id();
                                                ?>
                                                    <option value="<?php echo $cv->id; ?>"><?php echo $cv->name; ?></option>
                                                <?php   }
                                                ?>
                                            </select>
                                        </div>
                                        <!-- <div class="col-lg-6">
                                            <label for="">Product ID</label>
                                            <input class="form-control my-2 valid" type="number" name="product_id" value="<?php // echo $product_id; ?>">
                                        </div> -->

                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="">Suppliment</label>
                                        <div class="row my-3">
                                            <div class="col-lg-6">
                                                <label for="">Yes</label>
                                                <input type="radio" name="suppliment" value="1">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="">No</label>
                                                <input checked type="radio" name="suppliment" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="">Weight</label>
                                        <div class="row my-3">
                                            <div class="col-lg-6">
                                                <label for="">Quantity</label>
                                                <input type="number" class="form-control" scope="any" min="0" name="qty" value="1">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="">Unit</label>
                                                <select id="massSelect" class="form-select" name="unit">
                                                    <option value="kg">Kilogram (kg)</option>
                                                    <option value="g">Gram (g)</option>
                                                    <option value="lb">Pound (lb)</option>
                                                    <option value="oz">Ounce (oz)</option>
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
                                                        <option class="pointer" value="<?php echo $cnt->code; ?>"> <?php echo $cnt->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>


                                            </div>
                                            <div class="col-md-6">

                                                <label for="">EAN Code</label>
                                                <input class="form-control my-2 valid" type="text" name="ean_code">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Zoll Tarif Number</label>
                                            <input class="form-control my-2 valid" type="text" scope="any" min="0" required="" name="zlf_num" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Manufacturer Price</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" required="" name="mf_price" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Min price</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" required="" name="min_price" aria-required="true" aria-invalid="false">
                                        </div>
                                        <!-- <div class="col-lg-6">
                                            <label for="">PV</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" name="pv" >
                                        </div> -->

                                    </div>
                                    <!-- <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Tax</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" required="" name="tax" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Product Detail</label>
                                            <input class="form-control my-2 valid" type="text" required="" name="details" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div> -->

                                    <div id="uplpr"></div>
                                    <button id="myproduct_btn" class="btn btn-light btn-block" name="upload_product_btn" type="button">UPLOAD</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-dark" href="/<?php echo home; ?>/list-all-products">Back</a>
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
        </main>
        <?php import("apps/view/inc/footer-credit.php"); ?>
    </div>
</div>
<?php
import("apps/view/inc/footer.php");
?>