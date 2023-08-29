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
                    <li class="breadcrumb-item active">Statistics</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="birth_cls" style="font-size: 1.125rem; font-weight: 300;">Sales of direct partners
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if (authenticate() == true) {
                                        $salesCtrl = new Sales_ctrl;
                                        $pasle = $salesCtrl->total_partner_sale($my_id = USER['id']);
                                        echo $pasle;
                                    }
                                    ?>/-
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="birth_cls" style="font-size: 1.125rem; font-weight: 300;">Customer Sales
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <p>€0.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="birth_cls" style="font-size: 1.125rem; font-weight: 300;">Order quantities from your partners
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if (authenticate() == true) {
                                        $pasle = $salesCtrl->total_partner_sale($my_id = USER['id']);
                                        $orders = $salesCtrl->partner_order_list($my_id);
                                        // myprint($orders);
                                    }
                                    ?>
                                    <table id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Date</th>
                                                <th>Partner</th>
                                                <th>product</th>
                                                <th>Order Qty</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Date</th>
                                                <th>Partner</th>
                                                <th>product</th>
                                                <th>Order Qty</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php foreach ($orders as $key => $ord) {

                                            ?>
                                                <tr>
                                                    <td><?php echo $ord->id; ?></td>
                                                    <td><?php echo $ord->invoice_date; ?></td>
                                                    <td><?php echo $ord->partner; ?></td>
                                                    <td><?php echo $ord->pkg_name; ?></td>
                                                    <td><?php echo $ord->qty; ?></td>
                                                </tr>
                                            <?php  } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="birth_cls" style="font-size: 1.125rem; font-weight: 300;">Partner list with sales
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table id="datatablesSimple1">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Rank</th>
                                                <th>Sales</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Username</th>
                                                <th>Rank</th>
                                                <th>Sales</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                      

                                            <?php 
                                            $salesCtrl = new Sales_ctrl;
                                            $patner_sale_group = $salesCtrl->partner_sale_list(USER['id']);
                                            // myprint($patner_sale_group);
                                            foreach ($patner_sale_group as $key => $ord) {
                                                // $ord = obj($ord);

                                                ?>
                                                    <tr>
                                                        <td><?php echo $ord->partner; ?></td>
                                                        <td><?php echo $ord->position; ?></td>
                                                        <td><?php echo $ord->amount; ?></td>
                                                    </tr>
                                                <?php  } ?>
                                        </tbody>
                                    </table>
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