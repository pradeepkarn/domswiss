<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
if (!authenticate()) {
    die('Login required');
}
$user = $context['user'];
if (is_superuser()) :
    if (isset($_POST['rv']) && isset($_POST['added_to']) && isset($_POST['action'])) {
        if (intval($_POST['rv']) && intval($_POST['added_to'])) {
            $arrrv['rv'] = abs($_POST['rv']);
            $arrrv['added_to'] = intval($_POST['added_to']);
            $arrrv['added_by'] = USER['id'];
            $rvdb = new Model('rank_advance');
            $rvdb->store($arrrv);
        }
    }
    if (isset($_POST['delete_rv_id']) && isset($_POST['action'])) {
        if (intval($_POST['delete_rv_id'])) {
            $rvdb = new Model('rank_advance');
            $rvdb->destroy($_POST['delete_rv_id']);
        }
    }
    ######################################endregion  if (isset($_POST['rv']) && isset($_POST['added_to']) && isset($_POST['action'])) {
    if (isset($_POST['commission']) && isset($_POST['added_to']) && isset($_POST['action'])) {
        if (intval($_POST['commission']) && intval($_POST['added_to'])) {
            $arrrv['rv'] = abs($_POST['rv']);
            $arrrv['added_to'] = intval($_POST['added_to']);
            $arrrv['added_by'] = USER['id'];
            $arrrv['amount'] = abs(floatval($_POST['commission']));
            if ($arrrv['amount'] > 0) {
                $rvdb = new Model('extra_credits');
                $rvdb->store($arrrv);
            }
        }
    }
    if (isset($_POST['delete_amt_id']) && isset($_POST['action']) && $_POST['action']=="cmsndlt") {
        if (intval($_POST['delete_amt_id'])) {
            $rvdb = new Model('extra_credits');
            $rvdb->destroy($_POST['delete_amt_id']);
        }
    }

