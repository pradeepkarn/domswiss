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
                            <li class="breadcrumb-item active">Your Customers</li>
                        </ol>

                        <div class="container">
                            <div class="row mb-4">
                                <div class="col-lg-12">
                                        <table id="datatablesSimple">
                                            <thead>
                                                <tr>
                                                    <th>Customer ID</th>
                                                    <th>Mentor ID</th>
                                                    <th>User name</th>
                                                    <th>Surname</th>
                                                    <th>address</th>
                                                    <th>status</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                <th>Customer ID</th>
                                                    <th>Mentor ID</th>
                                                    <th>User name</th>
                                                    <th>Surname</th>
                                                    <th>address</th>
                                                    <th>status</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
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