<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
$prmadrs = get_my_primary_address(USER['id']);
if (!$prmadrs) {
  $_SESSION['msg'][] = "Please update primary address";
  echo js_alert(msg_ssn(return: true));
  echo go_to("profile");
  return;
}
?>
<div id="layoutSidenav">
  <?php import("apps/view/inc/sidebar.php"); ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">My Products</h1>
        <ol class="breadcrumb mb-4 mypop">
          <li class="breadcrumb-item active">My Products</li>
        </ol>
        <section>
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <ul style="display:none;" class="nav nav-pills my-3">
                  <li class="nav-item me-2">
                    <a class="nav-link <?php if (!isset($_GET['catid'])) {
                                          echo 'active';
                                        } ?>" href="/<?php echo home; ?>/products">All</a>
                  </li>
                  <?php
                  $catobj = new Model('item');
                  $cats = $catobj->filter_index(['item_group' => 'category']);
                  foreach ($cats as $cv) {
                    $cv = obj($cv);
                  ?>
                    <li class="nav-item me-2">
                      <a class="nav-link <?php if (isset($_GET['catid'])) {
                                            echo $_GET['catid'] == $cv->id ? 'active' : null;
                                          } ?>" aria-current="<?php echo $cv->name; ?>" href="/<?php echo home; ?>/products/?catid=<?php echo $cv->id; ?>"><?php echo $cv->name; ?></a>
                    </li>
                  <?php   }
                  ?>


                </ul>
              </div>
              <?php
              $curr = getCurrency($keyword = MY_COUNTRY);
              if (count($curr) > 0) {
                $currency_code = $curr['currency']['code'];
                $currency_name = $curr['currency']['name'];
                $currency_flag = $curr['flag'];
              } else {
                $currency_flag = null;
                $currency_code = 'CHF';
                $currency_name = 'Franc';
                $currency_symbol = 'CHF';
              }
              // return;

              $userObj = new Model('item');

              if (isset($_GET['catid'])) {
                $product_list  = $userObj->filter_index(array('is_active' => true, 'item_group' => 'package', 'parent_id' => $_GET['catid']));
              } else {
                $product_list = $userObj->filter_index(array('is_active' => true, 'item_group' => 'package'));
              }
              ?>
              <?php

              foreach ($product_list as $pv) {
                $pv = (object) $pv;
                $itemObj = json_decode($pv->jsn);
                $cntrs = !isset($itemObj->countries)?[]:$itemObj->countries;
                // myprint($cntrs);

                // echo MY_COUNTRY;
                // Show in my country only
                $check_country = true;
                $country_info = in_array(MY_COUNTRY, $cntrs);
                if (!is_superuser()) {
                  $check_country = in_array(MY_COUNTRY, $cntrs);
                }
                if ($check_country == true) :
                  $prices = [];
                  $qtys = [];
                  $prods = isset($itemObj->items)?$itemObj->items:[];
                  // myprint($prods);
                  foreach ($prods as $it) {
                    $pr = obj(getData('item', $it->item));
                    $cntry = new Model('countries');
                    $cntr = $cntry->filter_index(['code' => MY_COUNTRY]);
                    if (count($cntr) > 0) {
                      $cn = obj($cntr[0]);
                      $current_cntry = MY_COUNTRY;
                      $tax = $pr->suppliment ?  $cn->min_tax : $cn->max_tax;
                    } else {
                      $current_cntry = 'CH';
                      $tax = 0;
                    }
                    $prices[] = round(((($it->net_price * $tax / 100) + $it->net_price) * $it->qty), 2);
                    $qtys[] = $it->qty;
                  }
                  $price = array_sum($prices);
                  $qty = array_sum($qtys);
              ?>
                  <div class="col-md-3">
                    <form id="select-prod-form<?php echo $pv->id; ?>" action="/<?php echo home; ?>/add-to-cart-ajax" method="POST">
                      <div class="card product-card mb-4">
                        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                          <img style="height: 200px; width:100%; object-fit:cover;" src="/<?php echo home; ?>/media/upload/items/<?php echo $pv->image; ?>" class="img-fluid w-100" alt="" srcset="">
                        </div>
                        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                          <h6 class="text-truncate mb-3 prod_name"><?php echo $pv->name; ?></h6>
                          <div class="d-flex justify-content-center">
                            <h6><?php echo "Euros"; ?> <?php echo $price; ?></h6>
                          </div>
                          <!-- <input type="hidden" value="<?php // echo $pv->pv; 
                                                            ?>" scope="any" min="1" name="pv" class="form-control my-2">
                          <input type="hidden" value="1" scope="any" min="1" name="qty" class="form-control my-2"> -->
                          <input type="hidden" value="<?php echo $pv->id; ?>" name="item_id" class="form-control my-2">
                          <input type="hidden" value="add" name="action" class="form-control my-2">
                          <!-- <input type="hidden" value="<?php // echo $price; 
                                                            ?>" name="price" class="form-control my-2"> -->
                          <div class="text-white <?php echo $pv->item_group == 'product' ? 'bg-success' : 'bg-primary'; ?>">
                            <?php echo strtoupper($pv->item_group); ?> <br>
                            <?php echo $qty; ?> <?php echo $qty > 1 ? 'Items' : 'Item'; ?>
                          </div>
                        </div>
                        <div class="card-footer">
                          Package information <i id="<?php echo "product-info-btn{$pv->id}"; ?>" data-bs-toggle="modal" data-bs-target="#prodInfo" class="pk-pointer text-start fas fa-info-circle"></i>
                          <input type="hidden" name="product_info" value="<?php echo $pv->id; ?>" class="<?php echo "product-info-id{$pv->id}"; ?>">
                          <?php pkAjax("#product-info-btn{$pv->id}", "/product-info-ajax", ".product-info-id{$pv->id}",  "#info-modal-div"); ?>
                        </div>
                        <div class="card-footer d-flex justify-content-center bg-light border">

                          <?php
                          if ($country_info == false) { ?>
                            <button id="select-prod-btn<?php echo $pv->id; ?>" disabled class="btn footer_btn btn-sm text-dark p-0">
                              <span style="color: black;">Not available in your country</span>
                            </button>
                          <?php  } else { ?>
                            <button id="select-prod-btn<?php echo $pv->id; ?>" class="btn footer_btn btn-sm text-dark p-0">
                              Add To Cart
                            </button>
                          <?php }
                          ?>

                        </div>
                      </div>
                    </form>
                  </div>
                  <?php pkAjax_form("#select-prod-btn{$pv->id}", "#select-prod-form{$pv->id}", "#inv"); ?>
              <?php
                endif;
                // Show in my country only end
                $check_country == false;
              }
              ?>
              <div id="inv"></div>

            </div>
          </div>

        </section>

      </div>



    </main>



    <!-- Modal -->
    <div class="modal fade" id="prodInfo" tabindex="-1" aria-labelledby="prodInfoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="prodInfoLabel">Product Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="info-modal-div"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <?php import("apps/view/inc/footer-credit.php"); ?>
  </div>
</div>



<?php

import("apps/view/inc/footer.php");
