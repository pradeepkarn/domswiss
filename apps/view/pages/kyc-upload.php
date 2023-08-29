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
                    <li class="breadcrumb-item active">KYC Upload</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <h3 style="font-size:14px; color:#007cba; font-weight: 600; padding-top:5px">KYC DOCUMENTS</h3>
                            <hr>
                            <div class="card">
                                <div class="card-header kyc_media pt-3 pb-3">
                                    <h5 class="mb-0 identity_dc">identity document</h5>
                                    <h5 class="mb-0 not_upl">NOT UPLOADED</h5>
                                </div>
                                <div class="card-body">
                                    <p class="doc_para">In order for us to be able to process your payout we need to verify your identity. Please upload this document here. After successful verification of your payout should be available in a few days.</p>
                                    <p class="doc_para">Documents we accept for identity verification: <br>
                                        passport, identity card
                                    </p>
                                    <div class="col-12">
                                        <div class="drag_up">
                                            <div class="file-drop-area">
                                                <span class="choose-file-button upl_btn1">Choose files</span>
                                                <span class="file-message upl_btn1">Drag and drop a file here or click</span>
                                                <input class="file-input" type="file" multiple>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="card mt-5">
                                <div class="card-header kyc_media pt-3 pb-3">
                                    <h5 class="mb-0 identity_dc">Proof of address</h5>
                                    <h5 class="mb-0 not_upl">NOT UPLOADED</h5>
                                </div>
                                <div class="card-body">
                                    <p class="doc_para">In order for us to be able to process your payout we need to verify your address. Please upload this document here. After successful verification of your payout should be available in a few days.</p>
                                    <p class="doc_para">Documents we accept for address verification: <br>
                                        Utility Bill (Electric, Mobile Phone, Internet, ...)
                                    </p>
                                    <div class="col-12">
                                        <div class="drag_up">
                                            <div class="file-drop-area">
                                                <span class="choose-file-button upl_btn1">Choose files</span>
                                                <span class="file-message upl_btn1">Drag and drop a file here or click</span>
                                                <input class="file-input" type="file" multiple>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <button class="btn btn-primary kyc_upbtn1 mt-3">KYC upload</button>

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