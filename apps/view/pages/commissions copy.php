<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
$earns = [];
if (authenticate() == true) {
    $userid = USER['id'];
    $active_date = last_active_date($userid);
    $tree  = my_tree($ref = $userid, 1, $active_date);
    // $depth = 1;
    $treeLength = count($tree);
    $calc = calculatePercentageSum($data = $tree, $depth = 1, $treeLength, $userid = $userid);
    $sum = $calc['sum'];
    $rv_sum = $calc['rv_sum'] + my_rv_and_admin_rv($user_id = $userid, $dbobj = null);
    $jsonData = json_encode($tree, JSON_PRETTY_PRINT);
    $file = "jsondata/trees/tree_" . $userid . '.json';
    file_put_contents($file, $jsonData);

    // if (file_exists(RPATH . "/jsondata/trees/earning_" . $userid . '.json')) {
    //     $json_data = file_get_contents(RPATH . "/jsondata/trees/earning_" . $userid . '.json');
    // }

    $db = new Model('credits');
    $crarr['user_id'] = $userid;
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
    $direct_bonus = total_bonus($user_id);
    $drb['user_id'] = $user_id;
    $drb['status'] = 'direct_bonus';
    $alrdbns = $db->filter_index($drb);
    if (count($alrdbns) > 0) {
        $drbid = obj($alrdbns[0]);
        $drb['amt'] = $direct_bonus;
        $db->update($drbid->id, $drb);
    } else {
        $drb['amt'] = $direct_bonus;
        $db->store($drb);
    }
    // echo $json_data;
    // $earns = json_decode($json_data);
    $position = getPosition($level = $rv_sum);
}
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Commissions</h1>
                <ol class="breadcrumb mb-4 mypop">
                    <li class="breadcrumb-item active">Commissions</li>
                </ol>
                <div class="container">

                    <div class="row mb-4">

                        <div class="col-md-6 mx-auto">
                            <div class="shadow-sm card h-100 px-3 py-2">
                                <h5>Your Retirement Foundation</h5>
                                Total Share Count <?php
                                                    $share = my_all_share_count($userid);
                                                    echo $share;
                                                    ?>
                            </div>
                        </div>
                        <?php if (is_superuser()) { ?>

                            <div class="col-md-6 mx-auto">
                                <div class="shadow-sm card h-100 px-3 py-2">
                                    <h5>All Retirement Foundation</h5>
                                    Total Share Count <?php
                                                        $share_count_all = all_user_share_count();
                                                        echo $share_count_all['share_count'];
                                                        ?>
                                </div>
                            </div>
                        <?php  } ?>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12 text-center">
                            <!-- <h3>Direct Bonus: <?php echo $direct_bonus;
                                                    ?> </h3> -->
                            <h3>Position: <?php echo $position . " RV: [$rv_sum]"; ?> </h3>
                        </div>
                        <div class="col-md-4">
                            <div class="shadow-sm card h-100 px-3 py-2">
                                <p>All commissions since starting</p>
                                <?php
                                $share = my_all_share($userid);
                                // myprint($share);
                                // transactionAmt(array('user'=>1,'money_out'=>10));
                                $db = new Dbobjects;
                                $sql = "select SUM(amt) as total_amt from credits where user_id = {$userid} and status = 'lifetime'";
                                $cmsn = $db->show($sql);
                                $lifetime_m = $cmsn[0]['total_amt'] ? $cmsn[0]['total_amt'] : 0;
                                ###############################################
                                $sql = "select SUM(amt) as total_amt from credits where user_id = {$userid} and status = 'direct_bonus'";
                                $cmsn = $db->show($sql);
                                $direct_m = $cmsn[0]['total_amt'] ? $cmsn[0]['total_amt'] : 0;
                                ###############################################
                                $sql = "select SUM(amt) as total_amt from credits where status = 'paid' and user_id = {$userid}";
                                $cmsn = $db->show($sql);
                                $tm_paid = $cmsn[0]['total_amt'] ? round(($cmsn[0]['total_amt']), 2) : 0;
                                $lifetime_m = round(($lifetime_m + $direct_m + $share), 2);
                                echo $lifetime_m;
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="shadow-sm card h-100 px-3 py-2">
                                <p> Free money to be paid out</p>
                                <?php
                                echo round(($lifetime_m - $tm_paid), 2);
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

                    <div class="row mb-4">

                        <div class="col-md-6">
                            <div class="shadow-sm card h-100 px-3 py-2">
                                <h5>Last month share paid</h5>
                                <?php
                                $share = my_last_month_share($userid);
                                echo $share;
                                ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="shadow-sm card h-100 px-3 py-2">
                                <h5>Lifetime share paid</h5>
                                <?php
                                $share = my_all_share($userid);
                                echo $share;
                                ?>
                            </div>
                        </div>

                    </div>
                    <?php
                    $salesCtrlLastMonth = new Sales_ctrl;
                    $currentDate = new DateTime();

                    // Calculate the first day of the last month
                    $firstDayLastMonth = clone $currentDate;
                    $firstDayLastMonth->modify('first day of last month');
                    $salesCtrlLastMonth->firstDay = $firstDayLastMonth->format('Y-m-01');

                    // Calculate the last day of the last month
                    $lastDayLastMonth = clone $currentDate;
                    $lastDayLastMonth->modify('last day of last month');
                    $salesCtrlLastMonth->lastDay = $lastDayLastMonth->format('Y-m-t');

                    // $sale_last_month = $salesCtrlLastMonth->get_sale_volume();
                    $sale_last_month = $salesCtrlLastMonth->get_pv_volume();


                    $last_month_shr = $salesCtrlLastMonth->distribute_share_by_pool($total_sale = $sale_last_month);
                    ?>

                    <div class="row my-3">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    if (is_superuser()) {
                                        echo "<h4>Total Sale for last month was = $sale_last_month</h4>";
                                        echo "<h4>Total Share for last month was = " . round(($sale_last_month * 0.12), 2) . "</h4>";
                                        echo "<h4>Total Share of each pool for last month was = " . round((($sale_last_month * 0.12) / 4), 2) . "</h4>";
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    $salesCtrl = new Sales_ctrl;
                                    // $sale = $salesCtrl->get_sale_volume();
                                    $sale = $salesCtrl->get_pv_volume();
                                    $shr = $salesCtrl->distribute_share_by_pool($total_sale = $sale);
                                    if (is_superuser()) {
                                        echo "<h4>Total Sale for this month is = $sale</h4>";
                                        echo "<h4>Total Share for this month is = " . round(($sale * 0.12), 2) . "</h4>";
                                        echo "<h4>Total Share for each pool is = " . round((($sale * 0.12) / 4), 2) . "</h4>";
                                    } ?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <?php
                    $myshares = array(
                        'pool1' =>  0,
                        'pool2' =>  0,
                        'pool3' =>  0,
                        'pool4' =>  0
                    );
                    $file = RPATH . "/jsondata/pool/members.json";
                    if (file_exists($file)) {
                        $jsndta = file_get_contents($file);
                        $mmbrs = json_decode($jsndta, true);
                    }
                    $shredivs = RPATH . "/jsondata/pool/share_divs.json";
                    if (file_exists($shredivs)) {
                        $shrsdta = file_get_contents($shredivs);
                        $shresexmpls = json_decode($shrsdta, true);
                    }
                    if (isset($shresexmpls[strtolower($position)])) {
                        $dtss = $shresexmpls[strtolower($position)];
                        $myshares = array(
                            'pool1' =>  $dtss['pool1'] * $shr['pool1']['unit_value'],
                            'pool2' =>  $dtss['pool2'] * $shr['pool2']['unit_value'],
                            'pool3' =>  $dtss['pool3'] * $shr['pool3']['unit_value'],
                            'pool4' =>  $dtss['pool4'] * $shr['pool4']['unit_value']
                        );
                        $myshare_count = array(
                            'pool1' =>  $dtss['pool1'],
                            'pool2' =>  $dtss['pool2'],
                            'pool3' =>  $dtss['pool3'],
                            'pool4' =>  $dtss['pool4']
                        );
                        // myprint($myshares);
                    }
                    ?>
                    <table class="table table-bordered">
                        <tr class="bg-primary text-white">
                            <th>AFFILIATE Partner</th>
                            <td>PV POINTS 50/50</td>
                            <td>Pool 4
                                <hr>
                                <div class="bg-warning text-dark p-1">My earned share: <?php echo $myshares['pool4']; ?></div>
                                <div class="bg-warning text-dark p-1">My share count: <?php echo $myshare_count['pool4']; ?></div>
                                <hr>
                                <br> Pool4 Share = <?php echo $shr['share_for_each_pool']; ?>
                                <br> Share Count = <?php echo $shr['pool4']['share_count']; ?>
                                <br> Unit value = <?php echo $shr['pool4']['unit_value']; ?>/share
                            </td>
                            <td>Pool 3
                                <hr>
                                <div class="bg-warning text-dark p-1"> My earned share: <?php echo $myshares['pool3']; ?></div>
                                <div class="bg-warning text-dark p-1">My share count: <?php echo $myshare_count['pool3']; ?></div>
                                <hr>
                                <br> Pool3 Share = <?php echo $shr['share_for_each_pool']; ?>
                                <br> Share Count = <?php echo $shr['pool3']['share_count']; ?>
                                <br> Unit value = <?php echo $shr['pool3']['unit_value']; ?>/share
                            </td>
                            <td>Pool 2
                                <hr>
                                <div class="bg-warning text-dark p-1"> My earned share: <?php echo $myshares['pool2']; ?></div>
                                <div class="bg-warning text-dark p-1">My share count: <?php echo $myshare_count['pool2']; ?></div>
                                <hr>
                                <br> Pool2 Share = <?php echo $shr['share_for_each_pool']; ?>
                                <br> Share Count = <?php echo $shr['pool2']['share_count']; ?>
                                <br> Unit value = <?php echo $shr['pool2']['unit_value']; ?>/share
                            </td>
                            <td>Pool l
                                <hr>
                                <div class="bg-warning text-dark p-1">My earned share: <?php echo $myshares['pool1']; ?></div>
                                <div class="bg-warning text-dark p-1">My share count: <?php echo $myshare_count['pool1']; ?></div>
                                <hr>
                                <br> Pool1 Share = <?php echo $shr['share_for_each_pool']; ?>
                                <br> Share Count = <?php echo $shr['pool1']['share_count']; ?>
                                <br> Unit value = <?php echo $shr['pool1']['unit_value']; ?>/share
                            </td>
                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 500 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">BRONZE Manager <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('BRONZE Manager')];
                                ?> members
                            </th>
                            <td>
                                500 <?php echo $rv_sum >= 500 ? "<i class='fas fa-check'></i>" : null; ?>
                            </td>
                            <td class="text-center" colspan="4">Shares in Pool</td>
                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 1000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">SILVER Manager <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('SILVER Manager')];
                                ?> members
                            </th>
                            <td>
                                1000 <?php echo $rv_sum >= 1000 ? "<i class='fas fa-check'></i>" : null; ?>
                            </td>
                            <td></td>

                            <td></td>

                            <td></td>

                            <td>1</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 2500 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">GOLD Manager <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('GOLD Manager')];
                                ?> members
                            </th>
                            <td>
                                2500 <?php echo $rv_sum >= 2500 ? "<i class='fas fa-check'></i>" : null; ?>
                            </td>
                            <td></td>

                            <td></td>

                            <td></td>

                            <td>2</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 5000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">PLATINUM Manager <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('PLATINUM Manager')];
                                ?> members
                            </th>
                            <td>
                                5000 <?php echo $rv_sum >= 5000 ? "<i class='fas fa-check'></i>" : null; ?>
                            </td>
                            <td></td>

                            <td></td>

                            <td></td>

                            <td>3</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 10000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">DIRECTOR <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('DIRECTOR')];
                                ?> members
                            </th>
                            <td>10000 <?php echo $rv_sum >= 10000 ? "<i class='fas fa-check'></i>" : null; ?></td>
                            <td></td>

                            <td></td>

                            <td>1</td>

                            <td>4</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 25000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">TEAM DIRECTOR <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('TEAM DIRECTOR')];
                                ?> members
                            </th>
                            <td>25000 <?php echo $rv_sum >= 25000 ? "<i class='fas fa-check'></i>" : null; ?></td>
                            <td></td>

                            <td></td>

                            <td>2</td>

                            <td>4</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 50000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">MARKETING DIRECTOR <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('MARKETING DIRECTOR')];
                                ?> members
                            </th>
                            <td>50000 <?php echo $rv_sum >= 50000 ? "<i class='fas fa-check'></i>" : null; ?></td>
                            <td></td>

                            <td></td>

                            <td>3</td>

                            <td>4</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 100000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">DIAMOND <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('DIAMOND')];
                                ?> members
                            </th>
                            <td>100000 <?php echo $rv_sum >= 100000 ? "<i class='fas fa-check'></i>" : null; ?></td>
                            <td></td>

                            <td>1</td>

                            <td>4</td>

                            <td>5</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 250000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">BLUE DIAMOND <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('BLUE DIAMOND')];
                                ?> members
                            </th>
                            <td>250000 <?php echo $rv_sum >= 250000 ? "<i class='fas fa-check'></i>" : null; ?></td>
                            <td></td>

                            <td>2</td>

                            <td>4</td>

                            <td>5</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 500000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">PURPLE DIAMOND <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('PURPLE DIAMOND')];
                                ?> members
                            </th>
                            <td>500000 <?php echo $rv_sum >= 500000 ? "<i class='fas fa-check'></i>" : null; ?></td>
                            <td></td>

                            <td>3</td>

                            <td>4</td>

                            <td>5</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 1000000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">GREEN DIAMOND <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('GREEN DIAMOND')];
                                ?> members
                            </th>
                            <td>1000000 <?php echo $rv_sum >= 1000000 ? "<i class='fas fa-check'></i>" : null; ?></td>
                            <td>1</td>

                            <td>4</td>

                            <td>5</td>

                            <td>5</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 2000000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">AMBASSADOR <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('AMBASSADOR')];
                                ?> members
                            </th>
                            <td>2000000 <?php echo $rv_sum >= 2000000 ? "<i class='fas fa-check'></i>" : null; ?></td>
                            <td>2</td>

                            <td>4</td>

                            <td>5</td>

                            <td>6</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 4000000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">ROYAL <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('ROYAL')];
                                ?> members
                            </th>
                            <td>4000000 <?php echo $rv_sum >= 4000000 ? "<i class='fas fa-check'></i>" : null; ?></td>
                            <td>3</td>

                            <td>4</td>

                            <td>5</td>

                            <td>6</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 8000000  ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">ROYAL I <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('ROYAL I')];
                                ?> members
                            </th>
                            <td>8000000 <?php echo $rv_sum >= 8000000 ? "<i class='fas fa-check'></i>" : null; ?></td>
                            <td>4</td>

                            <td>5</td>

                            <td>5</td>

                            <td>6</td>


                        </tr>
                        <tr class="text-center <?php echo $rv_sum >= 16000000 ? "bg-success text-white" : "bg-muted text-muted"; ?>">
                            <th class="text-start">ROYAL II <i class="fas fa-arrow-right"></i>
                                <?php
                                echo $mmbrs[strtolower('ROYAL II')];
                                ?> members
                            </th>
                            <td>16000000 <?php echo $rv_sum >= 16000000 ? "<i class='fas fa-check'></i>" : null; ?></td>
                            <td>5</td>

                            <td>5</td>

                            <td>6</td>

                            <td>6</td>
                        </tr>
                    </table>


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
                                        <input type="hidden" name="user" value="<?php echo $userid; ?>" min="0" scope="any" class="form-control">
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
