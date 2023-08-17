<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
$earns = [];
if (authenticate() == true) {
    $ldate = last_active_date($_GET['userid']);
    $tree  = my_tree($ref = $_GET['userid'],1, $ldate);
    $depth = 1;
    $treeLength = count($tree);
    $calc = calculatePercentageSum($data = $tree, $depth, $treeLength, $userid = $_GET['userid']);
    $sum = $calc['sum'];
    $jsonData = json_encode($tree, JSON_PRETTY_PRINT);
    $file = "jsondata/trees/tree_" . $_GET['userid'] . '.json';
    file_put_contents($file, $jsonData);

    if (file_exists(RPATH . "/jsondata/trees/earning_" . $_GET['userid'] . '.json')) {
        $json_data = file_get_contents(RPATH . "/jsondata/trees/earning_" . $_GET['userid'] . '.json');
    }

    $db = new Model('credits');
    $crarr['user_id'] = $_GET['userid'];
    $crarr['status'] = 'lifetime';
    $already = $db->filter_index($crarr);
    if (count($already) > 0) {
        $crid = obj($already[0]);
        $crarr['amt'] = $sum;
        $db->update($id = $crid->id, $crarr);
    } else {
        $crarr['amt'] = $sum;
        $db->store($crarr);
    }
    // echo $json_data;
    // $earns = json_decode($json_data);
}
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <!-- <h1 class="mt-4">Dashboard</h1> -->
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active">Credit Overview</li>
                </ol>

                <div class="container">

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="shadow-sm card h-100 px-3 py-2">
                                <p>All commissions since starting</p>
                                <?php
                                $db = new Dbobjects;
                                $sql = "select SUM(amt) as total_amt from credits where user_id = {$_GET['userid']} and status = 'lifetime'";
                                $cmsn = $db->show($sql);
                                $lifetime_m = $cmsn[0]['total_amt'] ? $cmsn[0]['total_amt'] : 0;
                                ###############################################
                                $sql = "select SUM(amt) as total_amt from credits where status = 'paid' and user_id = {$_GET['userid']}";
                                $cmsn = $db->show($sql);
                                $tm_paid = $cmsn[0]['total_amt'] ? $cmsn[0]['total_amt'] : 0;

                                echo $lifetime_m;
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="shadow-sm card h-100 px-3 py-2">
                                <p> Free money to be paid out</p>
                                <?php
                                // $sql = "select SUM(amt) as total_amt from credits where status = 'unpaid' and user_id = {$_GET['userid']}";
                                // $cmsn = $db->show($sql);
                                // $tm = $cmsn[0]['total_amt']?$cmsn[0]['total_amt']:0;
                                echo $lifetime_m - $tm_paid;
                                ?>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#withdrawMoney">Withdraw</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="shadow-sm card h-100 px-3 py-2">
                                <p>All commissions are paid out</p>
                                <?php

                                echo $tm_paid;
                                ?>

                            </div>
                        </div>

                    </div>
                    <!-- Modal -->
                    <div class="modal" id="withdrawMoney" tabindex="-1" aria-labelledby="withdrawMoneyLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Enter amount to withdraw: </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="res"></div>
                                    <form id="withdraw-amt" action="/<?php echo home; ?>/money-withdraw">
                                        <input type="number" name="money_out" value="10" min="0" scope="any" class="form-control">
                                        <input type="hidden" name="user" value="<?php echo $_GET['userid']; ?>" min="0" scope="any" class="form-control">
                                        <button id="submit-withdraw" type="button" class="btn btn-primary my-3">Confirm</button>
                                    </form>
                                    <?php pkAjax_form("#submit-withdraw", "#withdraw-amt", "#res"); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal end -->
                </div>
            </div>

        </main>

        <?php import("apps/view/inc/footer-credit.php"); ?>
    </div>
</div>
<?php
import("apps/view/inc/footer.php");
?>