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
                            <li class="breadcrumb-item active">Qualifications</li>
                        </ol>
                        <!-- <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Primary Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                       <div class="container mt-5">
                        <div class="row mb-3">
                            <div class="col-3">
                                <div class="box-1">
                                    <p>Binary Points</p>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="font-medium">0</h4>
                                            <h6 class="font-muted">Left</h6>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="font-medium">0</h4>
                                            <h6 class="font-muted">Right</h6>
                                        </div>
                                    </div>
                                    <div class="row mt-5 mb-5">
                                        <div class="col">
                                            <img src="./img/logo-dom-swiss.svg" width="250px" alt="" srcset="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="row">
                                    <div class="box-1">
                                        <p>PV volume overview</p>
                                        <hr>
                                    <div class="col">
                                        <table id="datatablesSimple">
                                            <thead>
                                                <tr>
                                                    <th>partner</th>
                                                    <th>Last own order</th>
                                                    <th>last period</th>
                                                    <th>week 1</th>
                                                    <th>week 2</th>
                                                    <th>week 3</th>
                                                    <th>week 4</th>
                                                    <th>current period</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>partner</th>
                                                    <th>Last own order</th>
                                                    <th>last period</th>
                                                    <th>week 1</th>
                                                    <th>week 2</th>
                                                    <th>week 3</th>
                                                    <th>week 4</th>
                                                    <th>current period</th>
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
                                                    <th>0</th>
                                                    <th>0</th>
                                                    <th>0</th>
                                                    <th>0</th>
                                                    <th>0</th>
                                                    <th>0</th>
                                                    <th>0</th>
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

<div class="row mb-3">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="box-2">
            <p>Qualification for Unilevel:</p>
            <hr>
        <h4 class="font-medium">PV own</h4>
        <h4 class="font-medium">0</h4>
        <hr>
        <h4 class="font-medium">PV customer</h4>
        <h4 class="font-medium">0</h4>
        <hr>        
    </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="box-1">
            <p>cycles</p>
            <hr>
        <h4 class="font-medium">Sadly you haven't reached Cycles yet.</h4>
        <hr>
        <p class="end-cycle">End of cycle (Apr 11, 2023 00:00:00):</p>
    </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="box-2">
            <p>Qualification for Cycle Bonus</p>
            <hr>
        <h4 class="font-medium">0</h4>
        <hr>
        <div class="row">
            <div class="col-6">
                <h4 class="font-medium">0</h4>
            </div>
            <div class="col-6">
                <h4 class="font-medium">0</h4>
            </div>
            <hr>
        </div> 
    </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="box-2">
            <p>Qualification for Unilevel:</p>
            <hr>    
            <p class="end-cycle">Bonus payment of € 100,-* every decade
                (maximum of 500 pv of direct partners two levels deep (maximum 200 per partner))
                
                </p>
    </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="box-1">
            <p>DOM Key Club Bonus</p>
            <hr>    
        <p class="end-cycle">This bonus can be reached multiple times!<br> Active DOM Key Club Bonus Payout requirements:</p>
    </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="box-2">
            <p>Unilevel Matching Bonus</p>
            <hr>    
            <p class="our_bonus">Your Bonus this week so far:</p>
            <p style="font-size: 50px; font-weight: bold;">€0</p>
            <p class="end-cycle">The UNILEVEL Matching Bonus activates after reaching PLATINUM MANAGER rank.</p>
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