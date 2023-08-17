<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
?>
<div id="layoutSidenav">
  <?php import("apps/view/inc/sidebar.php"); ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">Shopping Link</h1>
        <ol class="breadcrumb mb-4 mypop">
          <li class="breadcrumb-item active">Shopping Link</li>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add Product
          </button>
        </ol>

        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Products</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="/<?php echo home; ?>/purchase-add-item-ajax" id="select-item-form" class="material-form">
                  <div class="form-group row">
                    <div class="col-md">

                      <?php
                      $userObj = new Model('item');
                      $product_list = $userObj->filter_index(array('is_active' => true));

                      ?>
                      <label for="">Select Product Item</label>
                      <select class="form-select mb-3 my-2" name="item_id" aria-label="Default select example">
                        <?php
                        foreach ($product_list as $pv) {
                          $pv = (object) $pv;
                        ?>
                          <option value="<?php echo $pv->id; ?>"><?php echo $pv->name; ?></option>
                        <?php
                        }
                        ?>
                      </select>
                      <label for="">Enter Price</label>
                      <input type="number" scope="any" min="0" name="price" class="form-control my-2">
                      <label for="">Enter Quantity</label>
                      <input type="number" scope="any" min="1" name="qty" class="form-control my-2">
                      <?php
                      $userObj = new Model('pk_user');
                      $arr=null;
                      $arr['ref'] = $_SESSION['user_id'];
                      $user_list = $userObj->filter_index($assoc_arr=$arr,$ord = 'DESC',$limit = 999,      $change_order_by_col= "");

                      ?>
                      <label for="">Select User Name</label>
                      <select class="form-select mb-3 my-2" name="userid" aria-label="Default select example">
                        <?php
                        foreach ($user_list as $uv) {
                          
                        ?>
                          <option value="<?php echo $uv['id']; ?>"><?php echo $uv['username']; ?></option>
                        <?php
                        }
                        ?>
                      </select>
                      <div class="d-grid">
                      <button id="select-item-btn" class="btn btn-success my-2">Add</button>
                      </div>
                    </div>
                    
                  </div>
                </form>
                <div id="inv"></div>
                <?php pkAjax_form("#select-item-btn", "#select-item-form", "#inv"); ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>
            </div>
          </div>
        </div>


<div class="table-responsive">
        <table class="table table-bordered" style="font-size: 14px;">
          <thead class="table-light">
            <tr>
              <th width="10%" scope="col">Photo</th>
              <th width="30%" scope="col">Product</th>
              <th width="8%" scope="col">PV</th>
              <th width="11%" scope="col">Price € (excluding tax)</th>
              <th width="10%" scope="col">Crowd</th>
              <th width="11%" scope="col">Cost</th>
              <th width="10%" scope="col">tax €</th>
              <th width="10%" scope="col">Price € (with tax)</th>
            </tr>
          </thead>
          <div id="res"></div>
          <?php
            $myordersObj = new Model('customer_order');
            $cart_list = $myordersObj->filter_index(array('status' => 'cart','user_id'=>$_SESSION['user_id']));
            ?>
             <?php
             $total_amt = 0;
            foreach ($cart_list as $cv):
              $cv = (object) $cv;
              $item = (object) getData('item',$cv->item_id);
              $cost = round(($cv->qty * $cv->price),2);
              $amt = round(($cv->qty * $cv->price) + ($cv->price*$cv->tax*0.01),2);
              $total_amt += $amt;
              
              // myprint($pv);



            ?>
          <tbody>
           
            <tr>
              <th scope="row"><img src="/<?php echo home; ?>/media/upload/<?php echo $item->image; ?>" width="80px" alt="" srcset=""></th>
              <td><?php echo $item->name; ?></td>
              <td>0</td>
              <td><?php echo $cv->price; ?></td>
              <td>
              <div class="input-group product_data mb-3" style="width: 130px;">
              <input type="hidden" class="qty<?php echo $cv->id; ?>" name="cart_id" value="<?php echo $cv->id; ?>">
              <input type="hidden" class="qty<?php echo $cv->id; ?>" name="price" value="<?php echo $cv->price; ?>">
              <button id="decrease-btn<?php echo $cv->id; ?>" class="input-group-text decrement-btn">-</button>
              <input type="text" class="form-control text-center input-qty bg-white qty<?php echo $cv->id; ?>" aria-label="Amount (to the nearest dollar)" name="qty" value="<?php echo $cv->qty; ?>">
              <button id="increament-btn<?php echo $cv->id; ?>" class="input-group-text increment-btn">+</button>
            </div>  
              <?php
                pkAjax("#decrease-btn{$cv->id}","/purchase-decrease-qty-ajax",".qty{$cv->id}","#res");
                pkAjax("#increament-btn{$cv->id}","/purchase-increase-qty-ajax",".qty{$cv->id}","#res");
              ?>
            </td>
              <td><?php echo $cost; ?></td>
              <td><?php echo $cv->tax; ?></td>
              <td><?php echo $amt; ?> euros</td>
              
            </tr>
            <tr>
              
            </tr>
          </tbody>
          <?php endforeach; ?>
          
        </table>
        <!-- <div class="total"> -->
          <table class="table table-bordered">
            <tbody>
              <tr>
                <td width="40%"></td>
                <td width="8%" class="text-right">0</td>
                <td width="42%">Total Cost</td>
                <td width="10%"><?php echo $total_amt; ?> euros</td>
              </tr>
            </tbody>
          </table>
        <!-- </div> -->
      </div>

     

      </div>
    </main>

    
    <?php import("apps/view/inc/footer-credit.php"); ?>
  </div>
</div>



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



