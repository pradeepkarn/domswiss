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
                        <div class="col-6">
                            <div class="my-form">
                                <form class="material-form" id="product_form" action="/<?php echo home; ?>/upload-product-ajax" method="POST">
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Name of Product</label>
                                            <input class="form-control my-2 valid" type="text" required="" placeholder="Name of Product" name="product_name" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Quantity</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" required="" placeholder="Quantity" name="product_qty" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Price</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" required="" placeholder="Price" name="product_price" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Tax</label>
                                            <input class="form-control my-2 valid" type="number" scope="any" min="0" required="" placeholder="Tax" name="product_tax" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Product Detail</label>
                                            <input class="form-control my-2 valid" type="text" required="" placeholder="Product Detail" name="product_detail" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Product Image</label>
                                            <input class="form-control my-2 valid" type="file"
                                            id="formFile" name="product_image">
                                        </div>
                                    </div>
                                    <div id="uplpr"></div>
                                    <button id="myproduct_btn" class="btn btn-light btn-block" name="upload_product_btn" type="button">UPLOAD</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php pkAjax_form("#myproduct_btn", "#product_form", "#uplpr"); ?>

        </main>
        <?php import("apps/view/inc/footer-credit.php"); ?>
    </div>
</div>
<?php
import("apps/view/inc/footer.php");
?>