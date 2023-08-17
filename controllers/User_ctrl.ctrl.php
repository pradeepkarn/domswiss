<?php
class User_ctrl
{
    protected $db;
    public function __construct()
    {
        $this->db = new Dbobjects;
    }
    function store_pv()
    {
        $sql = "SELECT id from pk_user where is_active = 1";
        $data = $this->db->show($sql);
        $count = 0;
        foreach ($data as $key => $u) {
            $am_i_active = $this->am_i_active($u['id'])['active'];
            $pvctrl = new Pv_ctrl;
            $tree = $pvctrl->my_tree($u['id'], 1, $am_i_active);
            $cmsn = $pvctrl->calculate_sum($tree, 1, 10);
            $remark = $am_i_active==true?'active':'inactive';
            $fund_sql = "select id from my_fund where user_id = {$u['id']} AND  date_from = '{$pvctrl->firstDay}' AND  date_to = '{$pvctrl->lastDay}' AND content_group = 'pv'";
            $old_fund = $this->db->show($fund_sql);
            if (count($old_fund)>=1) {
                $of = $old_fund[0];
                $fund_sql = "update my_fund set amt = {$cmsn['pv_sum']} , remark = '$remark' where id = {$of['id']}";
            }else{
                $fund_sql = "insert into my_fund (user_id, amt, date_from, date_to,content_group,remark) values({$u['id']}, {$cmsn['pv_sum']}, '{$pvctrl->firstDay}','{$pvctrl->lastDay}','pv','$remark')";
            }
            $this->db->show($fund_sql);
            $count ++;
        }
        // echo $count . "rows updated";
    }
    function am_i_active($user_id)
    {
        $today = date('Y-m-d H:i:s');
        $sql = "SELECT pv, (33-DATEDIFF('$today', created_at)) as days_left 
        FROM payment 
        WHERE user_id = $user_id 
        AND pv >= 15 
        AND DATEDIFF('$today', created_at) <= 33 
        AND DATEDIFF('$today', created_at) > 0 
        AND status = 'paid' 
        AND (invoice IS NOT NULL AND invoice <> '')
        ORDER BY created_at DESC
        LIMIT 1;";
        $data = $this->db->show($sql);
        if (count($data)) {
            return array('active' => true, 'data' => $data);
        } else {
            return array('active' => false, 'data' => null);
        }
        // myprint($data);
        // echo $this->sql;
    }
}
