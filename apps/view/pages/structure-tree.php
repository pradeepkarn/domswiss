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
                            <li class="breadcrumb-item active">structure tree</li>
                        </ol>

                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                        <div class="card-body">
                                            <h4 class="card-title text-center">structure tree</h4>
                                            <hr>
                                            <!-- <i>There are no registered partners</i> -->
                                            <ul>
                                            <?php
                                            if (authenticate()==true) {
                                                $userObj = new Model('pk_user');
                                        
                                        $arr=null;
                                        $arr['ref'] = $_SESSION['user_id'];
                                        $partner = $userObj->filter_index($assoc_arr=$arr,$ord = 'DESC',$limit = 9999999,                                         $change_order_by_col= "");
                                        // myprint($partner);
                                        // myprint($_SESSION['user_id']);
                                            }

                                        foreach ($partner as $value) {
                                            ?>
                                            <li><?php echo $value['username']; ?></li>
                                            <?php
                                        }

                                        ?>   
                                            </ul>
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