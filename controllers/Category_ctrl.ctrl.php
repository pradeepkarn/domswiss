<?php

class Category_ctrl
{
    public function save($item_group = 'category')
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
           
            
            if (!isset($req->image)) {
                $_SESSION['msg'][] = "categroy image is required";
                $ok = false;
            }
            if ($ok == true) {
                if (
                    $req->name == ''
                ) {
                    $_SESSION['msg'][] = "Category name is required";
                    $ok = false;
                }
                if ($req->image->name == '') {
                    $_SESSION['msg'][] = "Image is required";
                    $ok = false;
                }
            }
            if ($ok == true) {
                $arr['name'] = $req->name;
                $arr['item_group'] = $item_group;
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
                    $_SESSION['msg'][] = "Category created successfully";
                    echo RELOAD;
                    return;
                } else {
                    $_SESSION['msg'][] = "Category not created";
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
           
           
            // if (!isset($req->image)) {
            //     $_SESSION['msg'][] = "Category image is required";
            //     $ok = false;
            // }
            if ($ok == true) {
                if (
                    !intval($req->product_id) ||
                    $req->name == '' 
                ) {
                    $_SESSION['msg'][] = "All fields are required";
                    $ok = false;
                }
            }
            if ($ok == true) {
                $arr['name'] = $req->name;
                $itemObj = new Model('item');
                $item_id = $req->product_id;
                $reply = $itemObj->update($item_id, $arr);
                $item = obj(getData(table: 'item', id: $item_id));
                if ($reply) {
                       if (isset($req->image->name) && $req->image->name!= "") {
                        $ext = pathinfo($req->image->name, PATHINFO_EXTENSION);
                        $imgname = str_replace(" ", "_", $req->name) . uniqid("_") . "." . $ext;
                        $imgname = str_replace("/","-",$imgname);
                        $imgname = str_replace("\\","-",$imgname);
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
                    $_SESSION['msg'][] = "Category updated successfully";
                    echo RELOAD;
                    return;
                } else {
                    $_SESSION['msg'][] = "Category not updated";
                    return;
                }
            } else {
                return false;
            }
        }
    }
}
