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
                    <li class="breadcrumb-item active">Checkout-form</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="my-form">
                                <?php 
                                 $userObj = new Model('pk_user');
                                 $arr=null;
                                 $arr['id'] = $_SESSION['user_id'];
                                 $user_list = $userObj->filter_index($assoc_arr=$arr,$ord = 'DESC',$limit = 999,$change_order_by_col= "");
                                 $addrs = get_my_primary_address();
                                ?>
                                <form class="material-form" id="place-item-form" action="/<?php echo home; ?>/place-order-ajax" method="POST">
                                <?php
                        foreach ($user_list as $uv) {
                            $uv = (object) $uv;
                        ?>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Name of Person</label>
                                            <input class="form-control my-2 valid" type="text" required="" placeholder="Name of Person" name="name" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $uv->username; ?>" aria-required="true" aria-invalid="false">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Zipcode</label>
                                            <input class="form-control my-2 valid" type="text" required="" placeholder="Zipcode" name="zipcode" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $addrs?$addrs->zipcode:null; ?>" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">ISD Code</label>
                                            <input class="form-control my-2 valid" type="text"  required="" placeholder="ISD Code" name="isd_code" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $addrs?$addrs->isd_code:null; ?>" aria-required="true" aria-invalid="false">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Mobile No.</label>
                                            <input class="form-control my-2 valid" type="text" required="" placeholder="Mobile No." name="mobile" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $addrs?$addrs->mobile:null; ?>" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">Address</label>
                                            <input class="form-control my-2 valid" type="text" required="" placeholder="Address" name="address" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $addrs?$addrs->address_name:null; ?>" aria-required="true" aria-invalid="false">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">City</label>
                                            <input class="form-control my-2 valid" type="text" required="" placeholder="City" name="city" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $addrs?$addrs->city:null; ?>" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <div class="col-lg-6">
                                            <label for="">State</label>
                                            <input class="form-control my-2 valid" type="text" required="" placeholder="State" name="state" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $addrs?$addrs->state:null; ?>" aria-required="true" aria-invalid="false">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="">Country</label>
                                            <input class="form-control my-2 valid" type="text" required="" placeholder="Country" name="country" data-validation-required-message="Das ist ein Pflichtfeld" value="<?php echo $addrs?$addrs->country:null; ?>" aria-required="true" aria-invalid="false">
                                        </div>
                                    </div>
                                    <?php 
                        }
                                    ?>
                                    
                                    <?php
            $myordersObj = new Model('customer_order');
            $cart_list = $myordersObj->filter_index(array('status' => 'cart'));
            ?>
             <?php
             $total_amt = 0;
            foreach ($cart_list as $cv):
              $cv = (object) $cv;
              $item = (object) getData('item',$cv->item_id);
              $cost = round(($cv->qty * $cv->price),2);
              $amt = round(($cv->qty * $cv->price) + ($cv->price*$cv->tax*0.01),2);
              $total_amt += $amt;
              $pv = (7 / 100) * $total_amt;
              $pv1 = (5 / 100) * $total_amt;
              $pv2 = (1 / 100) * $total_amt;
            //   myprint($pv);



            if (authenticate()==true) {
                $userObj = new Model('payment');
            
            $arr=null;
            $arr['user_id'] = $_SESSION['user_id'];
            $partner = $userObj->filter_index($assoc_arr=$arr,$ord = 'DESC',$limit = 999,              $change_order_by_col= "");
            }
            // myprint($partner);

            if (count($partner)==0) {
                // echo $pv = (7 / 100) * $total_amt;
            }
            // }elseif(count($partner)==1){
            //     echo $pv = (5 / 100) * $total_amt;
            // }elseif(count($partner)==2){
            //     echo $pv = (3 / 100) * $total_amt;
            // }else{
            //     echo $pv = (1 / 100) * $total_amt;
            // }
            
            
            ?>



            <?php endforeach; ?>

            <input type="hidden" name="total_amount" value="<?php echo $total_amt; ?>">
                                    <h5>Total Price - <?php echo $total_amt; ?> euros</h5>
                                    <h5>Payment Mode - COD</h5>
                                    <input type="hidden" name="payment_mode" value="Stripe">
                                    <input type="hidden" name="pv" value="<?php if(isset($pv))echo $pv ?>">
                                    
                                
                            </div>
                        </div>

                
            </form>
                <div id="check"></div>     
                <div class="row">
                    <div class="col-3">
                    <button id="placeord_btn" class="btn btn-light btn-block" name="upload_product_btn" type="button">Checkout</button>
                    
                    </div>
                </div>

                    </div>
                </div>
            </div>

            

        </main>
        <?php import("apps/view/inc/footer-credit.php"); ?>
    </div>
</div>
<?php
import("apps/view/inc/footer.php");
?>