<?php

class Product_ctrl
{
    public function save($item_group = 'product')
    {
        $ok = true;
        $req = (object) ($_POST);
        $req->image = isset($_FILES['image']) ? (object) ($_FILES['image']) : false;
        // myprint($req);
        // return;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($req->name)) {
                $_SESSION['msg'][] = "Name is required";
                $ok = false;
            }
            if (!isset($req->suppliment)) {
                $_SESSION['msg'][] = "Suppliment is required";
                $ok = false;
            }
            if (!isset($req->qty)) {
                $_SESSION['msg'][] = "Quantity is required";
                $ok = false;
            }
            if (!isset($req->unit)) {
                $_SESSION['msg'][] = "Unit is required";
                $ok = false;
            }
            if (!isset($req->prod_country)) {
                $_SESSION['msg'][] = "Production country is required";
                $ok = false;
            }
            if (!isset($req->ean_code)) {
                $_SESSION['msg'][] = "Ean code is required";
                $ok = false;
            }
            if (!isset($req->zlf_num)) {
                $_SESSION['msg'][] = "ZLF number is required";
                $ok = false;
            }
            if (!isset($req->mf_price)) {
                $_SESSION['msg'][] = "Manuf. price is required";
                $ok = false;
            }
            if (!isset($req->min_price)) {
                $_SESSION['msg'][] = "Min. price is required";
                $ok = false;
            }
            // if (!isset($req->product_id)) {
            //     $_SESSION['msg'][] = "Product id required";
            //     $ok = false;
            // }
            $product_id = generate_id('product');
            // if (!isset($req->tax)) {
            //     $_SESSION['msg'][] = "Tax is required";
            //     $ok = false;
            // }
            // if (!isset($req->details)) {
            //     $_SESSION['msg'][] = "Product details is required";
            //     $ok = false;
            // }
            // if (!isset($req->image)) {
            //     $_SESSION['msg'][] = "Product image is required";
            //     $ok = false;
            // }
            if ($ok == true) {
                if (
                    $req->name == '' ||
                    $req->suppliment == '' ||
                    $req->qty == '' ||
                    $req->unit == '' ||
                    $req->prod_country == '' ||
                    $req->ean_code == '' ||
                    $req->zlf_num == '' ||
                    $req->mf_price == '' ||
                    $req->min_price == ''
                ) {
                    $_SESSION['msg'][] = "All fields are required";
                    $ok = false;
                }
                // if ($req->image->name == '') {
                //     $_SESSION['msg'][] = "Product image is required";
                //     $ok = false;
                // }
            }
            if ($ok == true) {
                $arr['name'] = $req->name;
                $arr['qty'] = $req->qty;
                $arr['unit'] = $req->unit;
                $arr['prod_country'] = $req->prod_country;
                $arr['ean_code'] = $req->ean_code;
                $arr['zlf_num'] = $req->zlf_num;
                $arr['mf_price'] = $req->mf_price;
                $arr['min_price'] = $req->min_price;
                $arr['price'] = $req->min_price;
                $arr['product_id'] = $product_id;
                // $arr['pv'] = $req->pv;
                $arr['suppliment'] = intval($req->suppliment);
                $arr['details'] = $req->details;
                $arr['parent_id'] = isset($req->parent_id)?intval($req->parent_id):0;
                $arr['item_group'] = $item_group;
                $itemObj = new Model('item');
                $item_id = $itemObj->store($arr);
                if (intval($item_id)) {
                    if (isset($req->image->name) && $req->image->name!= "") {
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
                    $_SESSION['msg'][] = "Product created successfully";
                    echo RELOAD;
                    return;
                } else {
                    $_SESSION['msg'][] = "Product not created";
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($req->product_id)) {
                $_SESSION['msg'][] = "product id is required";
                $ok = false;
            }
            if (!isset($req->name)) {
                $_SESSION['msg'][] = "Name is required";
                $ok = false;
            }
            if (!isset($req->suppliment)) {
                $_SESSION['msg'][] = "Suppliment is required";
                $ok = false;
            }
            if (!isset($req->qty)) {
                $_SESSION['msg'][] = "Quantity is required";
                $ok = false;
            }
            if (!isset($req->unit)) {
                $_SESSION['msg'][] = "Unit is required";
                $ok = false;
            }
            if (!isset($req->prod_country)) {
                $_SESSION['msg'][] = "Production country is required";
                $ok = false;
            }
            if (!isset($req->ean_code)) {
                $_SESSION['msg'][] = "Ean code is required";
                $ok = false;
            }
            if (!isset($req->zlf_num)) {
                $_SESSION['msg'][] = "ZLF number is required";
                $ok = false;
            }
            if (!isset($req->mf_price)) {
                $_SESSION['msg'][] = "Manuf. price is required";
                $ok = false;
            }
           
            if (!isset($req->min_price)) {
                $_SESSION['msg'][] = "Min. price is required";
                $ok = false;
            }
            // if (!isset($req->tax)) {
            //     $_SESSION['msg'][] = "Tax is required";
            //     $ok = false;
            // }
            // if (!isset($req->details)) {
            //     $_SESSION['msg'][] = "Product details is required";
            //     $ok = false;
            // }
            // if (!isset($req->image)) {
            //     $_SESSION['msg'][] = "Product image is required";
            //     $ok = false;
            // }
            if ($ok == true) {
                if (
                    !intval($req->product_id) ||
                    $req->name == '' ||
                    $req->suppliment == '' ||
                    $req->qty == '' ||
                    $req->unit == '' ||
                    $req->prod_country == '' ||
                    $req->ean_code == '' ||
                    $req->zlf_num == '' ||
                    $req->mf_price == '' ||
                    $req->min_price == '' 
                ) {
                    $_SESSION['msg'][] = "All fields are required";
                    $ok = false;
                }
                // if ($req->image->name == '') {
                //     $_SESSION['msg'][] = "Product image is required";
                //     $ok = false; 
                // }
            }
            if ($ok == true) {
                $arr['name'] = $req->name;
                $arr['qty'] = $req->qty;
                $arr['unit'] = $req->unit;
                $arr['prod_country'] = $req->prod_country;
                $arr['ean_code'] = $req->ean_code;
                $arr['zlf_num'] = $req->zlf_num;
                $arr['mf_price'] = $req->mf_price;
                $arr['min_price'] = $req->min_price;
                $arr['price'] = $req->min_price;
                // $arr['pv'] = $req->pv;
                // $arr['tax'] = $req->tax;
                $arr['suppliment'] = intval($req->suppliment);
                // $arr['details'] = $req->details;
                $arr['parent_id'] = isset($req->parent_id)?intval($req->parent_id):0;
                $itemObj = new Model('item');
                $item_id = $req->product_id;
                $reply = $itemObj->update($item_id, $arr);
                $item = obj(getData(table: 'item', id: $item_id));
                if ($reply) {
                    if (isset($req->image->name) && $req->image->name!= "") {
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
                    $_SESSION['msg'][] = "Product updated successfully";
                    echo RELOAD;
                    return;
                } else {
                    $_SESSION['msg'][] = "Product not updated";
                    return;
                }
            } else {
                return;
            }
        }
    }
}
