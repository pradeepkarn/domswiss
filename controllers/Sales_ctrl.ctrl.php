<?php
require_once("config.php");
import("includes/class-autoload.inc.php");
$pool_share_divisions = [
    "silver manager" => [
        "pool1" => 1,
        "pool2" => 0,
        "pool3" => 0,
        "pool4" => 0
    ],
    "gold manager" => [
        "pool1" => 2,
        "pool2" => 0,
        "pool3" => 0,
        "pool4" => 0
    ],
    "platinum manager" => [
        "pool1" => 3,
        "pool2" => 0,
        "pool3" => 0,
        "pool4" => 0
    ],
    "director" => [
        "pool1" => 4,
        "pool2" => 1,
        "pool3" => 0,
        "pool4" => 0
    ],
    "team director" => [
        "pool1" => 4,
        "pool2" => 2,
        "pool3" => 0,
        "pool4" => 0
    ],
    "marketing director" => [
        "pool1" => 4,
        "pool2" => 3,
        "pool3" => 0,
        "pool4" => 0
    ],
    "diamond" => [
        "pool1" => 4,
        "pool2" => 5,
        "pool3" => 1,
        "pool4" => 0
    ],
    "blue diamond" => [
        "pool1" => 4,
        "pool2" => 5,
        "pool3" => 2,
        "pool4" => 0
    ],
    "purple diamond" => [
        "pool1" => 4,
        "pool2" => 5,
        "pool3" => 3,
        "pool4" => 0
    ],
    "green diamond" => [
        "pool1" => 4,
        "pool2" => 5,
        "pool3" => 4,
        "pool4" => 1
    ],
    "ambassador" => [
        "pool1" => 4,
        "pool2" => 5,
        "pool3" => 4,
        "pool4" => 2
    ],
    "royal" => [
        "pool1" => 4,
        "pool2" => 5,
        "pool3" => 4,
        "pool4" => 3
    ],
    "royal i" => [
        "pool1" => 4,
        "pool2" => 5,
        "pool3" => 4,
        "pool4" => 4
    ],
    "royal ii" => [
        "pool1" => 4,
        "pool2" => 5,
        "pool3" => 4,
        "pool4" => 5
    ]
];

$members = [
    "silver manager" => 10,
    "gold manager" => 10,
    "platinum manager" => 5,
    "director" => 10,
    "team director" => 15,
    "marketing director" => 20,
    "diamond" => 10,
    "blue diamond" => 10,
    "purple diamond" => 10,
    "green diamond" => 10,
    "ambassador" => 15,
    "royal" => 10,
    "royal i" => 5,
    "royal ii" => 2
];


