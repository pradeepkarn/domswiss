<?php

class Country_ctrl
{
    public function save()
    {
    }
    public function update()
    {
        $ok = true;
        $req = (object) ($_POST);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($req->country_id)) {
                $_SESSION['msg'][] = "country id is required";
                $ok = false;
            }
            if (!isset($req->name)) {
                $_SESSION['msg'][] = "Name is required";
                $ok = false;
            }

            if (!isset($req->max_tax)) {
                $_SESSION['msg'][] = "Min. tax is required";
                $ok = false;
            }

            if (!isset($req->min_tax)) {
                $_SESSION['msg'][] = "Mix. tax is required";
                $ok = false;
            }
            if (!isset($req->country_code)) {
                $_SESSION['msg'][] = "Country code is missiing";
                $ok = false;
            }
            $jsn = [];
            if (isset($req->bank_details)) {
                $jsn['banks'][] = $req->bank_details;
                $jsn['gateways'][] = null;
            }
            if (isset($req->office_address)) {
                $jsn['office_address'][] = $req->office_address;
            }
            if (isset($req->delivery_info)) {
                $arr['delv_info'] = $req->delivery_info;
            }
            if (isset($req->shipping_cost)) {
                $shpcst = obj([]);
                $shpcst->shipping_cost = $req->shipping_cost;
                $shpcst->country_code = $req->country_code;
                $arr['shipping'] = json_encode($shpcst);
            }
            $arr['jsn'] = json_encode($jsn);
            if ($ok == true) {
                if (
                    !intval($req->country_id) ||
                    $req->name == '' ||
                    $req->min_tax == '' ||
                    $req->max_tax == ''
                ) {
                    $_SESSION['msg'][] = "All fields are required";
                    $ok = false;
                }
            }
            if ($ok == true) {
                $arr['name'] = sanitize_remove_tags($req->name);
                $arr['min_tax'] = $req->min_tax;
                $arr['max_tax'] = $req->max_tax;
                $itemObj = new Model('countries');
                $item_id = $req->country_id;
                $reply = $itemObj->update($item_id, $arr);
                if ($reply) {
                    $_SESSION['msg'][] = "country updated successfully";
                    echo RELOAD;
                    return;
                } else {
                    $_SESSION['msg'][] = "country not updated";
                    return;
                }
            } else {
                return;
            }
        }
    }
}
