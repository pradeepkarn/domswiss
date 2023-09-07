<?php

class Package_ctrl
{
    public function save($item_group = 'package')
    {
        $ok = true;
        $req = (object) ($_POST);
        $req->image = isset($_FILES['image']) ? (object) ($_FILES['image']) : false;
        // myprint($req);
        // return;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($req->image)) {
                $_SESSION['msg'][] = "Package image is required";
                $ok = false;
            }
            if (!isset($req->name)) {
                $_SESSION['msg'][] = "Name is required";
                $ok = false;
            }

            if (!isset($req->countries)) {
                $_SESSION['msg'][] = "Please select at leaast 1 country";
                $ok = false;
            }
            if (!isset($req->items)) {
                $_SESSION['msg'][] = "Please select at least 1 product";
                $ok = false;
            }
            if (!isset($req->net_price)) {
                $_SESSION['msg'][] = "Net price is required";
                $ok = false;
            }
            if (!isset($req->cust_price)) {
                $_SESSION['msg'][] = "Customer price is required";
                $ok = false;
            }
            if (!isset($req->pv)) {
                $_SESSION['msg'][] = "PV is required";
                $ok = false;
            }
            if (!isset($req->details)) {
                $_SESSION['msg'][] = "Package detail is required";
                $ok = false;
            }
            if (!isset($req->direct_bonus)) {
                $_SESSION['msg'][] = "Direct bonus field is empty";
                $ok = false;
            }
            if ($ok == true) {
                if (
                    $req->name == '' ||
                    $req->net_price == '' ||
                    $req->cust_price == '' ||
                    $req->pv == '' ||
                    $req->details == ''

                ) {
                    $_SESSION['msg'][] = "All fields are required";
                    $ok = false;
                }
                if ($req->image->name == '') {
                    $_SESSION['msg'][] = "Product image is required";
                    $ok = false;
                }
            }
            if ($ok == true) {
                $arr['name'] = $req->name;
                $arr['qty'] = 1;
                $arr['net_price'] = $req->net_price;
                $arr['cust_price'] = $req->cust_price;
                $arr['pv'] = $req->pv;
                if (isset($req->rv)) {
                    $arr['rv'] = $req->rv;
                }
                $product_id = generate_id('package');
                $arr['product_id'] = $product_id;
                $arr['price'] = $req->cust_price;
                // $arr['tax'] = $req->tax;
                $arr['details'] = $req->details;
                if (isset($req->direct_bonus)) {
                    $arr['direct_bonus'] = $req->direct_bonus;
                }
                // $arr['parent_id'] = isset($req->parent_id)?intval($req->parent_id):0;
                $arr['item_group'] = $item_group;
                $allItems = [];
                // foreach ($req->items as $item) {
                //     $item_qty = "qty" . $item;
                //     if ($req->$item_qty == '') {
                //         $_SESSION['msg'][] = "Check product quantity";
                //         echo js_alert(msg_ssn(return: true));
                //         exit;
                //     }
                //     $allItems[] = array('item' => $item, 'qty' => $req->$item_qty);
                // }
                foreach ($req->items as $item) {
                    $item_qty = "qty" . $item;
                    $net_price = "net_price" . $item;
                    $cust_net_price = "cust_net_price" . $item;
                    if ($req->$item_qty == '') {
                        $_SESSION['msg'][] = "Check product quantity";
                        echo js_alert(msg_ssn(return: true));
                        exit;
                    }
                    $allItems[] = array(
                        'item' => $item,
                        'qty' => $req->$item_qty,
                        'net_price' => $req->$net_price,
                        'cust_net_price' => $req->$cust_net_price
                    );
                }
                $arr['jsn'] = json_encode(array(
                    'items' => $allItems,
                    'countries' => $req->countries,
                ));
                $arr['is_active'] = isset($req->is_active) ? 1 : 0;
                $arr['show_to_cust'] = isset($req->show_to_cust) ? 1 : 0;
                $itemObj = new Model('item');
                $item_id = $itemObj->store($arr);
                if (intval($item_id)) {
                    if ($req->image->name != "") {
                        $ext = pathinfo($req->image->name, PATHINFO_EXTENSION);
                        $imgname = str_replace(" ", "_", $req->name) . uniqid("_") . "." . $ext;
                        if (!is_dir(MEDIA_ROOT . "upload/items/")) {
                            mkdir(MEDIA_ROOT . "upload/items/", 0755, true);
                        }
                        $dir = MEDIA_ROOT . "upload/items/" . $imgname;
                        $upload = move_uploaded_file($req->image->tmp_name, $dir);
                        if ($upload) {
                            (new Model('item'))->update($item_id, array('image' => $imgname));
                        }
                    }
                    $_SESSION['msg'][] = "Package created successfully";
                    echo RELOAD;
                    return;
                } else {
                    $_SESSION['msg'][] = "Package not created";
                    return;
                }
            } else {
                return;
            }
        }
    }
    public function update()
    {
        $ok = true;
        $req = (object) ($_POST);
        $req->image = isset($_FILES['image']) ? (object) ($_FILES['image']) : false;
        // myprint($req);
        // return;
        // myprint($req);
        // return;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($req->product_id)) {
                $_SESSION['msg'][] = "product id is required";
                $ok = false;
            }
            if (!isset($req->name)) {
                $_SESSION['msg'][] = "Name is required";
                $ok = false;
            }
            if (!isset($req->countries)) {
                $_SESSION['msg'][] = "Please select at leaast 1 country";
                $ok = false;
            }
            if (!isset($req->items)) {
                $_SESSION['msg'][] = "Please select at least 1 product";
                $ok = false;
            }
            if (!isset($req->net_price)) {
                $_SESSION['msg'][] = "Net price is required";
                $ok = false;
            }
            if (!isset($req->cust_price)) {
                $_SESSION['msg'][] = "Customer price is required";
                $ok = false;
            }
            if (!isset($req->pv)) {
                $_SESSION['msg'][] = "PV is required";
                $ok = false;
            }
            if (!isset($req->details)) {
                $_SESSION['msg'][] = "Package details is required";
                $ok = false;
            }
            if (!isset($req->direct_bonus)) {
                $_SESSION['msg'][] = "Direct bonus field is empty";
                $ok = false;
            }
            if ($ok == true) {
                if (
                    !intval($req->product_id) ||
                    $req->name == '' ||
                    $req->net_price == '' ||
                    $req->cust_price == '' ||
                    $req->pv == '' ||
                    $req->details == ''
                ) {
                    $_SESSION['msg'][] = "All fields are required";
                    $ok = false;
                }
            }
            $total_gram = 0;
            if ($ok == true) {
                $arr['name'] = $req->name;
                $arr['qty'] = 1;
                $arr['net_price'] = $req->net_price;
                $arr['cust_price'] = $req->cust_price;
                $arr['pv'] = $req->pv;
                if (isset($req->rv)) {
                    $arr['rv'] = $req->rv;
                }
                $arr['price'] = $req->cust_price;
                $arr['details'] = $req->details;
                if (isset($req->direct_bonus)) {
                    $arr['direct_bonus'] = $req->direct_bonus;
                }
                // $arr['parent_id'] = isset($req->parent_id)?intval($req->parent_id):0;
                $allItems = [];
                // myprint($req);
                $db = new Dbobjects;
                foreach ($req->items as $item) {
                    $item_qty = "qty" . $item;
                    $net_price = "net_price" . $item;
                    $cust_net_price = "cust_net_price" . $item;
                    if ($req->$item_qty == '') {
                        $_SESSION['msg'][] = "Check product quantity";
                        echo js_alert(msg_ssn(return: true));
                        exit;
                    }
                    $allItems[] = array(
                        'item' => $item,
                        'qty' => $req->$item_qty,
                        'net_price' => $req->$net_price,
                        'cust_net_price' => $req->$cust_net_price
                    );
                    $prod = (object)$db->showOne("select id,qty,unit from item where item.id = $item");
                    $total_gram += calculate_gram($prod, $req->$item_qty);
                }
                $shipping_charges = [];
                foreach ($req->countries as $ccode) {
                    $shpcost = calculate_shipping_cost($db, $total_gram, $ccode);
                    $shipping_charges[] = array(
                        "ccode" => $ccode,
                        "shipping_cost" => $shpcost
                    );
                }
                $arr['shipping']=json_encode(array(
                    'shipping' => $shipping_charges
                ));
                $arr['jsn'] = json_encode(array(
                    'items' => $allItems,
                    'countries' => $req->countries,
                ));
                $arr['is_active'] = isset($req->is_active) ? 1 : 0;
                $arr['show_to_cust'] = isset($req->show_to_cust) ? 1 : 0;
                // $arr['total_gram'] = $total_gram;
                $itemObj = new Model('item');
                $item_id = $req->product_id;
                $update_reply = $itemObj->update($item_id, $arr);
                $item = obj(getData(table: 'item', id: $item_id));
                if ($update_reply) {
                    if ($req->image->name != "") {
                        $ext = pathinfo($req->image->name, PATHINFO_EXTENSION);
                        $imgname = str_replace(" ", "_", $req->name) . uniqid("_") . "." . $ext;
                        if (!is_dir(MEDIA_ROOT . "upload/items/")) {
                            mkdir(MEDIA_ROOT . "upload/items/", 0755, true);
                        }
                        $dir = MEDIA_ROOT . "upload/items/" . $imgname;
                        $upload = move_uploaded_file($req->image->tmp_name, $dir);
                        if ($upload) {
                            (new Model('item'))->update($item_id, array('image' => $imgname));
                            $old = obj($item);

                            if ($old->image != "") {
                                $olddir = MEDIA_ROOT . "upload/items/" . $old->image;
                                if (file_exists($olddir)) {
                                    unlink($olddir);
                                }
                            }
                        }
                    }
                    $_SESSION['msg'][] = "package updated successfully";
                    echo RELOAD;
                    return;
                } else {
                    $_SESSION['msg'][] = "Package not created";
                    return;
                }
            } else {
                return;
            }
        }
    }
}