class Sales_ctrl
{
    public $firstDay;
    public $lastDay;
    public $db;
    function __construct()
    {
        $this->db = new Dbobjects;
        $this->firstDay = date('Y-m-01');
        $this->lastDay = date('Y-m-t');
    }
    function get_sale_volume()
    {
        $db = $this->db;
        $sql = "SELECT SUM(amount) as total_sale FROM payment WHERE `status`= 'paid' AND updated_at IS NOT NULL AND updated_at >= '$this->firstDay' AND updated_at <= '$this->lastDay';";
        try {
            return round(($db->show($sql)[0]['total_sale']), 2);
        } catch (PDOException $th) {
            // echo $th;
            return 0.00;
        }
    }
    function get_pv_volume()
    {
        $db = $this->db;
        $sql = "SELECT SUM(pv) as total_pv FROM payment WHERE `status`= 'paid' AND updated_at IS NOT NULL AND updated_at >= '$this->firstDay' AND updated_at <= '$this->lastDay';";
        try {
            return round(($db->show($sql)[0]['total_pv']), 2);
        } catch (PDOException $th) {
            // echo $th;
            return 0.00;
        }
    }
    function distribute_share_by_pool($total_sale = 0)
    {
        $pool_share_divisions = [
            "silver manager" => [
                "pool1" => 1,
                "pool2" => 0,
                "pool3" => 0,
                "pool4" => 0
            ],
            "gold manager" => [
                "pool1" => 2,
                "pool2" => 0,
                "pool3" => 0,
                "pool4" => 0
            ],
            "platinum manager" => [
                "pool1" => 3,
                "pool2" => 0,
                "pool3" => 0,
                "pool4" => 0
            ],
            "director" => [
                "pool1" => 4,
                "pool2" => 1,
                "pool3" => 0,
                "pool4" => 0
            ],
            "team director" => [
                "pool1" => 4,
                "pool2" => 2,
                "pool3" => 0,
                "pool4" => 0
            ],
            "marketing director" => [
                "pool1" => 4,
                "pool2" => 3,
                "pool3" => 0,
                "pool4" => 0
            ],
            "diamond" => [
                "pool1" => 5,
                "pool2" => 4,
                "pool3" => 1,
                "pool4" => 0
            ],
            "blue diamond" => [
                "pool1" => 5,
                "pool2" => 4,
                "pool3" => 2,
                "pool4" => 0
            ],
            "purple diamond" => [
                "pool1" => 5,
                "pool2" => 4,
                "pool3" => 3,
                "pool4" => 0
            ],
            "green diamond" => [
                "pool1" => 5,
                "pool2" => 5,
                "pool3" => 4,
                "pool4" => 1
            ],
            "ambassador" => [
                "pool1" => 6,
                "pool2" => 5,
                "pool3" => 4,
                "pool4" => 2
            ],
            "royal" => [
                "pool1" => 6,
                "pool2" => 5,
                "pool3" => 4,
                "pool4" => 3
            ],
            "royal i" => [
                "pool1" => 6,
                "pool2" => 5,
                "pool3" => 5,
                "pool4" => 4
            ],
            "royal ii" => [
                "pool1" => 6,
                "pool2" => 6,
                "pool3" => 5,
                "pool4" => 5
            ]
        ];
        $members = [
            "silver manager" => 0,
            "gold manager" => 0,
            "platinum manager" => 0,
            "director" => 0,
            "team director" => 0,
            "marketing director" => 0,
            "diamond" => 0,
            "blue diamond" => 0,
            "purple diamond" => 0,
            "green diamond" => 0,
            "ambassador" => 0,
            "royal" => 0,
            "royal i" => 0,
            "royal ii" => 0
        ];
        $file = RPATH . "/jsondata/pool/members.json";
        if (file_exists($file)) {
            $jsndta = file_get_contents($file);
            $members = json_decode($jsndta, true);
        }
        $share_for_each_pool = round((($total_sale * 0.12) / 4), 2);

        $ts1 = 0;
        $ts2 = 0;
        $ts3 = 0;
        $ts4 = 0;
        foreach ($pool_share_divisions as $sk => $shr) {

            if (array_key_exists($sk, $members)) {
                $ts1 += $members[$sk] * $shr['pool1'];
                $ts2 += $members[$sk] * $shr['pool2'];
                $ts3 += $members[$sk] * $shr['pool3'];
                $ts4 += $members[$sk] * $shr['pool4'];
            }
        }
        $data = array(
            'share_for_each_pool' => $share_for_each_pool,
            'pool1' => array(
                'share_count' => $ts1,
                'unit_value' => $ts1 !== 0 ? round(($share_for_each_pool / $ts1), 2) : 0,
            ),
            'pool2' => array(
                'share_count' => $ts2,
                'unit_value' => $ts2 !== 0 ? round(($share_for_each_pool / $ts2), 2) : 0,
            ),
            'pool3' => array(
                'share_count' => $ts3,
                'unit_value' => $ts3 !== 0 ? round(($share_for_each_pool / $ts3), 2) : 0,
            ),
            'pool4' => array(
                'share_count' => $ts4,
                'unit_value' => $ts4 !== 0 ? round(($share_for_each_pool / $ts4), 2) : 0,
            ),
        );
        return $data;
    }
    function total_partner_sale($my_id)
    {
        $conn = $this->db;
        $sql = "SELECT SUM(amount) as partner_sale
        FROM payment
        WHERE user_id IN (SELECT id FROM pk_user WHERE ref = $my_id);";
        $psale = 0.0;
        try {
            $psale = $conn->showOne($sql)['partner_sale'];
            $psale = $psale ? round($psale, 2) : 0.0;
        } catch (PDOException $e) {
        }
        return $psale;
    }
    function partner_order_list($my_id)
    {
        $arr = [];
        $conn = $this->db;


        $sql = "SELECT p.id as id, p.user_id as partner_id, p.updated_at as invoice_date, co.jsn, co.qty, (select username from pk_user where id = p.user_id) as partner, (select name from item where id = co.item_id) as pkg_name, p.amount
        FROM payment p
        JOIN customer_order co ON p.id = co.payment_id
        WHERE p.status='paid' and p.user_id IN (SELECT id FROM pk_user WHERE ref = $my_id);
        ";
        $data = $conn->show($sql);
        $pvctrl = new Pv_ctrl;
        $pvctrl->db = $conn;
        foreach ($data as $key => $ord) {
            $ord = obj($ord);
            $ord->jsn = json_decode($ord->jsn);
            $ord->rv_sum = $pvctrl->my_all_type_rank_advance_sum($ord->partner_id);
            $ord->position = getPosition($level = $ord->rv_sum);
            $arr[] = $ord;
        }
        return $arr;
    }
    function partner_sale_list($my_id)
    {
        $arr = [];
        $conn = $this->db;
        $sql = "SELECT id as partner_id, username as partner, (select SUM(payment.amount) from payment where status = 'paid' and payment.user_id=pk_user.id) as amount FROM pk_user where ref=$my_id";
        $data = $conn->show($sql);
        $pvctrl = new Pv_ctrl;
        $pvctrl->db = $conn;


        foreach ($data as $key => $ord) {
            $ord = obj($ord);
            $ord->amount = $ord->amount != '' ? round($ord->amount,2) : 0;
            $ord->rv_sum = $pvctrl->my_all_type_rank_advance_sum($ord->partner_id);
            $ord->position = getPosition($level = $ord->rv_sum);
            $arr[] = $ord;
        }
        return $arr;
    }
}
