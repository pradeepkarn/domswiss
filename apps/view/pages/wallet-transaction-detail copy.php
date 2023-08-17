<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
if (!authenticate()) {
    die('Login required');
}
if (!isset($_GET['tid'])) {
    die('Transaction id is required');
}
if (!intval($_GET['tid'])) {
    die('Transaction id is invalid');
}
$unpaid_cmsn = getData('credits',intval($_GET['tid']));
if ($unpaid_cmsn==false) {
    die('Data not found in database');
}

?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <!-- <h1 class="mt-4">Dashboard</h1> -->
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active mycl">Withdrawal request by </li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div id="res"></div>
                            <table id="datatablesSimple" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Trans. ID</th>
                                        <th>Payee Username</th>
                                        <th>Payee Email</th>
                                        <th>Date</th>
                                        <th>Client Requested to get</th>
                                        <th>Amount</th>
                                        <th>Info</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Trans. ID</th>
                                        <th>Payee Username</th>
                                        <th>Payee Email</th>
                                        <th>Date</th>
                                        <th>Client requested to get:</th>
                                        <th>Amount</th>
                                        <th>Info</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    $remark = 'requested';
                                    if (isset($_GET['remark'])) {
                                        $remark = $_GET['remark'];
                                    }else{
                                        $remark = 'requested';
                                    }
                                    // $db = new Dbobjects;
                                    // $sql = "SELECT * FROM credits WHERE status = 'paid' AND remark = '$remark' ORDER BY calculated_on DESC;";
                                    // $unpaid_cmsn = $db->show($sql);
                                    ?>
                                    <?php 
                                        $cms = obj($unpaid_cmsn);
                                        $db = new Dbobjects;
                                        $sql = "SELECT * FROM pk_user WHERE pk_user.id = $cms->user_id;";
                                        $payeedata = $db->show($sql);
                                        $payee = null;
                                        if (count($payeedata)>0) {
                                            $payee = obj($payeedata[0]);
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $cms->id; ?></td>
                                            <td><?php echo $payee?$payee->username:"NA"; ?></td>
                                            <td><?php echo $payee?$payee->email:"NA"; ?></td>
                                            <td><?php echo $cms->paid_on; ?></td>
                                            <td><?php echo $cms->status; ?></td>
                                            <td><?php echo $cms->amt; ?></td>
                                            <td><?php echo $cms->info; ?></td>
                                            <td>
                                            <?php
                                                    if (is_superuser()) {
                                                        if ($cms->remark=='requested') {
                                                    ?>
                                                        <button data-bs-target="#orderstatusmodal<?php echo $cms->id; ?>" data-bs-toggle="modal" class="btn btn-secondary">Request Confirm</button>
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

                                                <?php  } else{
                                                    echo "Confirmed";
                                                }
                                                    }
                                                ?>
                                            </td>
                                        </tr>


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