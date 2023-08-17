<?php

use PHPMailer\PHPMailer\PHPMailer;

class Order_ctrl
{
    public function place()
    {
        $req = (object) ($_POST);
        if ($req) {
            $arr = null;
            $placeOrder = new Model('payment');
            $addrs = get_my_primary_address(USER['id']);
            if ($addrs == false) {
                $_SESSION['msg'][] = 'No primary address found';
                return false;
            }
            if (!isset($_POST['payment_mode'])) {
                $_SESSION['msg'][] = 'Please select payment mode';
                return false;
            }
            if ($_POST['payment_mode'] == '') {
                echo js_alert('Please select payment mode');
                return;
            }
            $dbobj = new Dbobjects;
            $con = $dbobj->dbpdo();
            $con->beginTransaction();
            $sql = "select SUM(price*qty) as total_amt, SUM(pv*qty) as total_pv, SUM(rv*qty) as total_rv, SUM(direct_bonus*qty) as total_db from customer_order where status = 'cart' and payment_id='0' and user_id = {$_SESSION['user_id']}";
            if (count($dbobj->show($sql))==0) {
                $_SESSION['msg'][] = 'Your cart is empty!';
                return;
            }
            try {
                $total_amt = $dbobj->show($sql)[0]['total_amt'];
                $total_pv = $dbobj->show($sql)[0]['total_pv'];
                $total_rv = $dbobj->show($sql)[0]['total_rv'];
                $total_db = $dbobj->show($sql)[0]['total_db'];
                $arr['amount'] = $total_amt;
                $arr['pv'] = $total_pv;
                $arr['rv'] = $total_rv;
                $arr['direct_bonus'] = $total_db;
            } catch (PDOException $e) {
                return false;
            }
            if (
                $addrs->name == '' ||
                $addrs->mobile == '' ||
                $addrs->address_name == '' ||
                $addrs->city == '' ||
                $addrs->state == '' ||
                $addrs->country == '' ||
                $addrs->zipcode == '' ||
                $addrs->isd_code == ''
            ) {
                $_SESSION['msg'][] = 'Please check your primary address and make sure you have entered all the details';
                return false;
            }
            // $dbobj->tableName = "payment";
            $arr['user_id'] = $_SESSION['user_id'];
            $arr['name'] = $addrs->name;
            $arr['mobile'] = $addrs->mobile;
            $arr['address'] = $addrs->address_name;
            $arr['city'] = $addrs->city;
            $arr['state'] = $addrs->state;
            $arr['country'] = $addrs->country;
            $arr['zipcode'] = $addrs->zipcode;
            $arr['isd_code'] = $addrs->isd_code;
            $arr['payment_method'] = sanitize_remove_tags($_POST['payment_mode']);
            // $arr['pv'] = 0;

            $arr['unique_id'] = uniqid();

            $arr['status'] = $_POST['payment_mode'] == 'Bank_transfer' ? "pending" : 'paid';
            $arr['updated_at'] = $_POST['payment_mode'] == 'Bank_transfer' ? null : date('Y-m-d H:i:s');

            try {
                $dbobj->tableName = "payment";
                $dbobj->insertData = $arr;
                $pay = $dbobj->create();
                if (intval($pay) && $arr['status'] == 'paid') {
                    $invid = generate_invoice_id($dbobj);
                    update_inv_if_not($pay, $invid, $dbobj);
                    $pvctrl = new Pv_ctrl;
                    $pvctrl->db = $dbobj;
                    $pvctrl->save_commissions($purchaser_id = $_SESSION['user_id'], $order_id = $pay, $pv = $total_pv, $rv=$total_rv, $total_db);
                }
                $con->commit();
            } catch (PDOException $th) {
                $_SESSION['msg'][] = $th;
                $pay = false;
                $con->rollback();
            }
            if (intval($pay)) {
                $dbobj = new Dbobjects;
                $dbobj->tableName = 'customer_order';
                // Filter user cart
                $dbobj->filter(array('status' => 'cart', 'user_id' => $_SESSION['user_id']));
                // Update cart with payment data
                $dbobj->insertData['payment_id'] = $pay;
                $dbobj->insertData['status'] = 'paid';
                $dbobj->insertData['updated_at'] = date('Y-m-d H:i:s');
                // execute payment data
                $dbobj->update();
                $_SESSION['msg'][] = 'Order placed';
                $my_email = null;

                if (isset($_SESSION['user_id'])) {
                    $userid = $_SESSION['user_id'];
                    $invite = getData("pk_user", $userid);
                    $my_email = $invite != false ? $invite['email'] : null;
                }
                $shpadrs = get_my_primary_address($userid = USER['id']);
                $bank = get_invoice_address($country_code = $shpadrs->country_code)->bank;
                new_order_email(obj([
                    'email' => $my_email,
                    'order_id' => $pay,
                    'order_amt' => $total_amt,
                    'bank_account' => $bank
                ]));
                return true;
            } else {
                $_SESSION['msg'][] = 'Order not placed';
                return false;
            }
        }
    }
    public function confirm_order_status($id, $dataObj)
    {
        $updated_at = date('Y-m-d H:i:s');
        $pmt = getData('payment',$id);
        $pvctrl = new Pv_ctrl;
        $pvctrl->db = new Dbobjects;
        $pvctrl->save_commissions($purchaser_id = $pmt['user_id'], $order_id = $id, $pv = $pmt['pv'], $rv=$pmt['rv'], $pmt['direct_bonus']);
        return (new Model('payment'))->update($id, ['status' => 'paid', 'info' => $dataObj->info, 'updated_at' => $updated_at]);
    }
}
