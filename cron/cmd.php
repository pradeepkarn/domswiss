<?php
if (!defined("direct_access")) {
    define("direct_access", 1);
}
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../includes/class-autoload.inc.php");
require_once 'vendor/autoload.php';
import('functions.php');


// $db = new Dbobjects;
// $db->tableName = 'pk_user';
// $users = $db->all(limit: 100000);

// foreach ($users as $u) {
//     set_time_limit(60);
//     $active_date = last_active_date($userid=$u['id']);
//     $tree  = my_tree($ref = $u['id'], 1, $active_date);
//     $old_rv = calculatePercentageSum($tree, $depth = 1, $treeLength=1, $userid = $u['id']);
//     $rvsm = $old_rv['rv_sum'];
//     $sql_oldrv = "INSERT INTO `rank_advance` (`id`, `rv`, `added_to`, `added_by`, `status`, `old_rv`) VALUES (NULL, '0', '{$u['id']}', '1', 'confirmed', '$rvsm');";
//     $db->show($sql_oldrv);
// }

// exit;
$ub = new Pv_ctrl;
$ub->db = new Dbobjects;
// $act = $ub->am_i_active(149);
// echo $act['active']==true?$act['data']['id']:'f';
// $data = $ub->ring_commissions(307,455,100);
// $data = $ub->save_commissions(149,4980,100,2500,100);
// echo $ub->count_active_partners(147);
// echo $ub->my_lifetime_commission_sum($my_id=219);
// echo $ub->my_last_month_commission_sum($my_id=219);
// echo $ub->commission_sum_by_date_range();
// $data = $ub->get_partner_id(1);

// print_r($data);
// $tree = $ub->my_tree(1);
// $lifetime = $ub->calculate_sum($tree,1,1);

// print_r($lifetime);
// print_r($tree);
exit;

// $ub->firstDay = "2023-06-01";
// $ub->lastDay = "2023-06-30";
// $ub->store_pv();
// $ub->firstDay = "2023-07-01";
// $ub->lastDay = "2023-07-31";
// $ub->store_pv();
// $ub->firstDay = "2023-08-01";
// $ub->lastDay = "2023-08-31";
// $ub->store_pv();


// echo $ub->my_lifetime_pv(160);
// echo $ub->my_current_month_pv(1);
// echo $ub->my_last_month_pv(1);
return;
