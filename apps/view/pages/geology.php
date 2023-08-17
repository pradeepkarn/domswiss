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
                            <li class="breadcrumb-item active">Geology</li>
                        </ol>

                        <div class="container">
                            <div class="row mb-4">
                                <div class="col-7 ps-5 d-flex justify-content-center mb-5">
                                    <?php 
$userObj = new Model('pk_user');
$allusers = $userObj->index();
// myprint($allusers);
$arr=null;
$arr['user_group'] = "admin";
// myprint($arr);
                                    ?>
                                    <!-- <div class="geo_tree1">
                                    <img src="/<?php echo home; ?>/media/img/user-blank.png" alt="" srcset="" width="80px" height="80px">
                                        <p class="pt-3"><?php echo $arr["user_group"]; ?></p>
                                    </div> -->
                                </div>
                                <div class="col-lg-12 ps-5">
                                <?php
                                    if (authenticate()==true) {
                                        $userObj = new Model('pk_user');
                                        $arr=null;
                                        $arr['ref'] = $_SESSION['user_id'];
                                        $partner = $userObj->filter_index($assoc_arr=$arr,$ord = 'DESC',$limit = 8,                                         $change_order_by_col= "");
                                    }
                                 ?>
                                 <?php
                                 foreach ($partner as $value) {
                                    ?>
                                    <div class="geo_tree">
                                        <img src="/<?php echo home; ?>/media/img/user-blank.png" alt="" srcset="" width="80px" height="80px">
                                    <p class="pt-3"><?php echo $value['username']; ?></p>
                                    </div>
                                    <?php 
                                    }
                                    ?>
                                
                                </div>
                                </div>
                            </div>

                            <!-- <div class="tree">
	<ul>
		<li>
			<a href="#">Parent</a>
			<ul>
				<li>
					<a href="#">Child</a>
					<ul>
						<li>
							<a href="#">Grand Child</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="#">Child</a>
					<ul>
						<li><a href="#">Grand Child</a></li>
						<li>
							<a href="#">Grand Child</a>
							<ul>
								<li>
									<a href="#">Great Grand Child</a>
								</li>
								<li>
									<a href="#">Great Grand Child</a>
								</li>
								<li>
									<a href="#">Great Grand Child</a>
								</li>
							</ul>
						</li>
						<li><a href="#">Grand Child</a></li>
					</ul>
				</li>
			</ul>
		</li>
	</ul>
</div> -->
                        </div>
                       
                </main>
                <?php import("apps/view/inc/footer-credit.php");?>
            </div>
</div>
<?php 
import("apps/view/inc/footer.php");
?>