<?php

class Pv_ctrl
{
    public $db;
    public $firstDay;
    public $lastDay;
    public $currentDate;
    function __construct()
    {
        $this->currentDate = new DateTime();
        // $this->db = new Dbobjects;
        $this->firstDay = date('Y-m-01 00:00:00');
        $this->lastDay = date('Y-m-t 23:59:59');
    }

    function my_lifetime_commission_sum($my_id)
    {
        $db = new Dbobjects;
        $sql = "SELECT SUM(commission) as cmsn_sum FROM ring_commissions WHERE `partner_id`= $my_id;";
        try {
            return round(($db->show($sql)[0]['cmsn_sum']), 2);
        } catch (PDOException $th) {
            return 0.00;
        }
    }
    function my_admin_commission_sum($my_id)
    {
        $db = new Dbobjects;
        $sql = "SELECT SUM(amount) as cmsn_sum FROM extra_credits WHERE `added_to`= $my_id;";
        try {
            return round(($db->show($sql)[0]['cmsn_sum']), 2);
        } catch (PDOException $th) {
            return 0.00;
        }
    }
    function my_lifetime_direct_bonus_sum($my_id)
    {
        $db = new Dbobjects;
        $sql = "SELECT SUM(direct_bonus) as d_b FROM ring_commissions WHERE `partner_id`= $my_id;";
        try {
            return round(($db->show($sql)[0]['d_b']), 2);
        } catch (PDOException $th) {
            return 0.00;
        }
    }
    function my_lifetime_rank_advance_sum($my_id)
    {
        $db = new Dbobjects;
        $sql = "SELECT SUM(rank_advance) as rank_advance_sum FROM ring_commissions WHERE `partner_id`= $my_id;";
        try {
            return round(($db->show($sql)[0]['rank_advance_sum']), 2);
        } catch (PDOException $th) {
            return 0.00;
        }
    }
    function my_all_type_rank_advance_sum($my_id)
    {
         $rv_sum = $this->my_lifetime_rank_advance_sum($my_id);
         $rv_sum = old_data('rank_advance',$my_id);
         $rv_sum += my_rv_and_admin_rv($user_id = $my_id, $dbobj = $this->db);
         return $rv_sum;
    }
    function my_this_month_commission_sum($my_id)
    {
        $db = new Dbobjects;
        $sql = "SELECT SUM(commission) as cmsn_sum FROM ring_commissions WHERE `partner_id`= $my_id AND created_at >= '$this->firstDay' AND created_at <= '$this->lastDay';";
        try {
            return round(($db->show($sql)[0]['cmsn_sum']), 2);
        } catch (PDOException $th) {
            return 0.00;
        }
    }
    function my_this_month_rank_advacne_sum($my_id)
    {
        $db = new Dbobjects;
        $sql = "SELECT SUM(rank_advance) as rank_advance_sum FROM ring_commissions WHERE `partner_id`= $my_id AND created_at >= '$this->firstDay' AND created_at <= '$this->lastDay';";
        try {
            return round(($db->show($sql)[0]['rank_advance_sum']), 2);
        } catch (PDOException $th) {
            return 0.00;
        }
    }
    function my_last_month_commission_sum($my_id)
    {

        $fromDate = clone $this->currentDate;
        $firstDay = $fromDate->modify('-1 month')->format('Y-m-01 00:00:00');
        // Clone the current date to another object for the "to" date
        $toDate = clone $this->currentDate;
        $lastDay = $toDate->modify('-1 month')->format('Y-m-t 23:59:59');

        $db = new Dbobjects;
        $sql = "SELECT SUM(commission) as cmsn_sum FROM ring_commissions WHERE `partner_id`= $my_id AND created_at >= '$firstDay' AND created_at <= '$lastDay';";
        try {
            return round(($db->show($sql)[0]['cmsn_sum']), 2);
        } catch (PDOException $th) {
            return 0.00;
        }
    }
    function my_last_month_rank_advance_sum($my_id)
    {

        $fromDate = clone $this->currentDate;
        $firstDay = $fromDate->modify('-1 month')->format('Y-m-01 00:00:00');
        // Clone the current date to another object for the "to" date
        $toDate = clone $this->currentDate;
        $lastDay = $toDate->modify('-1 month')->format('Y-m-t 23:59:59');

        $db = new Dbobjects;
        $sql = "SELECT SUM(rank_advance) as rank_advance_sum FROM ring_commissions WHERE `partner_id`= $my_id AND created_at >= '$firstDay' AND created_at <= '$lastDay';";
        try {
            return round(($db->show($sql)[0]['rank_advance_sum']), 2);
        } catch (PDOException $th) {
            return 0.00;
        }
    }
    function commission_sum_by_date_range()
    {
        $db = new Dbobjects;
        $sql = "SELECT SUM(commission) as cmsn_sum FROM ring_commissions WHERE created_at >= '$this->firstDay' AND created_at <= '$this->lastDay';";
        try {
            return round(($db->show($sql)[0]['cmsn_sum']), 2);
        } catch (PDOException $th) {
            return 0.00;
        }
    }
    function rank_advance_sum_by_date_range()
    {
        $db = new Dbobjects;
        $sql = "SELECT SUM(rank_advance) as rank_advance_sum FROM ring_commissions WHERE created_at >= '$this->firstDay' AND created_at <= '$this->lastDay';";
        try {
            return round(($db->show($sql)[0]['rank_advance_sum']), 2);
        } catch (PDOException $th) {
            return 0.00;
        }
    }
    function my_tree($ref, $depth = 1)
    {
        if ($depth > 10) {
            return []; // Return an empty array if the maximum depth is reached
        }

        $prtdata = array();
        // $this->db = new Dbobjects;
        $sql = "select pk_user.id, pk_user.username, pk_user.image, pk_user.ref from pk_user where pk_user.ref = $ref and pk_user.ref != 0 order by pk_user.id desc";
        $data = $this->db->show($sql);

        foreach ($data as $p) {
            $od = $this->order_details($user_id = $p['id'], $this->db);
            $prtdata[] = array(
                'id' => $p['id'],
                'ring' => $depth,
                'username' => $p['username'],
                'is_active' => $this->check_active($user_id = $p['id']),
                'pv' => $od['total_pv'] ? round($od['total_pv'], 2) : 0,
                'rv' => $od['total_rv'] ? round($od['total_rv'], 2) : 0,
                'tree' => $this->my_tree($p['id'], $depth + 1)
            );
        }

        return $prtdata;
    }
    function d3_tree($ref, $depth = 1)
    {
        if ($depth > 10) {
            return []; // Return an empty array if the maximum depth is reached
        }

        $prtdata = array();
        // $this->db = new Dbobjects;
        $sql = "select pk_user.id, pk_user.username, pk_user.image, pk_user.ref from pk_user where pk_user.ref = $ref and pk_user.ref != 0 order by pk_user.id desc";
        $data = $this->db->show($sql);

        foreach ($data as $p) {
            $od = $this->order_details($user_id = $p['id'], $this->db);
            $prtdata[] = array(
                'id' => $p['id'],
                'ring' => $depth,
                'name' => $p['username'],
                'is_active' => $this->check_active($user_id = $p['id']),
                'pv' => $od['total_pv'] ? round($od['total_pv'], 2) : 0,
                'rv' => $od['total_rv'] ? round($od['total_rv'], 2) : 0,
                'children' => $this->d3_tree($p['id'], $depth + 1)
            );
        }

        return $prtdata;
    }
    function order_details($user_id = null, $db = new Dbobjects)
    {
        $sql_sum = "SELECT SUM(amount) AS purchase, SUM(pv) AS total_pv, SUM(rv) AS total_rv FROM payment WHERE user_id = $user_id AND status = 'paid' AND (invoice IS NOT NULL AND invoice <> '') AND updated_at >= '$this->firstDay' AND updated_at <= '$this->lastDay';";
        return $db->show($sql_sum)[0];
    }
    function am_i_active($user_id)
    {
        $today = date('Y-m-d H:i:s');
        $sql = "SELECT pv, id,(33-DATEDIFF('$today', created_at)) as days_left 
        FROM payment 
        WHERE user_id = $user_id 
        AND pv >= 15 
        AND DATEDIFF('$today', created_at) <= 33 
        AND status = 'paid' 
        AND (invoice IS NOT NULL AND invoice <> '')
        ORDER BY created_at DESC
        LIMIT 1;";
        // echo $sql;
        $data = $this->db->show($sql);
        if (count($data) > 0) {
            return array('active' => true, 'data' => $data[0]);
        } else {
            return array('active' => false, 'data' => null);
        }
    }

