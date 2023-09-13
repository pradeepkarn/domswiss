<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
?>
<div id="layoutSidenav">
  <?php import("apps/view/inc/sidebar.php"); ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">Payment</h1>
        <ol class="breadcrumb mb-4 mypop">
          <li class="breadcrumb-item active">Payment</li>
          <?php
          ?>
        </ol>



        <div class="table-responsive">
          <table class="table table-bordered" style="font-size: 14px;">
            <thead class="table-light">
              <tr>
                <th width="10%" scope="col">Photo</th>
                <th width="30%" scope="col">Product</th>
                <th width="8%" scope="col">PV</th>
                <th width="8%" scope="col">RV</th>
                <!-- <th width="8%" scope="col">Direct Bonus</th> -->
                <th width="11%" scope="col">Price € (excluding tax)</th>
                <th width="10%" scope="col">Tax % </th>
                <th width="10%" scope="col">Quantity</th>

                <th width="10%" scope="col">Price (with tax)</th>
                <th width="10%" scope="col" class="text-end">Amount</th>



              </tr>
            </thead>
            <div id="res"></div>
            <?php
            $myordersObj = new Model('customer_order');
            $cart_list = $myordersObj->filter_index(array('status' => 'cart', 'user_id' => $_SESSION['user_id']));
            ?>
            <?php
            $total_amt = 0;
            $total_pv = 0;
            $total_db = 0;
            $total_gm = 0;
            foreach ($cart_list as $cv) :
              $cv = (object) $cv;
              $item = (object) getData('item', $cv->item_id);
              $phpobj = json_decode($item->jsn);
              $gm = 0;
              foreach ($phpobj->items as $pkey => $prd) {
                $prod = (object) (new Dbobjects)->showOne("select id,qty,unit from item where id = '$prd->item'");
                $gm += calculate_gram($prod, $cv->qty);
                $total_gm += $gm;
              }

              $price_with_tax = round($cv->price);
              $price_without_tax = $price_with_tax / (100 + $cv->tax) * 100;
              $amt = round(($cv->qty * $cv->price), 2);
              $total_rv = round(($cv->qty * $cv->rv), 2);
              $total_amt += $amt;
              $total_pv += round(($cv->qty * $cv->pv), 2);
              $total_db += round(($cv->qty * $cv->direct_bonus), 2);

            ?>
              <tbody>

                <tr>
                  <th scope="row"><img src="/<?php echo home; ?>/media/upload/items/<?php echo $item->image; ?>" width="80px" alt="" srcset=""></th>
                  <td><?php echo $item->name; ?></td>
                  <td><?php echo $cv->pv; ?></td>
                  <td><?php echo $cv->rv; ?></td>
                  <!-- <td><?php // echo $cv->direct_bonus; 
                            ?></td> -->
                  <td><?php echo round($price_without_tax, 2); ?></td>
                  <td><?php echo $cv->tax; ?></td>

                  <td>
                    <div class="input-group product_data mb-3" style="width: 130px;">
                      <!-- <input type="hidden" class="qty<?php //echo $cv->id; 
                                                          ?>" name="cart_id" value="<?php //echo $cv->id; 
                                                                                    ?>">
                      <input type="hidden" class="qty<?php //echo $cv->id; 
                                                      ?>" name="price" value="<?php //echo $cv->price; 
                                                                              ?>"> -->
                      <input type="hidden" class="qty-dec<?php echo $cv->id; ?>" name="item_id" value="<?php echo $cv->item_id; ?>">
                      <input type="hidden" class="qty-dec<?php echo $cv->id; ?>" name="action" value="remove">
                      <input type="hidden" class="qty-inc<?php echo $cv->id; ?>" name="item_id" value="<?php echo $cv->item_id; ?>">
                      <input type="hidden" class="qty-inc<?php echo $cv->id; ?>" name="action" value="add">
                      <button id="decrease-btn<?php echo $cv->id; ?>" class="input-group-text decrement-btn">-</button>
                      <input readonly type="text" class="form-control text-center input-qty bg-white qty<?php echo $cv->id; ?>" aria-label="Amount (to the nearest dollar)" name="qty" value="<?php echo $cv->qty; ?>">
                      <button id="increament-btn<?php echo $cv->id; ?>" class="input-group-text increment-btn">+</button>
                    </div>
                    <?php
                    pkAjax("#decrease-btn{$cv->id}", "/purchase-decrease-qty-ajax", ".qty-dec{$cv->id}", "#res");
                    pkAjax("#increament-btn{$cv->id}", "/purchase-increase-qty-ajax", ".qty-inc{$cv->id}", "#res");
                    ?>
                  </td>

                  <td><?php echo $price_with_tax; ?></td>
                  <td class="text-end"><?php echo $amt; ?></td>

                </tr>
                <tr>

                </tr>
              </tbody>
            <?php endforeach; ?>


            <tbody>
              <tr class="text-end">
                <td colspan="2"></td>
                <td colspan=""><?php echo $total_pv; ?></td>

                <td class="text-right" colspan="4"></td>
                <td colspan="1">Total Amount = </td>
                <td><?php echo $total_amt; ?> </td>
              </tr>
              <tr class="text-end">
                <td colspan="5"></td>
                <td colspan="">Weight = </td>
                <td colspan=""><?php echo $total_gm; ?> gm</td>
                <td colspan="1">Shipping Cost =</td>
                <td><?php echo $shpcost = calculate_shipping_cost(db:new Dbobjects, gram:$total_gm, ccode:USER['country_code']); ?> </td>
              </tr>
              <tr class="text-end">
              <td colspan="7"></td>
                <th>Final Amount = </th>
                <th><?php echo $total_amt+$shpcost; ?></th>
              </tr>
            </tbody>
          </table>
          <!-- </div> -->
        </div>


        <hr style="padding: 0px 20px; width: 100%; margin-left: -4px; border-top: 1px dotted rgba(0,0,0,0.6); margin-top: 15px; margin-bottom: 15px;">

        <div class="row">
          <div class="col">
            <h5 class="card-title ship_add">Shipping Address
              <span class="ms-2">Please check the shipping address before ordering!</span>
            </h5>
          </div>
        </div>

        <div class="row pt-3 mb-5">
          <div class="col-6">
            <?php
            $my_username = null;

            if (isset($_SESSION['user_id'])) {
              $userid = $_SESSION['user_id'];
              $invite = getData("pk_user", $userid);
              $my_username = $invite != false ? $invite['username'] : null;
              $my_adderess = $invite != false ? $invite['address'] : null;
              $addrs = get_my_primary_address($userid);
              $no_stripe_country = ['KE', 'GH', 'UG', 'TZ', 'NG'];
            }
            ?>
            <p class="del_add">
              Selected delivery address: <?php echo $addrs ? $addrs->name : null; ?> - <?php echo $addrs ? $addrs->country : null; ?>
            </p>
          </div>
          <!-- <div class="col-6">
    <a class="col-md-3 btn btn-info" href="/<?php echo home; ?>/profile">Add new address</a>
  </div> -->
          <hr style="padding: 0px 20px; width: 100%; margin-left: -4px; border-top: 1px dotted rgba(0,0,0,0.6); margin-top: 20px; margin-bottom: 15px;">
        </div>
        <form class="material-form" id="place-item-form" action="/<?php echo home; ?>/place-order-ajax" method="POST">
          <div class="row mb-3">
            <div class="col-2">
              <div class="nav_tabs1">
                <button <?php echo in_array($addrs->country_code, $no_stripe_country) ? "disabled" : null; ?> id="stripeBtn" type="button" class="btn tablinks2 <?php echo in_array($addrs->country_code, $no_stripe_country) ? "hide" : null; ?>" onclick="openPayment(event, 'stripes')"><i class="fab fa-stripe-s"></i> stripes</button>
                <button id="btBtn" type="button" class="btn tablinks2" onclick="openPayment(event, 'transfer')"><i class="fa-sharp fa-light fa-money-check"></i> Bank transfer (Prepay)</button>
              </div>
            </div>

            <div class="col-5">
              <div id="stripes" class="tabcontent2 <?php echo in_array($addrs->country_code, $no_stripe_country) ? "hide" : null; ?>">
                <input <?php echo in_array($addrs->country_code, $no_stripe_country) ? "disabled" : null; ?> id="stripeRadio" type="radio" name="payment_mode" value="Stripe">
                <img src="/<?php echo home; ?>/media/img/stripe.png" width="100px" alt="" srcset="">
                <p class="stripes_cl mt-2">Online payment system<br> credit cards, Sofort / Klarna (payment via invoice), EPS, and much more.</p>
              </div>
              <div id="transfer" class="tabcontent2">
                <input id="btRadio" type="radio" checked name="payment_mode" value="Bank_transfer">
                <?php
                $banklist = get_banks_by_country();
                echo count($banklist['banks']) ? "{$banklist['banks'][0]}" : null;
                ?>

                <!-- <p class="stripes_cl">Please transfer the total amount to the following account:</p>
                <p class="stripes_cl">Account holder: Swiss Pharma Trade AG</p>
                <p class="stripes_cl">Raiffeisenbank St. Gallen<br>
                  IBAN: CH98 8080 8001 4782 5195 6<br>
                  BIC: RAIFCH22XXX</p>
                <p class="stripes_cl">Please transfer the total amount of your order to our account:</p>
                <p class="stripes_cl">Account owner: Swiss Pharma Trade AG<br>
                  Raiffeisenbank St. Gallen<br>
                  IBAN: CH98 8080 8001 4782 5195 6<br>
                  BIC: RAIFCH22XXX</p> -->
              </div>

            </div>
          </div>

          <input type="hidden" name="total_amount" value="<?php echo $total_amt; ?>">
          <input type="hidden" name="total_gm" value="<?php echo $total_gm; ?>">
          <input type="hidden" name="shipping_cost" value="<?php echo $shpcost; ?>">

          <input type="hidden" name="pv" value="<?php if (isset($pv)) echo $pv ?>">
        </form>
        <div class="row mb-3">
          <div class="col-6">
            <div id="check"></div>
            <button id="placeord_btn" class="col-md-3 btn btn-info">Make Payment</button>
          </div>
          <div id="spinn" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <?php
          pkAjax_form("#placeord_btn", "#place-item-form", "#check");
          ajaxActive('#spinn');
          ajaxDeactive("#placeord_btn");
          ?>

          <p class="again_payment">IF THERE IS AN ERROR IN THE FOLLOWING WINDOW, PLEASE CLICK "MAKE PAYMENT" AGAIN.</p>
        </div>

      </div>
    </main>


    <?php import("apps/view/inc/footer-credit.php"); ?>
  </div>
</div>

<script>
  var stripeBtn = document.getElementById('stripeBtn');
  var btRadio = document.getElementById('btRadio');

  var btBtn = document.getElementById('btBtn');
  var stripesRadio = document.getElementById('stripeRadio');

  stripeBtn.addEventListener('click', () => {
    stripesRadio.checked = true;
  })
  btBtn.addEventListener('click', () => {
    btRadio.checked = true;
  })
</script>

<?php
$userObj = new Model('pk_user');
$allusers = $userObj->index();
// myprint($allusers);
$arr = null;
$arr['user_group'] = "admin";
$allusers_filtered = $userObj->filter_index($assoc_arr = $arr, $ord = 'DESC', $limit = 9999999, $change_order_by_col = "");
// myprint($allusers_filtered);

$adrObj = new Model('address');
$arr = null;
// $arr['user_id'] = 1;
// $arr['address_type'] = "primary";
// $arr['mobile'] = 39459809;
// $arr['city'] = "New Delhi";
// $adrObj->store($arr);
// $adrObj->update($id=1,$arr);
// $adrObj->destroy($id=1);


// import("apps/view/components/form.php");

import("apps/view/inc/footer.php");
