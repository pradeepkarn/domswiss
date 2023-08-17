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
                    <li class="breadcrumb-item active">downline status</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Date of Join</th>
                                        <th>username</th>
                                        <th>Surname</th>
                                        <th>E-mail</th>
                                        <th>phones</th>
                                        <th>vid</th>
                                        <th>status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    if (authenticate() == true) {
                                        $userObj = new Model('pk_user');

                                        $arr = null;
                                        $arr['ref'] = $_SESSION['user_id'];
                                        $partner = $userObj->filter_index($assoc_arr = $arr, $ord = 'DESC', $limit = 9999999,                                         $change_order_by_col = "");
                                    }

                                    foreach ($partner as $value) {
                                    ?>
                                        <tr>
                                            <th>Date of Join</th>
                                            <th><?php echo $value['username']; ?></th>
                                            <th><?php echo $value['last_name']; ?></th>
                                            <th><?php echo $value['email']; ?></th>
                                            <th><?php echo $value['isd_code']; ?> <?php echo $value['mobile']; ?></th>
                                            <th>vid</th>
                                            <th><?php echo $value['status']; ?></th>
                                        </tr>
                                    <?php
                                    }
                                    ?>

                                </tbody>
                            </table>
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