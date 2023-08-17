<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
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
              $product_list = $userObj->filter_index(array('is_active' => true, 'item_group' => 'package'));

              ?>
              <?php
              foreach ($product_list as $pv) {
                $pv = (object) $pv;
                $itemObj = json_decode($pv->jsn);
                $cntrs = $itemObj->countries;
                // Show in my country only
                if (in_array(MY_COUNTRY, $cntrs)) :
                  $prices = [];
                  $qtys = [];
                  $prods = $itemObj->items;
                  foreach ($prods as $it) {
                    $pr = obj(getData('item', $it->item));
                    $cntry = new Model('countries');
                    $cntr = $cntry->filter_index(['code' => MY_COUNTRY]);
                    if (count($cntr) > 0) {
                      $cn = obj($cntr[0]);
                      $current_cntry = MY_COUNTRY;
                      $tax = $pr->suppliment ? $cn->max_tax : $cn->min_tax;
                    } else {
                      $current_cntry = 'CH';
                      $tax = 0;
                    }
                    $prices[] = round(((($pv->net_price / $tax) + $pv->net_price) * $it->qty), 2);
                    // $prices[] = round(((($pr->min_price / $tax) + $pr->min_price) * $it->qty), 2);
                    $qtys[] = $it->qty;
                  }
                  $price = array_sum($prices);
                  $qty = array_sum($qtys);
              ?>
                  <div class="col-3">
                    <form id="select-prod-form<?php echo $pv->id; ?>" action="/<?php echo home; ?>/send-add-item-ajax" method="POST">
                      <div class="card product-card mb-4">
                        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                          <img style="height: 200px; width:100%; object-fit:cover;" src="/<?php echo home; ?>/media/upload/items/<?php echo $pv->image; ?>" class="img-fluid w-100" alt="" srcset="">
                        </div>
                        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                          <h6 class="text-truncate mb-3 prod_name"><?php echo $pv->name; ?></h6>
                          <div class="d-flex justify-content-center">
                            <h6><?php echo "Euros"; ?> <?php echo $price; ?></h6>
                          </div>
                          <input type="hidden" value="<?php echo $pv->pv; ?>" scope="any" min="1" name="pv" class="form-control my-2">
                          <input type="hidden" value="1" scope="any" min="1" name="qty" class="form-control my-2">
                          <input type="hidden" value="<?php echo $pv->id; ?>" name="item_id" class="form-control my-2">
                          <input type="hidden" value="<?php echo $price; ?>" name="price" class="form-control my-2">
                          <div class="text-white <?php echo $pv->item_group == 'product' ? 'bg-success' : 'bg-primary'; ?>">
                            <?php echo strtoupper($pv->item_group); ?> <br>
                            <?php echo $qty; ?> <?php echo $qty > 1 ? 'Items' : 'Item'; ?>
                          </div>
                        </div>
                        <div class="card-footer d-flex justify-content-center bg-light border">
                          <button id="select-prod-btn<?php echo $pv->id; ?>" class="btn footer_btn btn-sm text-dark p-0">Add To Cart</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <?php pkAjax_form("#select-prod-btn{$pv->id}", "#select-prod-form{$pv->id}", "#inv"); ?>
              <?php
                endif;
                // Show in my country only end
              }
              ?>
              <div id="inv"></div>

            </div>
          </div>

        </section>

      </div>



    </main>



    <?php import("apps/view/inc/footer-credit.php"); ?>
  </div>
</div>



<?php

import("apps/view/inc/footer.php");
