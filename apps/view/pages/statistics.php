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
                                                <p>€0.00</p>
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
                                            <table id="datatablesSimple">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>product</th>
                                                    <th>order quantity</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>product</th>
                                                    <th>order quantity</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                
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
                                            if (authenticate()==true) {
                                                $userObj = new Model('pk_user');
                                        
                                        $arr=null;
                                        $arr['ref'] = $_SESSION['user_id'];
                                        $partner = $userObj->filter_index($assoc_arr=$arr,$ord = 'DESC',$limit = 9999999,                                         $change_order_by_col= "");
                                        }

                                        foreach ($partner as $value) {
                                            ?>
                                            <tr>
                                            <th><?php echo $value['username']; ?></th>
                                            <th>1</th>
                                            <th>€0.00</th>
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
                            </div>
                        </div>
                       
                </main>
                <?php import("apps/view/inc/footer-credit.php");?>
            </div>
</div>
<?php 
import("apps/view/inc/footer.php");
?>