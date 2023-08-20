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
$prod = getData('countries', $_GET['pid']);
if ($prod == false) {
    header("Location: /$home");
    exit;
}
$pv = obj($prod);
$jsn = json_decode($pv->jsn);
$banks = [];
$offices = [];
$gateways = [];
if (isset($jsn->banks)) {
    $banks = $jsn->banks;
}
if (isset($jsn->office_address)) {
    $offices = $jsn->office_address;
}
if (isset($jsn->gateways)) {
    $gateways = $jsn->gateways;
}



import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">

        <main>
            <script src="https://cdn.tiny.cloud/1/mhpaanhgacwjd383mnua79qirux2ub6tmmtagle79uomfsgl/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

            <div class="container-fluid px-4">
                <!-- <h1 class="mt-4">Dashboard</h1> -->
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active"><?php echo $pv->name; ?> (<?php echo $pv->code; ?>) </li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="my-form">
                                <form class="material-form" id="country_form" action="/<?php echo home; ?>/update-country-ajax" method="POST">

                                    <div class="form-group row mb-4">
                                        <div class="col-lg-12">
                                            <label for="">Edit Country</label>
                                            <input class="form-control my-2" type="text" name="name" value="<?php echo $pv->name; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="">Tax</label>
                                        <div class="row my-3">
                                            <div class="col-lg-6">
                                                <label for="">Min. Tax</label>
                                                <input class="form-control my-2 valid" type="text" name="min_tax" value="<?php echo $pv->min_tax; ?>">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="">Max. tax</label>
                                                <input class="form-control my-2 valid" type="text" name="max_tax" value="<?php echo $pv->max_tax; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="">Bank Details</label>
                                        <div class="row my-3">
                                            <div class="col-lg-12">
                                                <div id="container" class="my-3">
                                                    <div class="textarea-container">
                                                        <textarea rows="5" class="form-control tiny_textarea" name="bank_details"><?php echo count($banks) ? $banks[0] : null; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label for="">Office Address</label>
                                        <div class="row my-3">
                                            <div class="col-lg-12">
                                                <div id="container" class="my-3">
                                                    <div class="textarea-container">
                                                        <textarea rows="5" class="form-control tiny_textarea" name="office_address"><?php echo count($offices) ? $offices[0] : null; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- <button type="button" class="btn btn-primary" id="add-textarea">Add More Textarea</button> -->

                                    <!-- <script>
                                        document.getElementById('add-textarea').addEventListener('click', function() {
                                            var container = document.getElementById('container');
                                            var textareaContainer = document.createElement('div');
                                            textareaContainer.classList.add('textarea-container');

                                            var textarea = document.createElement('textarea');
                                            textarea.setAttribute('name', 'textarea[]');
                                            textarea.setAttribute('rows', '1');
                                            textarea.classList.add('form-control');

                                            textareaContainer.appendChild(textarea);
                                            container.appendChild(textareaContainer);
                                        });
                                    </script> -->
                                    <div id="uplpr"></div>
                                    <input type="hidden" name="country_id" value="<?php echo $pv->id; ?>">
                                    <button id="mycountry_btn" class="btn btn-light btn-block" name="update_country_btn" type="button">UPDATE</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-dark" href="/<?php echo home; ?>/list-all-countries">Back</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php pkAjax_form("#mycountry_btn", "#country_form", "#uplpr"); ?>
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
            <script>
                tinymce.init({
                    selector: '.tiny_textarea',
                    plugins: 'code anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                });
            </script>
        </main>
        <?php import("apps/view/inc/footer-credit.php"); ?>
    </div>
</div>
<?php
import("apps/view/inc/footer.php");
?>