    // function my_lifetime_pv($user_id)
    // {
    //     $sql = "SELECT SUM(amt) as pv_sum from my_fund where content_group = 'pv' and user_id=$user_id";
    //     $data = $this->db->show($sql);
    //     if (isset($data[0])) {
    //         return $data[0]['pv_sum'];
    //     } else {
    //         return 0;
    //     }
    // }

    function ring_commissions($purchaser_id, $order_id, $pv, $rv, $direct_bonus)
    {
        $cmsn_users = [];
        // $ref_id = [];
        $ref_id1 = $this->get_partner_id($my_id = $purchaser_id);
        if ($ref_id1 == 0) {
            return $cmsn_users;
        }
        $ref_id2 = $this->get_partner_id($my_id = $ref_id1);
        $ref_id3 = $this->get_partner_id($my_id = $ref_id2);
        $ref_id4 = $this->get_partner_id($my_id = $ref_id3);
        $ref_id5 = $this->get_partner_id($my_id = $ref_id4);
        $ref_id6 = $this->get_partner_id($my_id = $ref_id5);
        $ref_id7 = $this->get_partner_id($my_id = $ref_id6);
        $ref_id8 = $this->get_partner_id($my_id = $ref_id7);
        $ref_id9 = $this->get_partner_id($my_id = $ref_id8);
        $ref_id10 = $this->get_partner_id($my_id = $ref_id9);

        // Ring 1
        if ($this->check_ever_active($ref_id1)==true) {
            $cmsn_users[] = array(
                'partner_id' => $ref_id1,
                'commission' => round(($pv * 0.07), 2),
                'rank_advance' => round(($rv * 0.07), 2),
                'percentage' => 7,
                'pv' => $pv,
                'rv' => $rv,
                'order_by' => $purchaser_id,
                'order_id' => $order_id,
                'direct_bonus' => $direct_bonus,
                'ring' => 1
            );
        }
        if ($ref_id2 == 0) {
            return  $cmsn_users;
        }
        // Ring 2
        if ($this->count_active_partners($ref_id2) >= 2 && $ref_id2 != 0) {
            $cmsn_users[] = array(
                'partner_id' => $ref_id2,
                'commission' => round(($pv * 0.05), 2),
                'rank_advance' => round(($rv * 0.05), 2),
                'percentage' => 5,
                'pv' => $pv,
                'rv' => $rv,
                'order_by' => $purchaser_id,
                'order_id' => $order_id,
                'direct_bonus' => 0,
                'ring' => 2
            );
        }
        if ($ref_id3 == 0) {
            return  $cmsn_users;
        }
        // Ring 3
        if ($this->count_active_partners($ref_id3) >= 3 && $ref_id3 != 0) {
            $cmsn_users[] = array(
                'partner_id' => $ref_id3,
                'commission' => round(($pv * 0.03), 2),
                'rank_advance' => round(($rv * 0.03), 2),
                'percentage' => 3,
                'pv' => $pv,
                'rv' => $rv,
                'order_by' => $purchaser_id,
                'order_id' => $order_id,
                'direct_bonus' => 0,
                'ring' => 3
            );
        }
        if ($ref_id4 == 0) {
            return  $cmsn_users;
        }
        // Ring 4
        if ($this->count_active_partners($ref_id4) >= 4 && $ref_id4 != 0) {
            $cmsn_users[] = array(
                'partner_id' => $ref_id4,
                'commission' => round(($pv * 0.01), 2),
                'rank_advance' => round(($rv * 0.01), 2),
                'percentage' => 1,
                'pv' => $pv,
                'rv' => $rv,
                'order_by' => $purchaser_id,
                'order_id' => $order_id,
                'direct_bonus' => 0,
                'ring' => 4
            );
        }
        if ($ref_id5 == 0) {
            return  $cmsn_users;
        }
        // Ring 5
        if ($this->count_active_partners($ref_id5) >= 5 && $ref_id5 != 0) {
            $cmsn_users[] = array(
                'partner_id' => $ref_id5,
                'commission' => round(($pv * 0.005), 2),
                'rank_advance' => round(($rv * 0.005), 2),
                'percentage' => 0.5,
                'pv' => $pv,
                'rv' => $rv,
                'order_by' => $purchaser_id,
                'order_id' => $order_id,
                'direct_bonus' => 0,
                'ring' => 5
            );
        }
        if ($ref_id6 == 0) {
            return  $cmsn_users;
        }
        // Ring 6
        if ($this->count_active_partners($ref_id6) >= 6 && $ref_id6 != 0) {
            $cmsn_users[] = array(
                'partner_id' => $ref_id6,
                'commission' => round(($pv * 0.005), 2),
                'rank_advance' => round(($rv * 0.005), 2),
                'percentage' => 0.5,
                'pv' => $pv,
                'rv' => $rv,
                'order_by' => $purchaser_id,
                'order_id' => $order_id,
                'direct_bonus' => 0,
                'ring' => 6
            );
        }
        if ($ref_id7 == 0) {
            return  $cmsn_users;
        }
        // Ring 7
        if ($this->count_active_partners($ref_id7) >= 7 && $ref_id7 != 0) {
            $cmsn_users[] = array(
                'partner_id' => $ref_id7,
                'commission' => round(($pv * 0.005), 2),
                'rank_advance' => round(($rv * 0.005), 2),
                'percentage' => 0.5,
                'pv' => $pv,
                'rv' => $rv,
                'order_by' => $purchaser_id,
                'order_id' => $order_id,
                'direct_bonus' => 0,
                'ring' => 7
            );
        }
        if ($ref_id8 == 0) {
            return  $cmsn_users;
        }
        // Ring 8
        if ($this->count_active_partners($ref_id8) >= 8 && $ref_id8 != 0) {
            $cmsn_users[] = array(
                'partner_id' => $ref_id8,
                'commission' => round(($pv * 0.005), 2),
                'rank_advance' => round(($rv * 0.005), 2),
                'percentage' => 0.5,
                'pv' => $pv,
                'rv' => $rv,
                'order_by' => $purchaser_id,
                'order_id' => $order_id,
                'direct_bonus' => 0,
                'ring' => 8
            );
        }
        if ($ref_id9 == 0) {
            return  $cmsn_users;
        }
        // Ring 9
        if ($this->count_active_partners($ref_id9) >= 9 && $ref_id9 != 0) {
            $cmsn_users[] = array(
                'partner_id' => $ref_id9,
                'commission' => round(($pv * 0.005), 2),
                'rank_advance' => round(($rv * 0.005), 2),
                'percentage' => 0.5,
                'pv' => $pv,
                'rv' => $rv,
                'order_by' => $purchaser_id,
                'order_id' => $order_id,
                'direct_bonus' => 0,
                'ring' => 9
            );
        }
        if ($ref_id10 == 0) {
            return  $cmsn_users;
        }
        // Ring 10
        if ($this->count_active_partners($ref_id10) >= 10 && $ref_id10 != 0) {
            $cmsn_users[] = array(
                'partner_id' => $ref_id10,
                'commission' => round(($pv * 0.005), 2),
                'rank_advance' => round(($rv * 0.005), 2),
                'percentage' => 0.5,
                'pv' => $pv,
                'rv' => $rv,
                'order_by' => $purchaser_id,
                'order_id' => $order_id,
                'direct_bonus' => 0,
                'ring' => 10
            );
        }
    }
    function get_partner_id($my_id)
    {
        $my_sql = "SELECT id, ref from pk_user where is_active = 1 and pk_user.id = $my_id";
        $my_arr = $this->db->show($my_sql);
        if (count($my_arr) == 1) {
            return $my_arr[0]['ref'];
        } else {
            return 0;
        }
    }
    function count_active_partners($my_id)
    {
        $ref_sql = "SELECT id from pk_user where is_active = 1 and ref = $my_id";
        $my_partners = $this->db->show($ref_sql);
        $count_active = 0;
        foreach ($my_partners as $id) {
            if ($this->check_active($id['id'])) {
                $count_active += 1;
            }
        }
        return $count_active;
    }
    function check_active($user_id)
    {
        $today = date('Y-m-d H:i:s');
        $sql = "SELECT pv, (33-DATEDIFF('$today', created_at)) as days_left 
        FROM payment 
        WHERE user_id = $user_id 
        AND pv >= 15 
        AND DATEDIFF('$today', created_at) <= 33 
        AND status = 'paid' 
        AND (invoice IS NOT NULL AND invoice <> '')
        ORDER BY created_at DESC
        LIMIT 1;";
        $data = $this->db->show($sql);
        if (count($data)) {
            return true;
        } else {
            return false;
        }
    }
    function check_ever_active($user_id)
    {
        $today = date('Y-m-d H:i:s');
        $sql = "SELECT pv, (DATEDIFF('$today', created_at)) as days_left 
        FROM payment 
        WHERE user_id = $user_id 
        AND pv >= 15
        AND status = 'paid' 
        AND (invoice IS NOT NULL AND invoice <> '')
        ORDER BY created_at DESC
        LIMIT 1;";
        $data = $this->db->show($sql);
        if (count($data)) {
            return true;
        } else {
            return false;
        }
    }
    function save_commissions($purchaser_id, $order_id, $pv, $rv, $direct_bonus)
    {
        $data = $this->ring_commissions($purchaser_id, $order_id, $pv, $rv, $direct_bonus);
        foreach ($data as $ring) {
            $ring = (object) $ring;
            $rcmsn_sql = "SELECT * from ring_commissions where order_id = $order_id and partner_id = $ring->partner_id";
            $my_partners = $this->db->show($rcmsn_sql);
            if (count($my_partners) == 0) {
                $cmsn_insert_sql = "INSERT INTO ring_commissions (order_by, partner_id, ring, order_id, pv, rv, percentage, commission, rank_advance, direct_bonus)  VALUES ($ring->order_by, $ring->partner_id, $ring->ring, $ring->order_id, $ring->pv, $ring->rv, $ring->percentage, $ring->commission, $ring->rank_advance, $ring->direct_bonus)";
                $this->db->show($cmsn_insert_sql);
            }
        }
    }
}
