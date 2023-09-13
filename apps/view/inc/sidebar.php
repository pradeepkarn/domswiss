<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="/<?php echo home; ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <?php
                if (authenticate()) {
                ?>
                    <a class="nav-link" href="/<?php echo home; ?>/commissions">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Commissions
                    </a>
                    <a class="nav-link" href="/<?php echo home; ?>/my-commissions/?page=1">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        My Ring Commissions
                    </a>
                <?php
                }
                if (is_superuser()) {
                ?>
                    <a class="nav-link" href="/<?php echo home; ?>/all-users/?page=1">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        All Users
                    </a>
                    <a class="nav-link" href="/<?php echo home; ?>/all-commissions/?page=1">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        All Ring Commissions
                    </a>
                    <a class="nav-link" href="/<?php echo home; ?>/all-orders/">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        All Orders
                    </a>
                    <a class="nav-link" href="/<?php echo home; ?>/withdrawal-requests/?remark=requested">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Withdrawal Requests
                    </a>
                    <a class="nav-link" href="/<?php echo home; ?>/withdrawal-requests/?remark=confirmed">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Confirmed Withdrawal
                    </a>
                    <a class="nav-link" href="/<?php echo home; ?>/withdrawal-requests/?remark=cancelled">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Cancelled Withdrawal
                    </a>
                <?php
                }
                ?>

                <div class="sb-sidenav-menu-heading">Interface</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Network
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <!-- <a class="nav-link" href="/<?php // echo home; 
                                                        ?>/qualifications">Qualifications</a> -->

                        <?php
                        if (authenticate()) {
                        ?>
                            <a class="nav-link" href="/<?php echo home; ?>/genology">Genology</a>
                            <a class="nav-link" href="/<?php echo home; ?>/structure-tree">Structure Tree</a>
                        <?php
                        }
                        ?>
                        <a class="nav-link" href="/<?php echo home; ?>/invite">
                            New Partner</a>
                        <a class="nav-link" href="/<?php echo home; ?>/partners">
                            Your Partners</a>
                        <a class="nav-link" href="/<?php echo home; ?>/customers">
                            Your Customers</a>
                        <a class="nav-link" href="/<?php echo home; ?>/statistics">
                            Statistics</a>
                    </nav>
                </div>
                <?php
                if (authenticate()) {
                ?>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                        Reports
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                <?php
                }
                ?>
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <?php
                        if (authenticate()) {
                        ?>
                            <!-- <a class="nav-link" href="/<?php // echo home; 
                                                            ?>/earnings">Earnings</a> -->
                        <?php
                        }
                        ?>
                        <?php
                        if (authenticate()) {
                        ?>
                            <a class="nav-link" href="/<?php echo home; ?>/orders">My Orders</a>
                        <?php
                        }
                        ?>
                        <?php
                        if (authenticate()) {
                        ?>
                            <a class="nav-link" href="/<?php echo home; ?>/downline">Downline Status</a>
                        <?php
                        }
                        ?>
                    </nav>
                </div>
                <div class="sb-sidenav-menu-heading">Addons</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages4" aria-expanded="false" aria-controls="collapsePages4">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Products
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages4" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/<?php echo home; ?>/shop">Product Order</a>
                        <?php
                        $addrs = new Model('address');
                        $addrs = $addrs->filter_index(['user_id' => $_SESSION['user_id'], 'address_type' => 'primary']);
                        $is_valid_primary = count($addrs);
                        if ($is_valid_primary == 1) {
                        ?>
                            <?php
                            if (authenticate()) {
                            ?>
                                <a class="nav-link" href="/<?php echo home; ?>/products">All Products</a>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                    </nav>
                </div>
                <?php
                if (is_superuser()) {
                ?>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#countries-management" aria-expanded="false" aria-controls="collapsePages4">
                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                        Manage Countries
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="countries-management" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">

                            <a class="nav-link" href="/<?php echo home; ?>/list-all-countries">All countries</a>

                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#product-management" aria-expanded="false" aria-controls="collapsePages4">
                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                        Manage Products
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse show" id="product-management" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <?php
                            if (is_superuser()) {
                            ?>
                                <a class="nav-link" href="/<?php echo home; ?>/list-all-categories">All Categories</a>
                                <a class="nav-link" href="/<?php echo home; ?>/list-all-products">All Products</a>
                                <a class="nav-link" href="/<?php echo home; ?>/create-product">Add Product</a>
                                <a class="nav-link" href="/<?php echo home; ?>/list-all-packages">All Packages</a>
                                <a class="nav-link" href="/<?php echo home; ?>/create-package">Add Package</a>
                            <?php
                            }
                            ?>
                        </nav>
                    </div>
                <?php
                }
                ?>


                <?php
                if (authenticate()) {
                ?>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages1" aria-expanded="false" aria-controls="collapsePages1">
                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                        Finance
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                <?php
                }
                ?>
                <div class="collapse" id="collapsePages1" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <?php
                        if (is_superuser()) {
                        ?>
                            <!-- <a class="nav-link" href="/<?php // echo home; 
                                                            ?>/credits">Credit Sum</a> -->
                            <!-- <a class="nav-link" href="/<?php // echo home; 
                                                            ?>/all-users">All Users</a> -->
                        <?php
                        }
                        ?>
                    </nav>
                </div>
                <?php
                if (authenticate()) {
                ?>
                    <a class="nav-link" href="/<?php echo home; ?>/tickets">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Support Tickets
                    </a>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages2" aria-expanded="false" aria-controls="collapsePages2">
                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                        Ideas
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                <?php
                }
                ?>

                <div class="collapse" id="collapsePages2" aria-labelledby="headingFour" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/<?php echo home; ?>/profile">My Profile</a>
                    </nav>
                </div>
                <!-- <div class="collapse" id="collapsePages2" aria-labelledby="headingFour" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="/<?php echo home; ?>/shopping-link">Shopping Link</a>
                                </nav>
                            </div> -->
                <div class="collapse" id="collapsePages2" aria-labelledby="headingFour" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <?php
                        if (is_superuser()) {
                        ?>
                            <a class="nav-link" href="/<?php echo home; ?>/payment">Payment</a>
                        <?php
                        }
                        ?>
                    </nav>
                </div>
                <!-- <div class="collapse" id="collapsePages2" aria-labelledby="headingFour" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="/<?php echo home; ?>/checkout">Checkout</a>
                                </nav>
                            </div> -->
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?php echo USER ? USER['user_group'] : null; ?>
        </div>
    </nav>
</div>