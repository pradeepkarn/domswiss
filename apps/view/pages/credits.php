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
                    <li class="breadcrumb-item active">Credit Overview</li>
                </ol>

                <div class="container">
                    <style>
                        
                    </style>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="shadow-sm card h-100 px-3 py-2">
                                <p>All commissions since starting</p>
                                <?php
                                $db = new Dbobjects;
                                $sql = "select SUM(amt) as total_amt from credits where status='lifetime'";
                                $cmsn = $db->show($sql);
                                $tm = $cmsn[0]['total_amt']?$cmsn[0]['total_amt']:0;
                                echo $tm;
                                ?>
                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="shadow-sm card h-100 px-3 py-2">
                                <p>All commissions are paid out</p>
                                <?php
                                $sql = "select SUM(amt) as total_amt from credits where status = 'paid'";
                                $cmsn = $db->show($sql);
                                $tm_paid = $cmsn[0]['total_amt']?$cmsn[0]['total_amt']:0;
                                echo $tm_paid;
                                ?>
                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="shadow-sm card h-100 px-3 py-2">
                               <p> Free money to be paid out</p>
                                <?php
                                // $sql = "select SUM(amt) as total_amt from credits where status = 'unpaid'";
                                // $cmsn = $db->show($sql);
                                // $tm = $cmsn[0]['total_amt']?$cmsn[0]['total_amt']:0;
                                echo $tm-$tm_paid;
                                ?>
                                
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