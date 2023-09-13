<?php class Domswiss_tree_ctrl
{
    function handle_rv($user_id)
    {
        $pvctrl = new Pv_ctrl;
        $pvctrl->db = new Dbobjects;
        // $act_data = $pvctrl->am_i_active($user_id);
        // $tree = $pvctrl->my_tree($user_id, 1);

        // $tree  = my_tree($ref = $user_id, 1, $last_pmt);
        // $depth = 1;
        // $treeLength = count($tree);
        // $calc = calculatePercentageSum($data = $tree, $depth, $treeLength, $user_id);
        // $sum = $calc['sum'];
        // $rv_sum = $calc['rv_sum'] + my_rv_and_admin_rv($user_id = $user_id, $dbobj = null);
        // $jsonData = json_encode($tree, JSON_PRETTY_PRINT);
        // $file = "jsondata/trees/tree_" . $user_id . '.json';
        // file_put_contents($file, $jsonData);
        $db = new Model('credits');
        $rv_sum = $pvctrl->my_lifetime_rank_advance_sum($user_id);
        $rv_sum += my_rv_and_admin_rv($user_id = $user_id, $dbobj = null);
        $rv_sum += my_old_rv($user_id = $user_id, $dbobj = null);
        // $crarr['user_id'] = $user_id;
        // $crarr['status'] = 'lifetime';
        // $already = $db->filter_index($crarr);
        // if (count($already) > 0) {
        //     $crid = obj($already[0]);
        //     $crarr['amt'] = $sum;
        //     $db->update($id = $crid->id, $crarr);
        // } else {
        //     $crarr['amt'] = $sum;
        //     $db->store($crarr);
        // }
        // $direct_bonus = total_bonus($user_id);
        // $drb['user_id'] = $user_id;
        // $drb['status'] = 'direct_bonus';
        // $alrdbns = $db->filter_index($drb);
        // if (count($alrdbns) > 0) {
        //     $drbid = obj($alrdbns[0]);
        //     $drb['amt'] = $direct_bonus;
        //     $db->update($drbid->id, $drb);
        // } else {
        //     $drb['amt'] = $direct_bonus;
        //     $db->store($drb);
        // }
        $position = getPosition($level = $rv_sum);
        $this->save_share_by_month($user_id, $position);
        return $position;
    }

    function save_share_by_month($user_id, $position)
    {
        $slaeCtrl = new Sales_ctrl;
        // $slaeCtrl->firstDay = date('2023-07-01');
        // $slaeCtrl->lastDay = date('2023-07-31');
        // $sale = $slaeCtrl->get_sale_volume();
        $sale = $slaeCtrl->get_pv_volume();
        $shr = $slaeCtrl->distribute_share_by_pool($total_sale = $sale);
        $date_from = $slaeCtrl->firstDay;
        $date_to = $slaeCtrl->lastDay;
        // $file = RPATH . "/jsondata/pool/members.json";
        // if (file_exists($file)) {
        //     $jsndta = file_get_contents($file);
        //     $mmbrs = json_decode($jsndta, true);
        // }
        $jsn = null;
        $shredivs = RPATH . "/jsondata/pool/share_divs.json";
        if (file_exists($shredivs)) {
            $shrsdta = file_get_contents($shredivs);
            $shresexmpls = json_decode($shrsdta, true);

            if (isset($shresexmpls[strtolower($position)])) {
                $dtss = $shresexmpls[strtolower($position)];
                $myshares = array(
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'shares' => array(
                        'pool1' =>  $dtss['pool1'] * $shr['pool1']['unit_value'],
                        'pool2' =>  $dtss['pool2'] * $shr['pool2']['unit_value'],
                        'pool3' =>  $dtss['pool3'] * $shr['pool3']['unit_value'],
                        'pool4' =>  $dtss['pool4'] * $shr['pool4']['unit_value']
                    ),
                    'share_count' => array(
                        'pool1' =>  $dtss['pool1'],
                        'pool2' =>  $dtss['pool2'],
                        'pool3' =>  $dtss['pool3'],
                        'pool4' =>  $dtss['pool4']
                    )
                );
                $jsn = json_encode($myshares);
            }
        }

        // $db = new Dbobjects;
        // $db->tableName = 'shares';
        // $olddata = $db->filter(['user_id' => $user_id, 'date_from' => $date_from, 'date_to' => $date_to]);
        // if (count($olddata) > 0) {
        //     $db->insertData['user_id'] = $user_id;
        //     $db->insertData['date_from'] = $date_from;
        //     $db->insertData['date_to'] = $date_to;
        //     $db->insertData['jsn'] = $jsn;
        //     $db->insertData['position'] = $position;
        //     $db->insertData['updated_at'] = date('Y-m-d H:i:s');
        //     $db->update();
        // } else {
        //     $db->insertData['user_id'] = $user_id;
        //     $db->insertData['date_from'] = $date_from;
        //     $db->insertData['date_to'] = $date_to;
        //     $db->insertData['jsn'] = $jsn;
        //     $db->insertData['position'] = $position;
        //     $db->insertData['updated_at'] = date('Y-m-d H:i:s');
        //     $db->create();
        // }
    }
}
