<nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="/<?php echo home; ?>/">DOM SWISS</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->

    <!-- <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form> -->
    <div style="float: right !important;" class="cart_items ms-auto">
        <?php
        $currency_flag = null;
        $currency_code = null;
        $currency_name = null;
        $currency_symbol = null;
        $country_name = null;
        if (authenticate()) {
            $count = isset(USER['id'])?cart_items(USER['id']):0;
            $curr = getCurrency($keyword = MY_COUNTRY);
            // myprint($curr);
            if (count($curr) > 0) {
                $currency_code = $curr['currency']['code'];
                $currency_name = $curr['currency']['name'];
                $currency_flag = $curr['flag'];
                $country_name = $curr['name'];
            } else {
                $currency_flag = null;
                $currency_code = 'CHF';
                $currency_name = 'Franc';
                $currency_symbol = 'CHF';
                $country_name = null;
            }
        } else {
            $count = 0;
        }

        ?>
        <a href="/<?php echo home; ?>/payment" class="cart_icon"><i style="font-size: large;" class="bi bi-cart-fill"><sup><?php echo $count; ?>
            </i></a>
    </div>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                    <a class="dropdown-item" href="#!">
                        <img src="data:image/png;base64,<?php echo $currency_flag; ?>" alt="<?php echo USER!=false?USER['country']:null; ?>">
                        <?php echo $country_name; ?>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="/<?php echo home; ?>/logout">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>