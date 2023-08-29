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
                    <li class="breadcrumb-item active">Structure Tree (Partners)</li>
                </ol>

                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h4 class="card-title text-center">Structure Tree (Partners)</h4>
                                        <hr>
                                        <!-- <i>There are no registered partners</i> -->
                                        <ul>
                                            <?php
                                            if (authenticate() == true) {

                                                $pvctrl = new Pv_ctrl;
                                                $pvctrl->db = new Dbobjects;
                                                $partner = $pvctrl->my_tree($_SESSION['user_id']);
                                            $tree = structure_tree($partner);
                                            ?>
                                            <style>
                                                ul,
                                                #myUL {
                                                    list-style-type: none;
                                                }

                                                #myUL {
                                                    margin: 0;
                                                    padding: 0;
                                                }
                                                #myUL li{
                                                    margin-bottom: 10px;
                                                }

                                                .caret {
                                                    cursor: pointer;
                                                    -webkit-user-select: none;
                                                    /* Safari 3.1+ */
                                                    -moz-user-select: none;
                                                    /* Firefox 2+ */
                                                    -ms-user-select: none;
                                                    /* IE 10+ */
                                                    user-select: none;
                                                }

                                                .caret::before {
                                                    content: "\25B6";
                                                    color: black;
                                                    display: inline-block;
                                                    margin-right: 6px;
                                                }

                                                .caret-down::before {
                                                    -ms-transform: rotate(90deg);
                                                    /* IE 9 */
                                                    -webkit-transform: rotate(90deg);
                                                    /* Safari */
                                                    transform: rotate(90deg);
                                                }

                                                .nested {
                                                    display: none;
                                                }

                                                .active {
                                                    display: block;
                                                }
                                                .has-members{
                                                    font-size: 18px;
                                                }
                                            </style>
                                            <ul id="myUL">
                                                <?php echo $tree; ?>
                                            </ul>
                                            <?php } ?>
                                            <script>
                                                var toggler = document.getElementsByClassName("caret");
                                                var i;

                                                for (i = 0; i < toggler.length; i++) {
                                                    toggler[i].addEventListener("click", function() {
                                                        this.parentElement.querySelector(".nested").classList.toggle("active");
                                                        this.classList.toggle("caret-down");
                                                    });
                                                }
                                            </script>
                                        </ul>
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