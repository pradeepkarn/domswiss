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
                    <li class="breadcrumb-item active">Referral List</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="birth_cls" style="font-size: 1.125rem;">Upcoming birthdays in the next
                                        <select name="days" id="">
                                            <option value="0" selected>0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                        </select>
                                        days
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive mb-4">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>UserID</th>
                                                    <th>Surname</th>
                                                    <th>e-mail</th>
                                                    <th>phone</th>
                                                    <th>birth date</th>
                                                    <th>old</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row mb-4">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="birth_cls" style="font-size: 1.125rem;">Unilevel (number of enrolled partners in the system below you)
                                        </h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive mb-4">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>In total</th>
                                                            <th>level 1</th>
                                                            <th>level 2</th>
                                                            <th>level 3</th>
                                                            <th>level 4</th>
                                                            <th>level 5</th>
                                                            <th>level 6</th>
                                                            <th>level 7</th>
                                                            <th>level 8</th>
                                                            <th>level 9</th>
                                                            <th>level 10</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th>0</th>
                                                            <th>0</th>
                                                            <th>0</th>
                                                            <th>0</th>
                                                            <th>0</th>
                                                            <th>0</th>
                                                            <th>0</th>
                                                            <th>0</th>
                                                            <th>0</th>
                                                            <th>0</th>
                                                            <th>0</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Surname</th>
                                        <th>username</th>
                                        <th>position</th>
                                        <th>referal date</th>
                                        <th>status</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Surname</th>
                                        <th>username</th>
                                        <th>position</th>
                                        <th>referal date</th>
                                        <th>status</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    if (authenticate() == true) {
                                        $userObj = new Model('pk_user');

                                        $arr = null;
                                        $arr['ref'] = $_SESSION['user_id'];
                                        $partner = $userObj->filter_index($assoc_arr = $arr, $ord = 'DESC', $limit = 999,                                         $change_order_by_col = "");
                                    }

                                    $pvctrl = new Pv_ctrl;
                                    $pvctrl->db = new Dbobjects;


                                    foreach ($partner as $value) {
                                        $is_active = $pvctrl->check_active($value['id']);
                                    ?>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo $value['last_name']; ?></th>
                                            <th><?php echo $value['username']; ?></th>
                                            <th>positon</th>
                                            <th><?php echo $value['created_at']; ?></th>
                                            <th><?php echo $is_active?'Active':'In active'; ?></th>
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