<?php

class Cart_ctrl
{
    public function add_or_remove($action = 'add')
    {
        $req = obj($_POST);
        $userid = USER['id'];
        $pv = obj(getData('item', $req->item_id));
        $itemObj = json_decode($pv->jsn);
        $cntrs = $itemObj->countries;
        $cart = new Dbobjects;
        $pv = obj(getData('item', $req->item_id));
        $sql = "select id, qty, item_id from customer_order where user_id=$userid";
        $oldMngr = $cart->show($sql);
        if ($action == 'add') {
            if (count($oldMngr) > 0) {
                foreach ($oldMngr as $check) {
                    $prd = getData('item', $check['item_id']);
                    if ($prd['product_id'] == '8' && ($pv->product_id == 8 || $pv->product_id == 9 || $pv->product_id == 10)) {
                        echo js_alert('You can not downgrade from gold/reactivate same position');
                        return false;
                    } else if ($prd['product_id'] == '9' && ($pv->product_id == 9 || $pv->product_id == 10)) {
                        echo js_alert('You can not downgrade from silver/reactivate same position');
                        return false;
                    } else if ($prd['product_id'] == '10' && $pv->product_id == 10) {
                        echo js_alert('You can not reactivate same position');
                        return false;
                    }
                }
            }
        }

        if (in_array(MY_COUNTRY, $cntrs)) :
            $prices = [];
            $qtys = [];
            $prods = $itemObj->items;
            foreach ($itemObj->items as $it) {
                $pr = obj(getData('item', $it->item));
                // isner iten name in php object
                $it->item_name = $pr->name;
                $it->item_details = $pr->details;
                $it->product_id = $pr->product_id;
                $cntry = new Model('countries');
                $cntr = $cntry->filter_index(['code' => MY_COUNTRY]);
                $tax = 0;
                if (count($cntr) > 0) {
                    $cn = obj($cntr[0]);
                    $tax = $pr->suppliment == 1 ? $cn->max_tax : $cn->min_tax;
                }
                $it->min_tax = $cn->min_tax;
                $it->max_tax = $cn->max_tax;
                $it->is_suppliment = $pr->suppliment == 1 ? true : false;
                $it->tax_applied = $pr->suppliment == 1 ? 'max_tax' : 'min_tax';
                $it->tax_value = $tax;
                $it->my_country = MY_COUNTRY;
                $prices[] = round(((($it->net_price * ($tax / 100)) + $it->net_price) * $it->qty), 2);
                $qtys[] = $it->qty;
            }
            $price = array_sum($prices);
            // $qty = array_sum($qtys);
            $mypv = $pv->pv;
            $myrv = $pv->rv;
            $mybonus = $pv->direct_bonus;
            $cartarr['item_id'] = $req->item_id;

            $update_jsn = json_encode($itemObj);
            // $cart = new Dbobjects;
            $sql = "select qty from customer_order where item_id = $req->item_id and status='cart' and user_id=$userid";
            $old = $cart->show($sql);
            if ($action == 'add') :
                if (count($old) > 0) {
                    $sql = "update customer_order set qty = (qty+1), jsn = '$update_jsn' where item_id = $req->item_id and status='cart' and user_id=$userid";
                    $pdo = $cart->dbpdo();
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                } else {
                    $sql = "INSERT INTO customer_order 
                    (item_id, user_id, qty, price, tax, pv, rv, direct_bonus, status, jsn) VALUES 
                    ($req->item_id,  $userid, 1, $price, $tax, $mypv, $myrv, $mybonus, 'cart', '$update_jsn')";
                    // $cart = new Dbobjects;
                    $pdo = $cart->dbpdo();
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                }
            elseif ($action == 'remove') :
                // $cart = new Dbobjects;
                if (count($old) > 0) {
                    if ($old[0]['qty'] == 1) {
                        $sqldel = "delete from customer_order where item_id = $req->item_id and status='cart' and user_id=$userid";
                        $pdo = $cart->dbpdo();
                        $stmt = $pdo->prepare($sqldel);
                        $stmt->execute();
                    } else {
                        $sqlup = "update customer_order set qty = (qty-1), jsn = '$update_jsn' where item_id = $req->item_id and status='cart' and user_id=$userid";
                        $pdo = $cart->dbpdo();
                        $stmt = $pdo->prepare($sqlup);
                        // echo $sqlup;
                        $stmt->execute();
                    }
                }
            endif;
        // echo "Price: ".$price.", qty:". $qty.", pv: ".$mypv;
        else :
            return false;
        endif;
    }
    // for invoice freezing perpose only
    function update_invoice($payment_id, $update_amt = false)
    {

        $ord = [];
        $db = new Dbobjects;
        $pmtObj = ($db)->show("select * from payment where payment.id = $payment_id");
        if (count($pmtObj) > 0) {
            $ord['payment'] = $pmtObj[0];
            $deliver_adrs = get_my_primary_address($pmtObj[0]['user_id']);
            $cartObj = ($db)->show("select * from customer_order where customer_order.payment_id = $payment_id");
            if (count($cartObj) > 0) {
                $prices = [];
                foreach ($cartObj as $cart) {
                    $itemObj = [];
                    $pv = obj(getData('item', $cart['item_id']));
                    $itemObj = json_decode($pv->jsn);
                    if ($cart['jsn'] == null || $cart['jsn'] == '') {
                        foreach ($itemObj->items as $it) {
                            $pr = obj(getData('item', $it->item));
                            // isner iten name in php object
                            $it->item_name = $pr->name;
                            $it->item_details = $pr->details;
                            $it->product_id = $pr->product_id;
                            $cntry = new Model('countries');
                            $cntr = $cntry->filter_index(['code' => $deliver_adrs->country_code]);
                            $tax = 0;
                            if (count($cntr) > 0) {
                                $cn = obj($cntr[0]);
                                // $current_cntry = MY_COUNTRY;
                                $tax = $pr->suppliment == 1 ? $cn->max_tax : $cn->min_tax;
                            }
                            $it->min_tax = $cn->min_tax;
                            $it->max_tax = $cn->max_tax;
                            $it->is_suppliment = $pr->suppliment == 1 ? true : false;
                            $it->tax_applied = $pr->suppliment == 1 ? 'max_tax' : 'min_tax';
                            $it->tax_value = $tax;
                            $it->my_country = $deliver_adrs->country_code;
                            $prices[] = round(((($it->net_price * ($tax / 100)) + $it->net_price) * $it->qty), 2);
                            $qtys[] = $it->qty;
                        }
                        $amt = array_sum($prices);
                        $update_jsn = json_encode($itemObj);
                        try {
                            if ($update_amt == true) {
                                ($db)->show("update customer_order set price=$amt, jsn = '$update_jsn' where customer_order.id = {$cart['id']}");
                            } else {
                                ($db)->show("update customer_order set jsn = '$update_jsn' where customer_order.id = {$cart['id']}");
                            }
                            task_log($msg = "cart id: {$cart['id']}, updated in customer_order table");
                        } catch (PDOException $e) {
                            task_log($msg = "cart id: {$cart['id']}, not updated in customer_order table");
                        }
                    } else {
                        task_log($msg = "cart id: {$cart['id']}, is already updated in customer_order table");
                    }
                }
            }
            if ($update_amt == true) {
                $tamt = $db->show("select SUM(price*qty) as total_amt from customer_order where payment_id = $payment_id")[0]['total_amt'];
                ($db)->show("update payment set amount = $tamt where payment.id = {$payment_id}");
            }
        }
    }
}
