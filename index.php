<?php
require_once(__DIR__ . "/config.php");
import("/includes/class-autoload.inc.php");
import('/vendor/autoload.php');
$url = explode("/", $_SERVER["QUERY_STRING"]);
$path = $_SERVER["QUERY_STRING"];
define("direct_access", 1);
// $GLOBALS['row_id'] = end($url);
// $GLOBALS['tableName'] = prev($url);
// $GLOBALS['url_last_param'] = end($url);
// $GLOBALS['url_2nd_last_param'] = prev($url);
function get_my_primary_address($userid)
{
  if (!isset($_SESSION['user_id'])) {
    return false;
  }
  $addrs = new Model('address');
  $myaddress_list = $addrs->filter_index(['user_id' => $userid, 'address_type' => 'primary']);
  if (count($myaddress_list) > 0) {
    return (object)($myaddress_list[0]);
  } else {
    return false;
  }
}
function get_invoice_address($country_code = 'CH')
{
  if (!isset($_SESSION['user_id'])) {
    return false;
  }
  $data = new stdClass;
  $data->bank = null;
  $data->office = null;
  $ofcadrs = null;
  $addrs = new Model('countries');
  $myaddress_list = $addrs->filter_index(['code' => $country_code]);
  if (count($myaddress_list) > 0) {
    $ofc = (object)($myaddress_list[0]);
    $data->delv_info = $ofc->delv_info;
    $jsn = json_decode($ofc->jsn);
    $offices = [];
    if (isset($jsn->banks) && count($jsn->banks) > 0) {
      $bank = $jsn->banks[0];
      $data->bank = $bank;
    }
    if (isset($jsn->office_address)) {
      $offices = $jsn->office_address;
      $ofcadrs = $offices[0];
    }
    $data->office = $ofcadrs;

    return $data;
  } else {
    return false;
  }
}

$home = home;

define('URL', $url);
$acnt = new Account;
$acnt = $acnt->getLoggedInAccount();
define('USER', $acnt);
if (USER) {
  $primary_address = get_my_primary_address(USER['id']) ? get_my_primary_address(USER['id'])->country_code : null;
  define('MY_COUNTRY', $primary_address);
} else {
  define('MY_COUNTRY', 'CH');
}

$checkaccess = ['admin', 'subadmin', 'salesman', 'whmanager'];
$lp = null;
import("functions.php");
define('RELOAD', js("location.reload();"));
if (authenticate() == true) {
  $reg_date = USER['created_at'];
  $pmt = new Model('payment');
  $lastpamt = $pmt->filter_index($assoc_arr = ['user_id' => $_SESSION['user_id'], 'status' => 'paid'], $ord = 'desc', $limit = 1, $change_order_by_col = 'created_at');

  $lp = count($lastpamt) > 0 ? $lastpamt[0]['created_at'] : $reg_date;
  // if ($lp != false) {
  //   $lp =  $lp['created_at'];
  // }
  if (isset(USER['user_group'])) {
    $pass = in_array(USER['user_group'], $checkaccess);
    define('PASS', $pass);
  } else {
    $pass = false;
    define('PASS', $pass);
  }
} else {
  $pass = false;
  define('PASS', $pass);
}

define('LAST_PMT', $lp);

// Login via cookie
if (isset($_COOKIE['remember_token'])) {
  $acc = new Account;
  $acc->loginWithCookie($_COOKIE['remember_token']);
}
//login via cookie ends

