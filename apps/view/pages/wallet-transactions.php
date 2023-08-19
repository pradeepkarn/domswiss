<?php

use League\Csv\Writer;

$remark = 'requested';
if (isset($_GET['remark'])) {
    $remark = $_GET['remark'];
} else {
    $remark = 'requested';
}
$db = new Dbobjects;

$sql = "SELECT * FROM credits WHERE status = 'paid' AND remark = '$remark' ORDER BY calculated_on DESC;";
if ($remark=='cancelled') {
    $sql = "SELECT * FROM credits WHERE status = 'cancelled' AND remark = '$remark' ORDER BY calculated_on DESC;";
}
$unpaid_cmsn = $db->show($sql);


import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
if (!authenticate()) {
    die('Login required');
}
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <!-- <h1 class="mt-4">Dashboard</h1> -->
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active mycl">All withdrawal requests</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div id="res"></div>
                            <table id="datatablesSimple" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Trans. ID</th>
                                        <th>Country</th>
                                        <th>Payee Username</th>
                                        <th>Payee Email</th>
                                        <th>Date</th>
                                        <th>Client Requested to get</th>
                                        <th>Amount</th>
                                        <th>Info</th>
                                        <th>Details</th>
                                        <th class="text-center" colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Trans. ID</th>
                                        <th>Country</th>
                                        <th>Payee Username</th>
                                        <th>Payee Email</th>
                                        <th>Date</th>
                                        <th>Client requested to get:</th>
                                        <th>Amount</th>
                                        <th>Info</th>
                                        <th>Details</th>
                                        <th class="text-center" colspan="2">Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>

                                    <?php
                                    $csv_main_data = [];
                                    foreach ($unpaid_cmsn as $cms) {
                                        $cms = obj($cms);
                                        $sql = "SELECT * FROM pk_user WHERE pk_user.id = $cms->user_id;";
                                        $payeedata = $db->show($sql);
                                        $payee = null;
                                        if (count($payeedata) > 0) {
                                            $payee = obj($payeedata[0]);
                                            // for csv
                                            $csvdata['transaction_id'] = $payee->id;
                                            $csvdata['payee_id'] = $payee->id;
                                            $csvdata['payee_email'] = $payee->email;
                                            $csvdata['username'] = $payee->username;


                                            $bank_account = null;
                                            $bank_name = null;
                                            $swift_code = null;
                                            $iban = null;
                                            $country_code = null;
                                            $bank_country_name = null;
                                            $flag = null;
                                            $jsnob = $payee->jsn;
                                            $jsn = json_decode($jsnob);

                                            if (isset($jsn->banks)) {
                                                $bank_account = isset($jsn->banks[0]->bank_account) ? $jsn->banks[0]->bank_account : null;
                                                $bank_name = isset($jsn->banks[0]->bank_name) ? $jsn->banks[0]->bank_name : null;
                                                $swift_code = isset($jsn->banks[0]->swift_code) ? $jsn->banks[0]->swift_code : null;
                                                $iban = isset($jsn->banks[0]->iban) ? $jsn->banks[0]->iban : null;
                                                $country_code = isset($jsn->banks[0]->country_code) ? $jsn->banks[0]->country_code : null;
                                                $bank_country_name = isset($jsn->banks[0]->country_name) ? $jsn->banks[0]->country_name : null;
                                                $flag = isset(getCurrency($country_code)['flag']) ? getCurrency($country_code)['flag'] : null;

                                                $csvdata['bank_name'] = $bank_name;
                                                $csvdata['bank_account'] = $bank_account;
                                                $csvdata['iban'] = $iban;
                                                $csvdata['swift_code'] = $swift_code;
                                                $csvdata['country'] = $bank_country_name;
                                            }
                                        }
                                        $csvdata['amt'] = $cms->amt;
                                        $csvdata['paid_on'] = $cms->paid_on;
                                        $csvdata['status'] = $cms->status;
                                        $csvdata['remark'] = $cms->info;

                                    ?>
                                        <tr>
                                            <td><?php echo $cms->id; ?></td>
                                            <td><?php if ($flag) : ?>
                                                    <img src="data:image/png;base64,<?php echo $flag; ?>" alt="flag">
                                                    <?php echo $bank_country_name; ?>
                                                <?php else : ?>
                                                    Country Not updated
                                                <?php endif; ?>

                                            </td>
                                            <td><?php echo $payee ? $payee->username : "NA"; ?></td>
                                            <td><?php echo $payee ? $payee->email : "NA"; ?></td>
                                            <td><?php echo $cms->paid_on; ?></td>
                                            <td><?php echo $cms->status; ?></td>
                                            <td><?php echo $cms->amt; ?></td>
                                            <td><?php echo $cms->info; ?></td>
                                            <td>
                                                <?php if ($bank_account && $bank_name && $swift_code && $country_code) : ?>
                                                    <a target="_blank" href="/<?php echo home; ?>/withdrawal-requests/?tid=<?php echo $cms->id; ?>&remark=<?php echo $cms->remark; ?>">Open</a>
                                                <?php else : ?>
                                                    Waiting for bank details
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (is_superuser()) {
                                                    if ($cms->remark == 'requested') {
                                                ?>
                                                        <?php if ($bank_account && $bank_name && $swift_code && $country_code) : ?>
                                                            <button data-bs-target="#orderstatusmodal<?php echo $cms->id; ?>" data-bs-toggle="modal" class="btn btn-secondary">Confirm</button>
                                                        <?php else : ?>
                                                            Waiting for bank details
                                                        <?php endif; ?>

                                                        <!-- Modal -->
                                                        <div class="modal" id="orderstatusmodal<?php echo $cms->id; ?>">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">

                                                                    <!-- Modal header -->
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Mark this request as confirmed</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>

                                                                    <!-- Modal body -->
                                                                    <div class="modal-body">
                                                                        <textarea name="info" class="pmt<?php echo $cms->id; ?> form-control my-3" placeholder="Remark"></textarea>
                                                                        <input type="hidden" class="pmt<?php echo $cms->id; ?>" name="credit_id" value="<?php echo $cms->id; ?>">
                                                                        <button id="mark-this-paid<?php echo $cms->id; ?>" class="btn btn-success">Confirmed</button>
                                                                        <?php
                                                                        pkAjax("#mark-this-paid{$cms->id}", "/mark-this-request-as-confirmed-ajax", ".pmt{$cms->id}", "#res");
                                                                        ?>
                                                                    </div>

                                                                    <!-- Modal footer -->
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                <?php  } else {
                                                        echo $cms->remark;
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (is_superuser()) {
                                                    if ($cms->remark == 'requested') {
                                                ?>

                                                        <button data-bs-target="#orderCancelModal<?php echo $cms->id; ?>" data-bs-toggle="modal" class="btn btn-danger text-white">Cancel</button>


                                                        <!-- Modal -->
                                                        <div class="modal" id="orderCancelModal<?php echo $cms->id; ?>">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">

                                                                    <!-- Modal header -->
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Mark this request as cancelled</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>

                                                                    <!-- Modal body -->
                                                                    <div class="modal-body">
                                                                        <textarea name="info" class="cncelpmt<?php echo $cms->id; ?> form-control my-3" placeholder="Cancellation message"></textarea>
                                                                        <input type="hidden" class="cncelpmt<?php echo $cms->id; ?>" name="credit_id" value="<?php echo $cms->id; ?>">
                                                                        <button id="mark-this-cancelled<?php echo $cms->id; ?>" class="btn btn-danger text-white">Cancelled</button>
                                                                        <?php
                                                                        pkAjax("#mark-this-cancelled{$cms->id}", "/mark-this-request-as-cancelled-ajax", ".cncelpmt{$cms->id}", "#res");
                                                                        ?>
                                                                    </div>

                                                                    <!-- Modal footer -->
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                <?php  } else {
                                                    
                                                        echo $cms->remark;
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $csv_main_data[] =    $csvdata;
                                    }


                                    ?>

                                </tbody>
                            </table>
                            <a href="/<?php echo home; ?>/wallet-paid.csv" download>Download CSV</a>
                        </div>
                        <?php
                        if (count($csv_main_data) > 0) {
                            $filePath = 'wallet-paid.csv';
                            // Create a new CSV writer instance
                            $csv = Writer::createFromPath($filePath, 'w');
                            $headers = array_keys($csv_main_data[0]);
                            // Insert headers as the first row in the CSV file
                            $csv->insertOne($headers);
                            // Insert the data along with headers into the CSV file
                            $csv->insertAll($csv_main_data);
                        }
                        ?>
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