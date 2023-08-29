<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
$plobj = new Model('countries');
$cl = $plobj->index();
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active">All Products</li>
                </ol>
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <table id="datatablesSimple1" class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Code</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Bank Details</th>
                                        <th scope="col">Office</th>
                                        <!-- <th scope="col">Delivery Info</th> -->
                                        <th scope="col">Min Tax</th>
                                        <th scope="col">Max Tax</th>
                                        <th scope="col">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cl as $key => $pv) :
                                        $pv = obj($pv);
                                        $jsn = json_decode($pv->jsn);
                                        $gateways = [];
                                        $banks = [];
                                        $offices = [];
                                       
                                        if (isset($jsn->banks)) {
                                            $banks = $jsn->banks;
                                        }
                                        if (isset($jsn->office_address)) {
                                            $offices = $jsn->office_address;
                                        }
                                        if (isset($jsn->gateways)) {
                                            $gateways = $jsn->gateways;
                                        }

                                    ?>
                                        <tr>
                                            <td><?php echo $pv->code; ?></td>
                                            <td><?php echo $pv->name; ?></td>
                                            <td><?php echo count($banks)?$banks[0]:null; ?></td>
                                            <td><?php echo count($offices)?$offices[0]:null; ?></td>
                                            <!-- <td><?php //echo $pv->delv_info; ?></td> -->
                                            <td><?php echo $pv->min_tax; ?></td>
                                            <td><?php echo $pv->max_tax; ?></td>
                                            <td>
                                                <a class="btn-primary btn btn-sm" href="/<?php echo home . "/edit-country/?pid=" . $pv->id; ?>">Edit</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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