switch ($path) {
  case '':
    if (isset($_POST['submit'])) {
      // myprint($_POST);
      // $adrObj = new Model('address');
      // $arr = null;
      // $arr['user_id'] = $_POST['user_id'];
      // $arr['address_type'] = $_POST['address_type'];
      // $arr['mobile'] = $_POST['mobile'];
      // $arr['city'] = $_POST['city'];
      // $id = $adrObj->store($arr);
      // if (intval($id)) {
      //     echo "Data saved";
      // }
    }
    if (authenticate() == false) {
      import("apps/view/pages/login.php");
      return;
    }
    import("apps/view/pages/home.php");
    break;
  case 'logout':
    if (authenticate() == true) {
      setcookie("remember_token", "", time() - (86400 * 30 * 12), "/"); // 86400 = 1 day
      // Finally, destroy the session.
      if (session_status() !== PHP_SESSION_NONE) {
        session_destroy();
      }
    }
    if (isset($_COOKIE['remember_token'])) {
      unset($_COOKIE['remember_token']);
    }
    header("Location:/" . home);
    break;
  default:

    if ($url[0] == "send-de-activation-warning") {
      set_time_limit(1200);
      $db = new Dbobjects;
      $db->tableName = 'pk_user';
      $users = $db->all(limit: 10);
      $uobj = new Dbobjects;
      $check_date = date('Y-m-d H:i:s');
      foreach ($users as $u) {
        $dasyleft = days_left(last_active_date($u['id']));
        if ($dasyleft <= 3 && $dasyleft >= 0) {
          $sql = "select email from warning_emails where email = '{$u['email']}' AND DATEDIFF(created_at, '$check_date') <= 15";
          $alrady = $uobj->show($sql);
          if (count($alrady) == 0) {
            de_activation_warning_email(obj([
              'email' => $u['email']
            ]));
          }
        }
      }
      return;
    }
    // if ($url[0] == 'pv-calculate') {
      
    // }
    if ($url[0] == "pool-calculate") {
      // return;
      set_time_limit(600);
      // if(!is_superuser()){
      //   die();
      // }
      $db = new Dbobjects;
      $db->tableName = 'pk_user';
      $users = $db->all(limit: 100000);
      return;
      $dms = new Domswiss_tree_ctrl;
      // Assuming $members is the original array you provided
      $members = [
        "affiliate partner" => 0,
        "bronze manager" => 0,
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

      // Your foreach loop
      foreach ($users as $u) {
        $member = $dms->handle_rv($user_id = $u['id']);
        // Increment the count for the corresponding member
        $members[$member]++;
      }

      $date = date('Y-m-d');
      $jsonData = json_encode($members, JSON_PRETTY_PRINT);
      $file = RPATH . "/jsondata/pool/members.json";
      file_put_contents($file, $jsonData);
      return;
    }
    if ($url[0] == "share-calculate-863hjkdf68yeiuiuvv87e4687") {
      set_time_limit(600);
      // if(!is_superuser()){
      //   die();
      // }
      $db = new Dbobjects;
      $db->tableName = 'pk_user';
      $users = $db->all(limit: 100000);
      $db->tableName = 'credits';
      $monthend = date('Y-m-t');
      // $monthend = '2023-07-31';
      if ($monthend == date('Y-m-d')) {
        foreach ($users as $u) {
          $arr = null;
          $shr_amt = my_all_share($u['id']);
          $shr_cnt = my_all_share_count($u['id']);
          $arr['user_id'] = $u['id'];
          $arr['status'] = 'share';
          $arr['month_end'] = $monthend;
          $oldshre = $db->filter($arr);
          $arr['share_count'] = $shr_cnt;
          $arr['amt'] = $shr_amt;
          $db->insertData = $arr;
          if (count($oldshre) == 0) {
            $db->create();
          } else {
            $db->update();
          }
        }
      }
      return;
    }
    if ($url[0] == "contact") {
      import("apps/view/pages/contact.php");
      return;
    }
    if ($url[0] == "product-info-ajax") {
      $obj = new Model('item');
      $data = obj($obj->show($_POST['product_info']));
      $var = <<<PARA
                <p>$data->details</p>
              PARA;
      echo $var;
      return;
    }
    if ($url[0] == "privacy-policy") {
      import("apps/view/pages/privacy-policy.php");
      return;
    }
    if ($url[0] == "terms-and-conditions") {
      import("apps/view/pages/terms-and-conditions.php");
      return;
    }

    if ($url[0] == "qualifications") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/qualifications.php");
      return;
    }
    if ($url[0] == "all-users") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      $context = null;
      if (isset($_GET['page']) || isset($_GET['q'])) {
        $context['data'] = getPage($_GET);
      }
      import("apps/view/pages/all-users.php", $context);
      return;
    }
    if ($url[0] == "all-commissions") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      $context = null;
      if (isset($_GET['page']) || isset($_GET['q'])) {
        $context['data'] = getCommissions($_GET, $data_limit = 5);
      }
      import("apps/view/pages/all-commissions.php", $context);
      return;
    }
    if ($url[0] == "my-commissions") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      $context = null;
      if (isset($_GET['page']) || isset($_GET['q'])) {
        // $req = new stdClass;
        $req = obj($_GET);
        $req->my_id = USER['id'];
        $context['data'] = getMyCommissions($req, $data_limit = 5);
      }
      import("apps/view/pages/my-commissions.php", $context);
      return;
    }
    if ($url[0] == "genology") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/genology.php");
      return;
    }
    if ($url[0] == "withdrawal-requests") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      if (isset($_GET['tid'])) {
        import("apps/view/pages/wallet-transaction-detail.php");
        return;
      }
      import("apps/view/pages/wallet-transactions.php");
      return;
    }
    if ($url[0] == "structure-tree") {
      if (!authenticate()) {
        header("Location: /$home/");
        exit;
      }
      import("apps/view/pages/structure-tree.php");
      return;
    }
    if ($url[0] == "invite") {
      import("apps/view/pages/invite.php");
      return;
    }
    if ($url[0] == "invite-ajax") {

      if (usersignup()) {

        msg_ssn();
      } else {
        msg_ssn();
      }
      return;
    }
    if ($url[0] == "partners") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/partners.php");

      return;
    }
    if ($url[0] == "customers") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/my-customers.php");
      return;
    }
    if ($url[0] == "statistics") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/statistics.php");
      return;
    }
    // if ($url[0] == "earnings") {
    //   if (!authenticate()) {
    //     header("Location: /$home/");
    //     exit;
    //   }
    //   import("apps/view/pages/earnings.php");
    //   return;
    // }
    if ($url[0] == "orders") {
      if (!authenticate()) {
        header("Location: /$home/");
        exit;
      }
      import("apps/view/pages/orders.php");
      return;
    }
    if ($url[0] == "downline") {
      if (!is_superuser()) {
        header("Location: /$home/");
        exit;
      }
      import("apps/view/pages/downline.php");
      return;
    }
    if ($url[0] == "shop") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/shop.php");
      return;
    }

    if ($url[0] == "update-address-ajax") {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }
      if (isset($_POST['id'])) {
        // myprint($_POST);
        // return;
        $myobj = new Dbobjects;
        $myobj->tableName = "address";
        $id = $_POST['id'];
        $myobj->pk($id);
        $myobj->insertData['name'] = $_POST['person_name'];
        $myobj->insertData['state'] = $_POST['state'];
        $myobj->insertData['address_name'] = $_POST['address_name'];
        if (isset($_POST['country_code'])) {
          $myobj->insertData['country_code'] = $_POST['country_code'];
          $country = getCountryData($countryCode = $_POST['country_code']);
          $myobj->insertData['country'] = $country->name;
        }
        $myobj->insertData['street'] = isset($_POST['street']) ? ($_POST['street']) : null;
        $myobj->insertData['city'] = $_POST['city'];
        $myobj->insertData['zipcode'] = $_POST['zipcode'];
        $myobj->insertData['isd_code'] = $_POST['isd_code'];
        $myobj->insertData['mobile'] = $_POST['mobile'];
        // myprint($data);
        $myobj->update();
        echo js_alert('Updated');
        echo RELOAD;
      }
      exit;
    }
    // update personal data
    if ($url[0] == 'update-personal-data') {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }
      $uobj = new Dbobjects;
      $uobj->tableName = "pk_user";
      $id = USER['id'];
      $uobj->pk($id);
      if (isset($_POST['first_name'])) {
        $uobj->insertData['first_name'] = $_POST['first_name'];
      }
      if (isset($_POST['last_name'])) {
        $uobj->insertData['last_name'] = $_POST['last_name'];
      }
      if (isset($_POST['city'])) {
        $uobj->insertData['city'] = $_POST['city'];
      }
      if (isset($_POST['state'])) {
        $uobj->insertData['state'] = $_POST['state'];
      }
      if (isset($_POST['zipcode'])) {
        $uobj->insertData['zipcode'] = $_POST['zipcode'];
      }
      if (isset($_POST['country_code'])) {
        $uobj->insertData['country_code'] = $_POST['country_code'];
        $country = getCountryData($countryCode = $_POST['country_code']);
        $uobj->insertData['country'] = $country->name;
      }
      if (count($uobj->insertData) > 0) {
        $uobj->update();
      }
      echo RELOAD;
      return;
    }
    if ($url[0] == "primary-address-ajax") {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }
      if (isset($_POST['pr_address']) && intval($_POST['pr_address'])) {
        $adr = new Dbobjects;
        $adr->tableName = "address";
        $adr->insertData = null;
        $adr->filter(['user_id' => $_SESSION['user_id']]);
        $adr->insertData['address_type'] = "";
        $adr->update();
        $adr = new Dbobjects;
        $adr->tableName = "address";
        $adr->pk($_POST['pr_address']);
        $adr->insertData = null;
        $adr->insertData['address_type'] = 'primary';
        $adr->update();
        echo js_alert('Updated');
        echo RELOAD;
      }
      exit;
    }
    if ($url[0] == "make-this-address-primary-ajax") {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }
      if (isset($_POST['pr_address']) && intval($_POST['pr_address'])) {
        $adr = new Dbobjects;
        $adr->tableName = "address";
        $adr->insertData = null;
        $adr->filter(['user_id' => $_SESSION['user_id']]);
        $adr->insertData['address_type'] = "";
        $adr->update();
        $adr = new Dbobjects;
        $adr->tableName = "address";
        $adr->pk($_POST['pr_address']);
        $adr->insertData = null;
        $adr->insertData['address_type'] = 'primary';
        $adr->update();
        echo RELOAD;
        echo go_to("products");
      }
      exit;
    }
    if ($url[0] == "new-address-ajax") {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }
      $arr = null;
      // myprint($_POST);
      // return;
      $itemProduct = new Model('address');
      $arr['user_id'] = $_SESSION['user_id'];
      $arr['name'] = $_POST['ad_name'];
      $arr['address_name'] = $_POST['address'];
      $arr['isd_code'] = $_POST['country_code'];
      $arr['mobile'] = $_POST['mobile'];
      $arr['city'] = $_POST['city'];
      $arr['street'] = isset($_POST['street']) ? ($_POST['street']) : null;
      $arr['state'] = $_POST['state'];
      // $arr['country'] = $_POST['country'];
      if (isset($_POST['country'])) {
        $arr['country_code'] = $_POST['country'];
        $country = getCountryData($countryCode = $_POST['country']);
        $arr['country'] = $country->name;
      }
      $arr['zipcode'] = $_POST['zipcode'];
      $arr['address_type'] = '';

      $id = $itemProduct->store($arr);
      if (intval($id)) {
        echo js_alert('Address added successfully');
        echo RELOAD;
        return;
      }
    }
    if ($url[0] == "products") {
      if (!authenticate()) {
        header("Location: /$home/");
        exit;
      }
      import("apps/view/pages/products.php");
      return;
    }
    if ($url[0] == "credits") {
      if (!is_superuser()) {
        header("Location: /$home/");
        exit;
      }
      import("apps/view/pages/credits.php");
      return;
    }
    if ($url[0] == "user-credits") {
      if (!is_superuser()) {
        header("Location: /$home/");
        exit;
      }
      import("apps/view/pages/user-credits.php");
      return;
    }
    if ($url[0] == "edit-user") {
      if (!is_superuser()) {
        header("Location: /$home/");
        exit;
      }
      if (!isset($_GET['userid'])) {
        header("Location: /$home/all-users");
        exit;
      }
      if (!intval($_GET['userid'])) {
        header("Location: /$home/all-users");
        exit;
      }
      $user = getData('pk_user', $_GET['userid']);
      $context['user'] = $user;
      import("apps/view/pages/edit-user.php", $context);
      return;
    }
    if ($url[0] == "update-profile-by-admin-ajax") {
      if (!is_superuser()) {
        $_SESSION['msg'][] = 'You are not an admin to perform this action';
        echo js_alert(msg_ssn(return: true));
        exit;
      }
      updateUserDetails($data = $_POST);
      echo js_alert(msg_ssn(return: true));
      echo RELOAD;
      return;
    }
    if ($url[0] == "save-bank-account-ajax") {
      if (!authenticate()) {
        $_SESSION['msg'][] = 'You need to be logged in to perform this action';
        echo js_alert(msg_ssn(return: true));
        exit;
      }
      if (isset($_POST['bank_account']) && isset($_POST['country']) && $_POST['country'] != '') {
        $cntry = getCountryData($_POST['country']);
        // $dta = getCurrency($_POST['country']);
        // myprint($dta['flag']);
        $sjnphp = [
          'banks' => [
            [
              'bank_account' => $_POST['bank_account'],
              'bank_name' => $_POST['bank_name'],
              'iban' => $_POST['iban'],
              'swift_code' => $_POST['swift_code'],
              'country_name' => $cntry->name,
              'country_code' => $cntry->code
            ]
          ]
        ];
        $jsn = json_encode($sjnphp);
        $db = new Model('pk_user');
        $db->update(USER['id'], ['jsn' => $jsn]);
        try {
          $db->update(USER['id'], ['jsn' => $jsn]);
          $_SESSION['msg'][] = 'Bank account updated';
          echo js_alert(msg_ssn(return: true));
          exit;
        } catch (PDOException $th) {
          $_SESSION['msg'][] = 'Bank account not updated';
          echo js_alert(msg_ssn(return: true));
          exit;
        }
      } else {
        $_SESSION['msg'][] = 'Bank account with country is required';
        echo js_alert(msg_ssn(return: true));
        exit;
      }

      // echo RELOAD;
      return;
    }
    if ($url[0] == "tickets") {
      if (!is_superuser()) {
        header("Location: /$home/");
        exit;
      }
      import("apps/view/pages/tickets.php");
      return;
    }
    if ($url[0] == "profile") {
      import("apps/view/pages/profile.php");
      return;
    }
    if ($url[0] == "checkout") {
      if (authenticate() == false) {
        import("apps/view/pages/login.php");
        return;
      }
      import("apps/view/pages/checkout.php");
      return;
    }
    if ($url[0] == "payment") {
      if (!authenticate()) {
        header("Location: /$home/");
        exit;
      }
      import("apps/view/pages/payment.php");
      return;
    }
    if ($url[0] == "commissions") {
      if (!authenticate()) {
        header("Location: /$home/");
        exit;
      }
      import("apps/view/pages/commissions.php");
      return;
    }
    if ($url[0] == "shopping-link") {
      if (authenticate() == false) {
        import("apps/view/pages/login.php");
        return;
      }
      import("apps/view/pages/shopping-link.php");
      return;
    }
    if ($url[0] == "kyc-upload") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/kyc-upload.php");
      return;
    }
    if ($url[0] == "signup") {
      import("apps/view/pages/signup.php");
      return;
    }
    if ($url[0] == "all-orders") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/all-orders.php");
      return;
    }
    if ($url[0] == "my-orders") {
      // if (authenticate() == false) {
      //   header("location:/$home/login");
      //   return;
      // }
      if (!isset($_GET['orderid'])) {
        header("location:/$home/orders");
        return;
      }
      // $orders = getData('payment',$_GET['orderid']);
      $orddata = get_order_details($orderid = $_GET['orderid']);
      // myprint($orddata);
      import("apps/view/pages/invoice-print.php", $orddata);
      return;
    }
    if ($url[0] == "label-print") {
      if (authenticate() == false) {
        header("location:/$home/login");
        return;
      }
      if (!isset($_GET['orderid'])) {
        header("location:/$home/orders");
        return;
      }
      // $orders = getData('payment',$_GET['orderid']);
      $orddata = get_order_details($orderid = $_GET['orderid']);
      // myprint($orddata);
      import("apps/view/pages/label-print.php", $orddata);
      return;
    }
    if ($url[0] == "money-withdraw") {
      if (authenticate() == false) {
        $_SESSION['msg'][] = "You are not logged in";
        exit;
      }
      if (!isset($_POST['user'])) {
        $_SESSION['msg'][] = "Invalid user id";
        exit;
      }
      $usr = new User_ctrl;
      $im_act = $usr->am_i_active($_POST['user'])['active'];
      if ($im_act!=true) {
        $_SESSION['msg'][] = "Your account is deactive, please purchase any product to activate the account";
        exit;
      }
      $payee = getData('pk_user', $_POST['user']);
      $bank_account = null;
      if ($payee) {
        $payee = obj($payee);
        $bank_account = null;
        $country_code = null;
        $jsnob = $payee->jsn;
        $jsn = json_decode($jsnob);
        // if (isset($jsn->banks)) {
        //   $bank = $jsn->banks[0];
        // }

        if (isset($jsn->banks)) {
          $bank_account = isset($jsn->banks[0]->bank_account) ? $jsn->banks[0]->bank_account : null;
          $country_code = isset($jsn->banks[0]->country_code) ? $jsn->banks[0]->country_code : null;
        }
      }
      if ($bank_account == null || $country_code == null) {
        $_SESSION['msg'][] = "Please update your bank account with bank country in profile section";
        echo js_alert(msg_ssn(return: true));
        // echo go_to("/profile");
        return false;
      }




      $udata = obj((new User_ctrl)->my_all_commission($payee->id));
      $cmsn_gt = $udata->cmsn_gt;
      $total_paid = $udata->total_paid;
      $total_unpaid = $udata->total_unpaid;
   
      $lifetime_m = $cmsn_gt;


      // $shr = my_all_share($userid = $_POST['user']);
      # find total life time amt
      $dbmny = new Dbobjects;
  
      // $old_lifetime_pv = old_data($key_name="commission",$_POST['user']);
      // $pvctrl = new Pv_ctrl;
      // $pv_sum = $pvctrl->my_lifetime_commission_sum($_POST['user']);
      // $lifetime_m = $pv_sum + $old_lifetime_pv;
      ##############################################

      // $direct_m = old_data($key_name="direct_bonus",$_POST['user']);
      // $direct_bonus += $pvctrl->my_lifetime_direct_bonus_sum($_POST['user']);
      // $lifetime_m =  $lifetime_m + $direct_m + $shr + $direct_bonus;
      // # find total paid amt
      $sql = "select SUM(amt) as total_amt from credits where user_id = {$_POST['user']} and status = 'paid' and remark='requested'";
      $cmsn_requested = $dbmny->showOne($sql)['total_amt'];
      $cmsn_requested = $cmsn_requested?$cmsn_requested:0;
      // $total_paid = $cmsn[0]['total_amt'] ? $cmsn[0]['total_amt'] : 0;
      $amntwd = abs($_POST['money_out']);
      if ($amntwd < 10) {
        $_SESSION['msg'][] = "Minium amount is 10";
        echo js_alert(msg_ssn(return: true));
        return false;
      }
      if (($lifetime_m - ($total_paid+$cmsn_requested)) >= $amntwd) {
        // js_alert($lifetime_m);
        $wdobj = new Model('credits');
        $wd_arr['user_id'] = $_POST['user'];
        $wd_arr['status'] = 'paid';
        $wd_arr['amt'] = $amntwd;
        $saved =  $wdobj->store($wd_arr);
        if (intval($saved)) {
          $_SESSION['msg'][] = "withdrew request submitted successfully";
        } else {
          $_SESSION['msg'][] = "Request not submitted";
        }
      } else {
        $_SESSION['msg'][] = "Amount must not be greater than to your total unpaid/requested amount, also amount must not be negative";
      }
      echo js_alert(msg_ssn(return: true));
      echo RELOAD;
      return;
    }
    if ($url[0] == "delete-product") {
      if (is_superuser() == false) {
        exit;
      }
      $product = new Model('item');
      $product->destroy($_POST['delpid']);
      echo go_to("/list-all-products");
      return;
    }
    if ($url[0] == "delete-package") {
      if (is_superuser() == false) {
        exit;
      }
      $product = new Model('item');
      $product->destroy($_POST['delpid']);
      echo go_to("/list-all-packages");
      return;
    }
    if ($url[0] == "forgot-password") {
      if (isset($_POST['reset_my_account_pass'])) {
        // print_r($_POST);
        $db = new Mydb('pk_user');
        $checkemail = $db->filterData(['email' => $_POST['email']]);
        if (count($checkemail) > 0) {
          $home = home;
          $token = bin2hex(random_bytes(32));
          $to      = $_POST['email'];
          $subject = 'Password reset';
          $link = "Go to reset password: <a href='https:/{$home}/reset-account/?token={$token}&email={$_POST['email']}'>Reset</a>";
          $message = <<<MSG
            <!DOCTYPE html>
            <html lang="en">
            <head>
                
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Password Reset</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
            </head>
            <body>
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h1 class="text-end">Reset your password</h1>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-12">
                        <p class="text-center">{$link}</p>
                    </div>
                </div>
                </div>
            </body>
            </html>
            MSG;

          $headers  = 'MIME-Version: 1.0' . "\r\n";
          $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
          $headers .= 'From: ' . email . "\r\n";
          $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
          if (mail($to, $subject, $message, $headers)) {
            $arr = null;
            $arr['remember_token'] = $token;
            $db->updateData($arr);
            $_SESSION['msg'][] = "A password reset link sent to your registered email: {$_POST['email']}, please check in spam folder in case of not found in inbox.";
          } else {
            $_SESSION['msg'][] = "Failure: Email was not sent!";
          }
        } else {
          $_SESSION['msg'][] = "Wrong Email";
        }
      }
      import("apps/view/pages/forgot-password.php");
      return;
    }
    if ($url[0] == "signup-ajax") {
      // myprint($_POST);
      // return;
      $userid = usersignup();
      if (intval($userid)) {
        createAddess($userid = $userid, $post = $_POST);
        $user = (object)(getData('pk_user', $userid));
        $obj = new stdClass;
        $obj->partner_email = null;
        if (intval($user->ref) > 0) {
          $referby = (object)(getData('pk_user', $user->ref));
          $obj->partner_email = $referby->email;
        }
        $obj->email = $_POST['email'];
        $obj->username = $user->username;
        $obj->first_name = $user->first_name;
        $obj->last_name = $user->last_name;
        $obj->password = $_POST['password'];
        // Send signup email template
        send_sign_up_email($obj);
        // template end
        echo js_alert(msg_ssn(return: true));
        echo go_to("login");
        return;
      } else {
        msg_ssn();
        return;
      }
      exit;
    }
    if ($url[0] == "change-password-ajax") {
      if (!authenticate()) {
        $_SESSION['msg'][] = "You are not logged in";
        echo js_alert(msg_ssn(return: true));
        exit;
      }

      // myprint($_POST);
      $oldpass = USER['password'];
      if (isset($_POST['old_pass']) && isset($_POST['new_pass']) && isset($_POST['cnf_pass'])) {
        if ($oldpass != md5($_POST['old_pass'])) {
          $_SESSION['msg'][] = "Old password wrong";
          echo js_alert(msg_ssn(return: true));
          exit;
        }
        if ($_POST['new_pass'] == "" || $_POST['cnf_pass'] == "") {
          $_SESSION['msg'][] = "Password must not be empty";
          echo js_alert(msg_ssn(return: true));
          exit;
        }
        if ($_POST['new_pass'] == $_POST['cnf_pass']) {
          $usrobj = new Model('pk_user');
          $reply  = $usrobj->update(USER['id'], array('password' => md5($_POST['cnf_pass'])));
          if ($reply) {
            $_SESSION['msg'][] = "Password changed";
          } else {
            $_SESSION['msg'][] = "Something went wrong";
          }
          echo js_alert(msg_ssn(return: true));
          echo RELOAD;
          exit;
        } else {
          $_SESSION['msg'][] = "New password and confirm password must be same";
          echo js_alert(msg_ssn(return: true));
          exit;
        }
      } else {
        $_SESSION['msg'][] = "All fields are mandatory";
        echo js_alert(msg_ssn(return: true));
        exit;
      }

      exit;
    }
    if ($url[0] == "login") {
      if (authenticate() == true) {
        header("location:/$home");
        return;
      }
      import("apps/view/pages/login.php");
      return;
    }
    if ($url[0] == "login-ajax") {

      // echo go_to("index");
      if (login()) {
        msg_ssn();
        echo go_to("");
        return;
      } else {
        msg_ssn();
      }
      return;
    }

    if ($url[0] == 'add-to-cart-ajax') {
      $cart = new Cart_ctrl;
      $cart->add_or_remove($action = 'add');
      echo RELOAD;
      exit;
    }
    if ($url[0] == 'remove-from-cart-ajax') {
      $cart = new Cart_ctrl;
      $cart->add_or_remove($action = 'remove');
      echo RELOAD;
      exit;
    }
    if ($url[0] == "send-add-item-ajax") {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }
      // myprint($_POST);
      $arr = null;
      $itemObj = new Model('item');
      $item = (object) $itemObj->show($_POST['item_id']);
      $arr['item_id'] = $item->id;
      $arr['price'] = $_POST['price'];
      $arr['qty'] = $_POST['qty'];
      if ($arr['qty'] < 0 || $arr['price'] < 0) {
        echo js_alert('Price and quantity must be greater than zero(0)');
        return;
      }
      if ($arr['qty'] < 1) {
        echo js_alert('Quantity must be greater than or equals to 1');
        return;
      }
      $arr['tax'] = $item->tax;
      $arr['user_id'] = $_SESSION['user_id'];
      $arr['refered_by'] = $_SESSION['user_id'];
      $arr['payment_id'] = 0;
      $arr['status'] = "cart";
      $arr['pv'] = $_POST['pv'];
      $new_my_order_id = (new Model('customer_order'))->store($arr);
      if (intval($new_my_order_id) && $new_my_order_id > 0) {
        echo js_alert('Item added successfully');
        // myprint($new_my_order_id);
        echo RELOAD;
        return;
      } else {
        echo js_alert('Item not added');
        return;
      }
    }


    if ($url[0] == "purchase-add-item-ajax") {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }
      // myprint($_POST);
      $arr = null;
      $itemObj = new Model('item');
      $item = (object) $itemObj->show($_POST['item_id']);
      $arr['item_id'] = $item->id;
      $arr['price'] = $_POST['price'];
      $arr['qty'] = $_POST['qty'];
      if ($arr['qty'] < 0 || $arr['price'] < 0) {
        echo js_alert('Price and quantity must be greater than zero(0)');
        return;
      }
      if ($arr['qty'] < 1) {
        echo js_alert('Quantity must be greater than or equals to 1');
        return;
      }
      $arr['tax'] = $item->tax;
      $arr['user_id'] = $_POST['userid'];
      $arr['refered_by'] = $_SESSION['user_id'];
      $arr['payment_id'] = 0;
      $arr['status'] = "cart";
      $new_my_order_id = (new Model('customer_order'))->store($arr);
      if (intval($new_my_order_id) && $new_my_order_id > 0) {
        echo js_alert('Item added successfully');
        echo RELOAD;
        return;
      } else {
        echo js_alert('Item not added');
        return;
      }
    }
    if ($url[0] == "purchase-decrease-qty-ajax") {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }

      $cart = new Cart_ctrl;
      $cart->add_or_remove($action = 'remove');
      echo RELOAD;
      exit;



      // $data = (object) ($_POST);
      // $id = $data->cart_id;
      // $qty = $data->qty - 1;

      // $arr = null;
      // $arr['qty'] = $qty;
      // $arr['price'] = $data->price;
      // $cart = new Model('customer_order');
      // if ($qty == 0 || $qty < 0) {
      //   $cart->destroy($id, $arr);
      //   echo RELOAD;
      //   return;
      // }
      // $cart->update($id, $arr);
      // echo RELOAD;
      // return;
    }
    if ($url[0] == 'mark-this-order-status-ajax') {
      if (!is_superuser()) {
        echo js_alert('Sorry, You are not an admin');
        exit;
      }
      // myprint($_POST);
      $ord = new Order_ctrl;
      $dataObj = new stdClass;
      $dataObj->info = $_POST['info'];
      $rpl = $ord->confirm_order_status($id = $_POST['pmt_id'], $dataObj);
      if ($rpl) {
        echo RELOAD;
        exit;
      } else {
        echo js_alert('Order status not changed');
        exit;
      }
    }
    if ($url[0] == 'delete-this-order-ajax') {
      if (!is_superuser()) {
        echo js_alert('Sorry, You are not an admin');
        exit;
      }
      if (!isset($_POST['dlt_id'])) {
        echo js_alert('Invalid order number');
        exit;
      }
      $ord = new Order_ctrl;
      $dataObj = new stdClass;
      $rpl = $ord->delet_order_and_cart($id = $_POST['dlt_id']);
      if ($rpl) {
        echo js_alert(msg_ssn(return:true));
        echo RELOAD;
        exit;
      } else {
        echo js_alert(msg_ssn(return:true));
        exit;
      }
      return;
    }
    if ($url[0] == 'mark-this-request-as-confirmed-ajax') {
      if (!is_superuser()) {
        echo js_alert('Sorry, You are not an admin');
        exit;
      }
      // myprint($_POST);
      $ord = new Credit_ctrl;
      $dataObj = new stdClass;
      $dataObj->info = $_POST['info'];
      $rpl = $ord->confirm_request($id = $_POST['credit_id'], $dataObj);
      if ($rpl) {
        echo RELOAD;
        exit;
      } else {
        echo js_alert('Order status not changed');
        exit;
      }
    }
    if ($url[0] == 'mark-this-request-as-cancelled-ajax') {
      if (!is_superuser()) {
        echo js_alert('Sorry, You are not an admin');
        exit;
      }
      // myprint($_POST);
      $ord = new Credit_ctrl;
      $dataObj = new stdClass;
      $dataObj->info = $_POST['info'];
      $rpl = $ord->cancel_request($id = $_POST['credit_id'], $dataObj);
      if ($rpl) {
        echo js_alert('Request was cancelled');
        echo RELOAD;
        exit;
      } else {
        echo js_alert('Status not changed');
        exit;
      }
    }
    if ($url[0] == "purchase-increase-qty-ajax") {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }

      $cart = new Cart_ctrl;
      $cart->add_or_remove($action = 'add');
      echo RELOAD;
      exit;


      // $data = (object) ($_POST);
      // $id = $data->cart_id;
      // $qty = $data->qty + 1;
      // $arr = null;
      // $arr['qty'] = $qty;
      // $arr['price'] = $data->price;
      // $cart = new Model('customer_order');
      // if ($qty == 0 || $qty < 0) {
      //   $cart->destroy($id);
      //   echo RELOAD;
      //   return;
      // }
      // echo RELOAD;
      // $cart->update($id, $arr);
      // return;
    }
    if ($url[0] == "add-products") {
      if (!is_superuser()) {
        header("Location: /$home/");
        exit;
      }
      import("apps/view/pages/add-products.php");
      return;
    }
    if ($url[0] == "upload-product-ajax") {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }
      // myprint($_POST);
      $arr = null;
      $itemProduct = new Model('item');
      $arr['name'] = $_POST['product_name'];
      $arr['stock_qty'] = $_POST['product_qty'];
      $arr['price'] = $_POST['product_price'];
      $arr['tax'] = $_POST['product_tax'];
      // For Image
      $product_image = $_FILES['product_image']['name'];
      $temp_image = $_FILES['product_image']['tmp_name'];
      $arr['image'] = $product_image;
      move_uploaded_file($temp_image, RPATH . "/media/upload/$product_image");


      $id = $itemProduct->store($arr);
      if (intval($id)) {
        echo js_alert('Product added successfully');
        echo RELOAD;
        return;
      }
    }
    ################# Create New Product #######################
    // List all categories
    if ($url[0] == "list-all-categories") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/categories/list.php");
      return;
    }
    // create category
    if ($url[0] == "create-category") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/categories/create.php");
      return;
    }
    if ($url[0] == "create-category-ajax") {
      if (is_superuser() == false) {
        echo js_alert('Please login as superuser');
        return;
      }
      $prodctrl = new Category_ctrl;
      $prodctrl->save();
      echo js_alert(msg_ssn(return: true));
      // echo RELOAD;
      exit;
    }
    if ($url[0] == "edit-category") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/categories/edit.php");
      return;
    }
    if ($url[0] == "update-category-ajax") {
      if (is_superuser() == false) {
        echo js_alert('Please login as superuser');
        return;
      }
      $prodctrl = new Category_ctrl;
      $prodctrl->update();
      echo js_alert(msg_ssn(return: true));
      // echo RELOAD;
      exit;
    }
    // all products
    if ($url[0] == "list-all-products") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/products/list.php");
      return;
    }
    if ($url[0] == "edit-product") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/products/edit.php");
      return;
    }
    if ($url[0] == "update-product-ajax") {
      if (is_superuser() == false) {
        echo js_alert('Please login as superuser');
        return;
      }
      $prodctrl = new Product_ctrl;
      $prodctrl->update();
      echo js_alert(msg_ssn(return: true));
      // echo RELOAD;
      exit;
    }
    if ($url[0] == "create-product") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/products/create.php");
      return;
    }
    if ($url[0] == "list-all-packages") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/packages/list.php");
      return;
    }
    if ($url[0] == "edit-package") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/packages/edit.php");
      return;
    }
    if ($url[0] == "update-package-ajax") {
      if (is_superuser() == false) {
        echo js_alert('Please login as superuser');
        return;
      }
      $prodctrl = new Package_ctrl;
      $prodctrl->update();
      echo js_alert(msg_ssn(return: true));
      // echo RELOAD;
      exit;
    }
    if ($url[0] == "create-product-ajax") {
      if (is_superuser() == false) {
        echo js_alert('Please login as superuser');
        return;
      }
      $prodctrl = new Product_ctrl;
      $prodctrl->save();
      echo js_alert(msg_ssn(return: true));
      // echo RELOAD;
      exit;
    }
    if ($url[0] == "create-package") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/packages/create.php");
      return;
    }
    if ($url[0] == "create-package-ajax") {
      if (is_superuser() == false) {
        echo js_alert('Please login as superuser');
        return;
      }
      $pckagectrl = new Package_ctrl;
      $pckagectrl->save();
      // myprint($_POST);
      echo js_alert(msg_ssn(return: true));
      // echo RELOAD;
      exit;
    }
    ################# Countries #######################
    // List all countries
    if ($url[0] == "list-all-countries") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/countries/list.php");
      return;
    }
    if ($url[0] == "edit-country") {
      if (is_superuser() == false) {
        header("location:/$home/login");
        return;
      }
      import("apps/view/pages/countries/edit.php");
      return;
    }
    if ($url[0] == "update-country-ajax") {
      if (is_superuser() == false) {
        echo js_alert('Please login as superuser');
        return;
      }
      $prodctrl = new Country_ctrl;
      $prodctrl->update();
      echo js_alert(msg_ssn(return: true));
      echo RELOAD;
      exit;
    }
    #################  end #######################
    if ($url[0] == "add-new-address") {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }
      // myprint($_POST);
      $arr = null;
      $itemAddress = new Model('address');
      $arr['user_id'] = $_SESSION['user_id'];
      $arr['name'] = $_POST['person_name'];
      $arr['mobile'] = $_POST['mobile'];
      $arr['locality'] = $_POST['locality'];
      $arr['city'] = $_POST['city'];
      $arr['state'] = $_POST['state'];
      $arr['country'] = $_POST['country'];
      $arr['zipcode'] = $_POST['zipcode'];
      $arr['address_type'] = $_POST['address_type'];
      if ($arr['locality'] == "") {
        echo js_alert('locality cannot be empty');
        return;
      }
      if ($arr['city'] == "") {
        echo js_alert('city cannot be empty');
        return;
      }
      if ($arr['state'] == "") {
        echo js_alert('state cannot be empty');
        return;
      }
      if ($arr['country'] == "") {
        echo js_alert('country name cannot be empty');
        return;
      }

      $add = $itemAddress->store($arr);
      if (intval($add)) {
        echo js_alert('Address added Successfully');
        echo RELOAD;
        return;
      } else {
        echo js_alert('Address not added');
        return;
      }
    }
    if ($url[0] == "remove-cart-ajax") {

      if (authenticate() == false) {
        echo js_alert('You need to login first');
        return;
      }
      if (isset($_POST['delete_cart'])) {
        $cartobj = new Dbobjects;
        $cartobj->tableName = "my_order";
        $arr['user_id'] = $_SESSION['user_id'];
        $arr['status'] = 'cart';
        $arr['payment_id'] = 0;
        $cartobj->filter($arr);
        $arr = null;
        $cartobj->delete();
        echo js_alert('Products Deleted Successfully');
        echo RELOAD;
        return;
      }
    }

    if ($url[0] == "checkout-form") {
      if (authenticate() == false) {

        echo js_alert('You need to login first');
        import("apps/view/pages/login.php");;
        return;
      }
      import("apps/view/pages/checkout-form.php");
      return;
    }
    ################## Order Placement ################################
    if ($url[0] == "place-order-ajax") {
      if (authenticate() == false) {
        echo js_alert('Please login first');
        return;
      }
      $ordCtrl = new Order_ctrl;
      $reply = $ordCtrl->place();
      if ($reply) {
        echo js_alert(msg_ssn(return: true));
        echo go_to('orders');
        return;
      } else {
        echo js_alert(msg_ssn(return: true));
        return;
      }
      exit;
    }
    ################## Order Placement ends ################################
    if ($url[0] == "country-search-ajax-profile") {
      $cntsr = searchCountry($_POST['key_ctry']);
?>
      <select name="country" class="form-select">
        <?php if (count($cntsr) != 0) {
          foreach ($cntsr as $item) { ?>
            <option value="<?= $item['code']; ?>"><?= $item['name']; ?></option>
        <?php  }
        } ?>

      </select>
    <?php
      exit;
    }
    if ($url[0] == "country-search-ajax") {
      $cntsr = searchCountry($_POST['key_ctry']);
    ?>
      <select name="country" class="form-control">
        <?php if (count($cntsr) != 0) {
          foreach ($cntsr as $item) { ?>
            <option value="<?= $item['id']; ?>"><?= $item['name']; ?></option>
        <?php  }
        } ?>

      </select>
    <?php
      exit;
    }

    if ($url[0] == "country-code-search-ajax") {
      $cntcodesr = searchPhone($_POST['key_ctry_code']);
    ?>
      <select name="country_code" class="form-control">
        <?php if (count($cntcodesr) != 0) {
          foreach ($cntcodesr as $item) { ?>
            <option value="<?= $item['dial_code']; ?>"><?= $item['dial_code']; ?></option>
        <?php  }
        } ?>

      </select>
      <?php
      exit;
    }
    if ($url[0] == "user-detail-ajax") {
      if (is_superuser() == false) {
        echo js_alert('Only Master can access this');
        return;
      }
      $username = $_POST['username'];
      $userObj = new Model('pk_user');
      $user = $userObj->filter_index(['username' => $username]);
      if (count($user) > 0) {
        $user = obj($user[0]);
        $uname = $user->username;
        $uemail = $user->email;
        $fname = $user->first_name;
        $lname = $user->last_name;
        $ucountry = $user->country;
        $uaddress = $user->address;

      ?>
        <div class="row">
  <?php
        $html = '<div class="col-6 mb-3"><input id="set-tree-user" class=" form-control valid" value="' . $uname . '" readonly></div>';
        $html .= '<div class="col-6 mb-3"><input id="set-tree-user" class="form-control valid" value="' . $uemail . '" readonly></div></div>';
        $html .= '<div class="col-12 mb-3"><input id="set-tree-user" class="form-control valid" value="' . $fname . '" readonly></div></div>';
        $html .= '<div class="col-12 mb-3"><input id="set-tree-user" class="form-control valid" value="' . $lname . '" readonly></div></div>';
        $html .= '<div class="col-12 mb-3"><input id="set-tree-user" class="form-control valid" value="' . $ucountry . '" readonly></div></div>';
        $html .= '<div class="col-12 mb-3"><input id="set-tree-user" class="form-control valid" value="' . $uaddress . '" readonly></div></div>';
        echo $html;
        exit;
      } else {
        echo js_alert('User not found');
        exit;
      }
      // echo $username;

      // echo js_alert($html);
      exit;
    }

    if ($url[0] == "reset-account") {
      import("apps/view/pages/reset-account.php");
      return;
    } else {
      import("apps/view/pages/404.php");
      return;
    }
    break;
}
