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
                    <li class="breadcrumb-item active">Add Category</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="my-form">
                                <form class="material-form" id="product_form" action="/<?php echo home; ?>/create-category-ajax" method="POST">
                                    <!-- <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Category Image</label>
                                            <input class="form-control my-2 valid" type="file" id="image-input" name="image">
                                        </div>
                                        <div class="col-lg-6">
                                            <img id="banner" style="width:100%; object-fit:contain;" src="" alt="">
                                        </div>
                                    </div> -->
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Name of Category</label>
                                            <input class="form-control my-2 valid" type="text" required="" name="name" data-validation-required-message="Das ist ein Pflichtfeld" value="" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div id="uplpr"></div>
                                    <button id="myproduct_btn" class="btn btn-light btn-block" name="upload_product_btn" type="button">Save</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-dark" href="/<?php echo home; ?>/list-all-categories">Back</a>
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