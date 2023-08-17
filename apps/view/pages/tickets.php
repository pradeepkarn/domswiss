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
                            <li class="breadcrumb-item active mycl">Credit Overview</li>
                            <li class="breadcrumb-item active"><button class="btn btn-danger">Create new tickets</button></li>
                        </ol>

                        <div class="container">
                            <div class="row mb-4">
                                <div class="col-lg-12">
                                <table id="datatablesSimple">
                                            <thead>
                                                <tr>
                                                    <th>Ticket ID</th>
                                                    <th>dates</th>
                                                    <th>Opened by</th>
                                                    <th>Subject</th>
                                                    <th>ticket type</th>
                                                    <th>status</th>
                                                    <th>actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                    <th>Ticket ID</th>
                                                    <th>dates</th>
                                                    <th>Opened by</th>
                                                    <th>Subject</th>
                                                    <th>ticket type</th>
                                                    <th>status</th>
                                                    <th>actions</th>
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