endif;
$udata = obj((new User_ctrl)->my_all_commission($userid = $user['id']));
$position = $udata->position;
$cmsn_gt = $udata->cmsn_gt;
$total_paid = $udata->total_paid;
$total_unpaid = $udata->total_unpaid;
$rv_sum = $udata->rv_gt;
// $last_date = last_active_date($user_id = $user['id']);
// $tree  = my_tree($ref = $user['id'], 1, $last_date);
// $depth = 1;
// $treeLength = count($tree);
// $calc = calculatePercentageSum($data = $tree, $depth, $treeLength, $user['id']);
// $sum = $calc['sum'];
// $rv_sum = $calc['rv_sum'] + my_rv_and_admin_rv($user_id = $user['id'], $dbobj = null);
// $jsonData = json_encode($tree, JSON_PRETTY_PRINT);
// $file = "jsondata/trees/tree_" . $user['id'] . '.json';
// file_put_contents($file, $jsonData);
// $db = new Model('credits');
// $crarr['user_id'] = $user['id'];
// $crarr['status'] = 'lifetime';
// $already = $db->filter_index($crarr);
// if (count($already) > 0) {
//     $crid = obj($already[0]);
//     $crarr['amt'] = $sum;
//     $db->update($id = $crid->id, $crarr);
// } else {
//     $crarr['amt'] = $sum;
//     $db->store($crarr);
// }
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <!-- <h1 class="mt-4">Dashboard</h1> -->
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active mycl">My Profile</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-lg-4">
                            <div class="spl-box">
                                <div class="new-box">
                                    <img src="/<?php echo home; ?>/media/img/user-blank.png" class="img-circle" width="150px" alt="" srcset="">
                                    <h4 class="mt-2 mypl"><?php echo $user['username']; ?></h4>
                                    <h6 class="mypl1 mb-3"><?php echo $user['first_name']; ?> <?php echo $user['last_name']; ?></h6>
                                    <p class="mypl1">ID: <span><?php echo $user['id']; ?></span></p>
                                    <hr class="mypr">
                                </div>

                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form action="" method="post">
                                        <div class="row m">
                                            <div class="col-md-6 my-3">
                                                <input  min="0" placeholder="Commission" type="text" scope="any" name="commission" class="form-control">
                                                <input type="hidden" name="added_to" value="<?php echo $user['id']; ?>">
                                                <input type="hidden" name="action" value="add_cmsn">
                                            </div>
                                            <div class="col-md-6 my-3">
                                                <button type="submit" class="btn btn-primary">Add commission</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row mb-4">

                                <div class="col-md-12">
                                    <div class="shadow-sm card h-100 px-3 py-2">
                                        <h5>Lifetime Commission
                                            <?php echo $cmsn_gt; ?>

                                        </h5>
                                        <table  class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Trans. ID</th>
                                                    <th>Commission</th>
                                                    <th>Date</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                        
                                            <tbody>
                                                <?php
                                                $db = new Dbobjects;
                                                $sql = "select * from extra_credits where added_to = {$user['id']}";
                                                $extracc = $db->show($sql);
                                                $total_extracc = 0;
                                                ?>
                                                <?php foreach ($extracc as $extcc) {
                                                    $extcc = obj($extcc);
                                                    $total_extracc += $extcc->amount;
                                                ?>
                                                    <tr>
                                                        <td><?php echo $extcc->id; ?></td>
                                                        <td><?php echo $extcc->amount; ?></td>
                                                        <td><?php echo $extcc->created_at; ?></td>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="delete_amt_id" value="<?php echo $extcc->id; ?>">
                                                                <input type="hidden" name="action" value="cmsndlt">
                                                                <button class="btn btn-sm btn-danger">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php } ?>

                                            </tbody>
                                        </table>
                                        <h5>Retirement Foundation</h5>
                                        Total Share Count <?php
                                                            $share = my_all_share_count($user['id']);
                                                            echo $share;
                                                            ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div id="res"></div>

                            <div class="row">
                                <!-- form -->
                                <form class="row g-3" id="update-profile-form" method="post" action="/<?php echo home; ?>/update-profile-by-admin-ajax">
                                    <div class="col-md-12 my-2">
                                        <label for="inputEmail4" class="form-label">Email</label>
                                        <input type="email" name="email" value="<?php echo $user['email']; ?>" class="form-control" id="inputEmail4">
                                    </div>
                                    <div class="col-md-6 my-2">
                                        <label for="inputUsername" class="form-label">Username</label>
                                        <input type="text" name="username" value="<?php echo $user['username']; ?>" class="form-control" id="inputUsername">
                                    </div>
                                    <div class="col-md-6 my-2">
                                        <label for="inputPassword4" class="form-label">Password</label>
                                        <input type="password" autocomplete="off" name="password" value="<?php echo $user['password']; ?>" class="form-control" id="inputPassword4">
                                        <input type="hidden" name="userid" value="<?php echo $user['id']; ?>">
                                        <input type="checkbox" name="change_password">
                                    </div>
                                    <div class="col-12 my-2">
                                        <div class="form-check">
                                            <input class="form-check-input" name="checkmeout" type="checkbox" id="gridCheck">
                                            <label class="form-check-label" for="gridCheck">
                                                Check me out
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button id="updateprofile" type="button" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                                <?php pkAjax_form("#updateprofile", "#update-profile-form", "#res"); ?>



                                <!-- form end-->
                                <form action="" method="post">
                                    <div class="row m">
                                        <div class="col-md-6 my-3">
                                            <input min="0" placeholder="RV" type="number" name="rv" class="form-control">
                                            <input type="hidden" name="added_to" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="action" value="add_rv">
                                        </div>
                                        <div class="col-md-6 my-3">
                                            <button type="submit" class="btn btn-primary">Add on</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row mb-4">
                                    <div class="col-lg-12">
                                        <table id="datatablesSimple" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Trans. ID</th>
                                                    <th>RV</th>
                                                    <th>Date</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Trans. ID</th>
                                                    <th>RV</th>
                                                    <th>Date</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                                $db = new Dbobjects;
                                                $sql = "select * from rank_advance where added_to = {$user['id']} and status = 'confirmed'";
                                                $rvs = $db->show($sql);
                                                $total_rv = 0;
                                                ?>
                                                <?php foreach ($rvs as $rvarr) {
                                                    $rv = obj($rvarr);
                                                    $total_rv += $rv->rv;
                                                ?>
                                                    <tr>
                                                        <td><?php echo $rv->id; ?></td>
                                                        <td><?php echo $rv->rv; ?></td>
                                                        <td><?php echo $rv->created_at; ?></td>
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="delete_rv_id" value="<?php echo $rv->id; ?>">
                                                                <input type="hidden" name="action" value="delete">
                                                                <button class="btn btn-sm btn-danger">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php } ?>

                                            </tbody>
                                        </table>
                                        <h4>Total RV by admin : <?php echo $total_rv; ?></h4>
                                        <h3>Total RV = <?php echo $rv_sum; ?></h3>

                                    </div>
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