<?php
exit;
if (!defined("direct_access")) {
    define("direct_access", 1);
}
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../includes/class-autoload.inc.php");
require_once 'vendor/autoload.php';
import('functions.php');

exit;
// $db = new Dbobjects;
// $db->tableName = 'pk_user';
// $users = $db->all(limit: 100000);

// foreach ($users as $u) {
//     set_time_limit(120);
//     $active_date = last_active_date($userid=$u['id']);
//     $tree  = my_tree($ref = $u['id'], 1, $active_date);
//     $old_rv = calculatePercentageSum($tree, $depth = 1, $treeLength=1, $userid = $u['id']);
//     $rvsm = $old_rv['rv_sum'];
    
//     $sql_oldrv = "INSERT INTO `old_data` (`key_name`, `key_value`, `user_id`) VALUES ('rank_advance', '$rvsm', {$u['id']});";
//     $db->show($sql_oldrv);

//     $bns = total_bonus($u['id']);
//     $sql_oldrv = "INSERT INTO `old_data` (`key_name`, `key_value`, `user_id`) VALUES ('direct_bonus', '$bns', {$u['id']});";
//     $db->show($sql_oldrv);

//     $sqloldsmsncxhh = "select SUM(amt) as total_amt from credits where user_id = {$userid} and status = 'lifetime'";
//     $cmsn = $db->show($sqloldsmsncxhh);
//     $old_lifetime_pv = isset($cmsn[0]['total_amt']) ? $cmsn[0]['total_amt'] : 0;

//     $bns = total_bonus($u['id']);
//     $sql_oldcmsn = "INSERT INTO `old_data` (`key_name`, `key_value`, `user_id`) VALUES ('commission', '$old_lifetime_pv', {$u['id']});";
//     $db->show($sql_oldcmsn);
// }

exit;