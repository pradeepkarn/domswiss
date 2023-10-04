<?php
function login()
{
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $account = new Account();
    $user = $account->login($_POST['username'], $_POST['password']);
    if ($user != false) {
      $cookie_name = "remember_token";
      $cookie_value = bin2hex(random_bytes(32)) . "_uid_" . $user['id'];
      setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 12), "/"); // 86400 = 1 day
      $db = new Model('pk_user');
      $db->show($_SESSION['user_id']);
      $arr = null;
      $arr['remember_token'] = $cookie_value;
      $db->update($_SESSION['user_id'], $arr);
      $arr = null;
      // $GLOBALS['msg_signin'][] = "Login Success";
      $_SESSION['msg'][] = "Login Success";
      return $user;
    } else {
      // $GLOBALS['msg_signin'][] = "Invalid credentials";
      $_SESSION['msg'][] = "Invalid credentials";
      return false;
    }
  }
}
function register()
{
  if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['cnfpassword'])) {
    if ($_POST['password'] === $_POST['cnfpassword']) {
      $account = new Account();
      $user = $account->register($_POST['email'], $_POST['password']);
      if ($user != false) {
        $cookie_name = "remember_token";
        $cookie_value = bin2hex(random_bytes(32)) . "_uid_" . $user['id'];
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 12), "/"); // 86400 = 1 day
        $db = new Mydb('pk_user');
        $db->pk($_SESSION['user_id']);
        $arr = null;
        $arr['remember_token'] = $cookie_value;
        $db->updateData($arr);
        $arr = null;
        return $user;
      } else {
        $GLOBALS['msg_signup'][] = "Sorry something went wrong";
        return false;
      }
    } else {
      $GLOBALS['msg_signup'][] = "Sorry, Password did not match";
      return false;
    }
  }
}
function is_superuser()
{
  $account = new Account();
  return $account->is_superuser();
}

function authenticate()
{
  $account = new Account();
  return $account->authenticate();
}
function myprint($data = null)
{
  echo "<pre>";
  print_r($data);
  echo "</pre>";
}
function pkAjax($button, $url, $data, $response, $event = 'click', $method = "post", $progress = false, $return = false)
{
  $progress_code = "";
  if ($progress == true) {
    $progress_code = "xhr: function() {
          var xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener('progress', function(evt) {
              if (evt.lengthComputable) {
                  var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                  $('.progress-bar').width(percentComplete + '%');
                  $('.progress-bar').html(percentComplete+'%');
              }
          }, false);
          return xhr;
          },";
  }
  $home = home;
  $ajax = "<script>
  $(document).ready(function() {
      $('{$button}').on('{$event}',function(event) {
          event.preventDefault();
          if (typeof tinyMCE != 'undefined') {
            tinyMCE.triggerSave();
          }
          $.ajax({
              $progress_code
              url: '/{$home}{$url}',
              method: '$method',
              data: $('{$data}').serializeArray(),
              dataType: 'html',
              success: function(resultValue) {
                  $('{$response}').html(resultValue)
              }
          });
      });
  });
  </script>";
  if ($return == true) {
    return $ajax;
  }
  echo $ajax;
}
function pkAjax_form($button, $data, $response, $event = 'click', $progress = false)
{
  $progress_code = "";
  if ($progress == true) {
    $progress_code = "xhr: function() {
          var xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener('progress', function(evt) {
              if (evt.lengthComputable) {
                  var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                  $('.progress-bar').width(percentComplete + '%');
                  $('.progress-bar').html(percentComplete+'%');
              }
          }, false);
          return xhr;
          },";
  }
  $ajax = "<script>
  $(document).ready(function (e) {
    $('{$data}').on('submit',(function(e) {
        e.preventDefault();
        if (typeof tinyMCE != 'undefined') {
          tinyMCE.triggerSave();
        }
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
          $progress_code
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(resultValue){
              $('{$response}').html(resultValue)
            }
        });
    }));
    $('{$button}').on('{$event}', function(e) {
      e.preventDefault();
      $('{$data}').submit();
  });
});
</script>";
  echo $ajax;
}
function get_content_by_slug($slug)
{
  $obj = new Model('content');
  $cont =  $obj->filter_index(array('slug' => $slug, 'content_group' => 'page'));
  if (count($cont) == 1) {
    return $cont[0];
  } else {
    return false;
  }
}
function generate_username_by_email($email, $try = 100)
{
  if (filter_var($email, FILTER_VALIDATE_EMAIL) == true) {
    $db = new Model('pk_user');
    $arr['email'] = sanitize_remove_tags($email);
    $emailarr = explode("@", $arr['email']);
    $username = $emailarr[0];
    $dbusername = $db->exists(array('username' => $username));
    if ($dbusername == true) {
      $i = 1;
      while ($dbusername == true) {
        $dbusername = $db->exists(array('username' => $username . $i));
        if ($dbusername == false) {
          return $username . $i;
        }
        if ($i == $try) {
          break;
        }
        $i++;
      }
    } else {
      return $username;
    }
  } else {
    return false;
  }
}
function generate_dummy_email($prefix = null)
{
  return rand(1000, 9999) . "_" . uniqid($prefix) . "@example.com";
}
function bsmodal($id = "", $title = "", $body = "", $btn_id='', $btn_text = "Action", $btn_class = "btn btn-primary", $size = "modal-sm", $modalclasses = "")
{
  $str = "
<div class='modal fade' id='$id' tabindex='-1' aria-hidden='true'>
<div class='modal-dialog $size'>
<div class='modal-content'>
<div class='modal-header'>
    <h5 class='modal-title'>$title</h5>
    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
</div>
<div class='modal-body'>
$body
</div>
<div class='modal-footer'>
    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
    <button type='button' id='$btn_id' class='$btn_class'>$btn_text</button>
</div>
</div>
</div>
</div>";
  return $str;
}
function popmodal($id = "", $title = "", $body = "", $btn_id='', $btn_text = "Action", $btn_class = "btn btn-primary", $size = "modal-sm", $close_btn_class = "")
{
  $str = "
<div class='modal fade' id='$id' tabindex='-1' aria-hidden='true'>
<div class='modal-dialog $size'>
<div class='modal-content'>
<div class='modal-header'>
    <h5 class='modal-title'>$title</h5>
    <button type='button' class='$close_btn_class btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
</div>
<div class='modal-body'>
$body
</div>
<div class='modal-footer'>
    <button type='button' class='$close_btn_class btn btn-secondary' data-bs-dismiss='modal'>Close</button>
    <button type='button' id='$btn_id' class='$btn_class'>$btn_text</button>
</div>
</div>
</div>
</div>";
  return $str;
}
function generate_slug($slug, $try = 1000)
{
  if ($slug !== "") {
    $db = new Model('content');
    $slug = str_replace(" ", "-", sanitize_remove_tags($slug));
    $dbslug = $db->exists(array('slug' => $slug));
    if ($dbslug == true) {
      $i = 1;
      while ($dbslug == true) {
        $dbslug = $db->exists(array('slug' => $slug . $i));
        if ($dbslug == false) {
          return $slug . $i;
        }
        if ($i == $try) {
          return false;
          break;
        }
        $i++;
      }
    } else {
      return $slug;
    }
  } else {
    return false;
  }
}
function ajaxLoad($loadId)
{
  $ajax = "<script>
$(document).ready(function() {
          
  $(document).ajaxStart(function(){
  $('{$loadId}').css('display', 'block');
});
$(document).ajaxComplete(function(){
  $('{$loadId}').css('display', 'none');
});
});
</script>";
  echo $ajax;
}
function ajaxLoadModal($loadId)
{
  $ajax = "<script>
$(document).ready(function() {
          
  $(document).ajaxStart(function(){
  $('{$loadId}').modal('show');
});
$(document).ajaxComplete(function(){
  $('{$loadId}').modal('hide');
});
});
</script>";
  echo $ajax;
}
function ajaxActive($qry)
{
  $ajax = "<script>
$(document).ready(function() {
  $('{$qry}').css({'visibility':'hidden'});
  $(document).ajaxStart(function(){
  $('{$qry}').css({'visibility':'visible'});
});
$(document).ajaxComplete(function(){
  $('{$qry}').css({'visibility':'hidden'});
});
});
</script>";
  echo $ajax;
}
function ajaxDeactive($qry)
{
  $ajax = "<script>
$(document).ready(function() {
  $('{$qry}').css({'visibility':'visible'});
  $(document).ajaxStart(function(){
  $('{$qry}').css({'visibility':'hidden'});
});
$(document).ajaxComplete(function(){
  $('{$qry}').css({'visibility':'visible'});
});
});
</script>";
  echo $ajax;
}
function removeSpace($str)
{
  $str = str_replace(" ", "_", sanitize_remove_tags($str));
  $str = str_replace("/", "_", $str);
  $str = str_replace("\\", "_", $str);
  $str = str_replace("&", "_", $str);
  $str = str_replace(";", "", $str);
  $str = str_replace(";", "", $str);
  $str = strtolower($str);
  return $str;
}
function filter_name($file_with_ext = "")
{
  $only_file_name = pathinfo($file_with_ext, PATHINFO_FILENAME);
  $only_file_name =  sanitize_remove_tags(str_ireplace(" ", "_", $only_file_name));
  $only_file_name =  sanitize_remove_tags(str_ireplace("(", "", $only_file_name));
  $only_file_name =  sanitize_remove_tags(str_ireplace(")", "", $only_file_name));
  $only_file_name =  sanitize_remove_tags(str_ireplace("'", "", $only_file_name));
  $only_file_name =  sanitize_remove_tags(str_ireplace("\"", "", $only_file_name));
  $only_file_name =  sanitize_remove_tags(str_ireplace("&", "", $only_file_name));
  $only_file_name =  sanitize_remove_tags(str_ireplace(";", "", $only_file_name));
  $only_file_name =  sanitize_remove_tags(str_ireplace("#", "", $only_file_name));
  return $only_file_name;
}
function getAccessLevel()
{
  if (isset($_SESSION['user_id'])) {
    $db = new Dbobjects();
    $db->tableName = "pk_user";
    $qry['id'] = $_SESSION['user_id'];
    $db->insertData = $qry;
    if (count($db->filter($qry)) != 0) {
      return $db->pk($_SESSION['user_id'])['access_level'];
    } else {
      false;
    }
  } else {
    false;
  }
}
function updateMyProfile()
{
  if (isset($_SESSION['user_id'])) {
    $db = new Mydb('pk_user');
    if (isset($_POST['update_profile_by_admin'])) {
      $qry['id'] = $_POST['update_profile_by_admin'];
    } else {
      $qry['id'] = $_SESSION['user_id'];
    }
    if (isset($_POST['password']) && ($_POST['password'] != "")) {
      $qry['password'] = md5($_POST['password']);
    }


    if (count($db->filterData($qry)) > 0) {
      if (isset($_POST['update_my_profile'])) {
        $upqry['name'] = sanitize_remove_tags($_POST['my_name']);
        $upqry['mobile'] = sanitize_remove_tags($_POST['my_mobile']);
        $upqry['updated_at'] = date('y-m-d h:m:s');
        $db->updateData($upqry);
      }
    } else {
      false;
    }
  } else {
    false;
  }
}

function getTableRowById($tablename, $id)
{
  $db = new Mydb($tablename);
  $qry['id'] = $id;
  if (count($db->filterData($qry)) > 0) {
    return $db->pkData($id);
  } else {
    false;
  }
}
function check_slug_globally($slug = null)
{
  $count = 0;
  $var = ['categories', 'content'];
  for ($i = 0; $i < count($var); $i++) {
    $db = new Dbobjects();
    $db->tableName = $var[$i];
    $qry['slug'] = $slug;
    $count += count($db->filter($qry));
  }
  return $count;
}

function all_books($ord = "DESC", $limit = 100, $post_cat = "", $catid = "")
{
  $novels = array();
  $novelobj = new Model('content');
  $arr['content_group'] = 'book';
  if ($post_cat != "") {
    $arr['post_category'] = $post_cat;
  }
  if ($catid != "") {
    $arr['parent_id'] = $catid;
  }
  $novels = $novelobj->filter_index($arr, $ord, $limit);
  if ($novels == false) {
    $novels = array();
  }
  return $novels;
}
function all_cats()
{
  $novels = array();
  $novelobj = new Model('content');
  $arr['content_group'] = 'listing_category';
  $novels = $novelobj->filter_index($arr);
  if ($novels == false) {
    $novels = array();
  }
  return $novels;
}
function js_alert($msg = "")
{
  return "<script>alert('{$msg}');</script>";
}
function js($msg = "")
{
  return "<script>{$msg}</script>";
}
function matchData($var1 = "null", $var2 = "null", $print = "Pradeep Karn")
{
  if ($var1 == $var2) {
    echo $print;
  }
}
function views($post_category = 'general', $cont_type = 'post')
{
  $views = array();
  $db = new Mydb('content');
  $data = $db->filterData(['post_category' => $post_category, 'content_type' => $cont_type]);
  foreach ($data as $key => $value) {
    $views[] = $value['views'];
  }
  $views = array_sum($views);
  return $views;
}
function pk_excerpt($string = null, $limit = 50, $strip_tags = true)
{
  if ($strip_tags === true) {
    $string = strip_tags($string);
  }
  if (strlen($string) > $limit) {
    // truncate string
    $stringCut = substr($string, 0, $limit);
    $endPoint = strrpos($stringCut, ' ');
    //if the string doesn't contain any space then it will cut without word basis.
    $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
    $string = $string . "...";
  }
  return $string;
}
function filterUnique($table, $col, $ord = "DESC")
{
  $db = new Mydb($table);
  if (count($db->filterDistinct($col)) > 0) {
    return $db->filterDistinct($col, $ord, 100000);
  } else {
    return false;
  }
}
//categories start
function create_category()
{

  global $conn;
  $parent_id = legal_input($_POST['parent_id']);
  $category_name = legal_input($_POST['category_name']);
  $catdb = new Model('content');
  $arr['title'] = $category_name;
  $arr['parent_id'] = $parent_id;
  $new_cat_id = $catdb->store($arr);
  // $query=$conn->prepare("INSERT INTO categories (parent_id, category_name) VALUES (?,?)");
  // $query->bind_param('is',$parent_id,$category_name);
  // $exec= $query->execute();
  if ($new_cat_id != false) {
    return $new_cat_id;
  } else {
    return false;
  }
}

function multilevel_categories($parent_id = 0, $radio = true, $category_group = "listing_category")
{
  $catdb = new Model('content');
  $exec = $catdb->filter_index(array('parent_id' => $parent_id, 'content_group' => $category_group));
  $catData = [];
  if ($exec != false) {
    foreach ($exec as $key => $row) {
      $catData[] = [
        'id' => $row['id'],
        'parent_id' => $row['parent_id'],
        'category_name' => $row['title'],
        'nested_categories' => multilevel_categories($row['id'], $radio, $category_group),
        'radio' => $radio
      ];
    }

    return $catData;
  } else {
    return $catData = [];
  }
}

function display_list($nested_categories)
{
  $rd = null;
  $home = home;
  $list = '<ul class="list-none">';
  foreach ($nested_categories as $nested) {
    if ($nested['radio'] == true) {
      $rd = '<input type="radio" name="parent_id" value=' . $nested['id'] . '> ';
    }
    $list .= '<li>' . $rd . "<a href='/{$home}/admin/categories/edit/{$nested['id']}' class='text-deco-none'>" . $nested['category_name'] . '</a></li>';
    if (!empty($nested['nested_categories'])) {
      $list .= display_list($nested['nested_categories']);
    }
  }
  $list .= '</ul>';
  return $list;
}

function display_option($nested_categories, $mark = ' ')
{
  $option = null;
  foreach ($nested_categories as $nested) {

    $option .= '<option value="' . $nested['id'] . '">' . $mark . $nested['category_name'] . '</option>';

    if (!empty($nested['nested_categories'])) {
      $option .= display_option($nested['nested_categories'], $mark . '-');
    }
  }
  return $option;
}
function getData($table, $id)
{
  return (new Model($table))->show($id);
}
// convert illegal input to legal input
function legal_input($value)
{
  $value = trim($value);
  $value = stripslashes($value);
  $value = htmlspecialchars($value);
  return $value;
}
function getCatTree($parent_id)
{
  $db = new Model('content');
  $listings = $db->filter_index(array('content_group' => 'listing_category', 'parent_id' => $parent_id), $ord = "DESC", $limit = "1000", $change_order_by_col = "");
  if ($listings == false) {
    $listings = array();
  }
  $listing_data = array();
  foreach ($listings as $key => $uv) {
    $listing_data[] = array(
      'id' => $uv['id'],
      'title' => $uv['title'],
      'info' => $uv['content_info'],
      'description' => $uv['content'],
      'image' => "/media/images/pages/" . $uv['banner'],
      'category' => ($uv['parent_id'] == 0) ? 'Main' : getData('content', $uv['parent_id'])['title'],
      'status' => $uv['status'],
      'child' => getCatTree($uv['id'])
    );
  }
  return $listing_data;
}
//cart count
$GLOBALS['cart_cnt'] = 0;
// if (authenticate()===true) {
//    $cartObj = new Model('my_order');
//    $mycart = $cartObj->filter_index(array('user_id'=>$_SESSION['user_id'],'status'=>'cart'));
//    if ($mycart==false) {
//     $mycart = array();
//    }
//    $GLOBALS['cart_cnt'] = count($mycart);
// }

if (isset($_SESSION['cart'])) {
  $GLOBALS['cart_cnt'] = count($_SESSION['cart']);
}
function change_my_banner($contentid, $banner, $banner_name = "img")
{
  if (isset($banner)) {
    $file = $banner;
    $media_folder = "images/pages";
    $imgname = $banner_name;
    $media = new Media();
    $page = new Dbobjects();
    $page->tableName = 'content';
    $pobj = $page->pk($contentid);
    $target_dir = RPATH . "/media/images/pages/";
    if ($pobj['banner'] != "") {
      if (file_exists($target_dir . $pobj['banner'])) {
        unlink($target_dir . $pobj['banner']);
        $_SESSION['msg'][] = "Old image was replaced";
      }
    }
    $file_ext = explode(".", $file["name"]);
    $ext = end($file_ext);
    $page->insertData['banner'] = $imgname . "." . $ext;
    $page->update();
    $media->upload_media($file, $media_folder, $imgname, $file['type']);
  }
}

function user_data_by_token($token, $col = 'email')
{
  $user = new Model('pk_user');
  $arr['app_login_token'] = $token;
  $user = $user->filter_index($arr);
  if (!count($user) > 0) {
    return false;
  } else {
    return $user[0][$col];
  }
}

function nested_array_unique($nested_array)
{
  $flattened = array_map('serialize', $nested_array);
  $flattened = array_unique($flattened);
  return array_map('unserialize', $flattened);
}

function num_to_words($number)
{
  $no = floor($number);
  $point = round($number - $no, 2) * 100;
  $hundred = null;
  $digits_1 = strlen($no);
  $i = 0;
  $str = array();
  $words = array(
    '0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety'
  );
  $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
  while ($i < $digits_1) {
    $divider = ($i == 2) ? 10 : 100;
    $number = floor($no % $divider);
    $no = floor($no / $divider);
    $i += ($divider == 10) ? 1 : 2;
    if ($number) {
      $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
      $hundred = ($counter == 1 && $str[0]) ? ' ' : null;
      $str[] = ($number < 21) ? $words[$number] .
        " " . $digits[$counter] . $plural . " " . $hundred
        :
        $words[floor($number / 10) * 10]
        . " " . $words[$number % 10] . " "
        . $digits[$counter] . $plural . " " . $hundred;
    } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " .
    $words[$point = $point % 10] : '';
  //  echo $result . "Rupees  " . $points . " Paise";
  return $result;
}
function msg_ssn($var = 'msg', $return = false)
{
  if (isset($_SESSION[$var])) {
    if ($return == true) {
      $returnmsg = null;
      foreach ($_SESSION[$var] as $msg) {
        $returnmsg .= "{$msg}\\n";
      }
      unset($_SESSION[$var]);
      return $returnmsg;
    }
    foreach ($_SESSION[$var] as $msg) {
      echo "{$msg}<br>";
    }
    unset($_SESSION[$var]);
  }
}
function usersignup()
{
  if (isset($_POST['mobile']) && isset($_POST['password']) && isset($_POST['cnf_password'])) {

    $email = generate_dummy_email($_POST['mobile']);
    if (isset($_POST['email'])) {
      $email = sanitize_remove_tags($_POST['email']);
    }

    $password = sanitize_remove_tags($_POST['password']);
    $cnf_password = sanitize_remove_tags($_POST['cnf_password']);


    $arr['email'] = $email;
    $arr['password'] = md5($password);
    //name
    if (isset($_POST['name'])) {
      if (strlen(sanitize_remove_tags($_POST['name'])) < 2) {
        $_SESSION['msg'][] = "Invalid name";
        return;
      }
      $arr['name'] = sanitize_remove_tags($_POST['name']);
    }
    if (isset($_POST['first_name'])) {
      // if (strlen(sanitize_remove_tags($_POST['first_name'])) < 1) {
      //   $_SESSION['msg'][] = "Invalid first name";
      //   return;
      // }
      $arr['first_name'] = sanitize_remove_tags($_POST['first_name']);
    }
    if (isset($_POST['last_name'])) {
      // if (strlen(sanitize_remove_tags($_POST['last_name'])) < 1) {
      //   $_SESSION['msg'][] = "Invalid last name";
      //   return;
      // }
      $arr['last_name'] = sanitize_remove_tags($_POST['last_name']);
    }
    if (isset($_POST['ref'])) {
      if (filter_var($_POST['ref'], FILTER_VALIDATE_INT) == false) {
        $_SESSION['msg'][] = "Invalid refrence";
        return;
      }
      if (getData("pk_user", $_POST['ref']) == false) {
        $_SESSION['msg'][] = "Invalid refrence";
        return;
      }
      $arr['ref'] = sanitize_remove_tags($_POST['ref']);
    }

    if (isset($_POST['city'])) {
      if (strlen(sanitize_remove_tags($_POST['city'])) < 2) {
        $_SESSION['msg'][] = "Invalid city name";
        return;
      }
      $arr['city'] = sanitize_remove_tags($_POST['city']);
    }
    if (isset($_POST['state'])) {
      if (strlen(sanitize_remove_tags($_POST['state'])) < 2) {
        $_SESSION['msg'][] = "Invalid state name";
        return;
      }
      $arr['state'] = sanitize_remove_tags($_POST['state']);
    }
    if (isset($_POST['address'])) {
      if (strlen(sanitize_remove_tags($_POST['address'])) < 2) {
        $_SESSION['msg'][] = "Invalid Address";
        return;
      }
      $arr['address'] = sanitize_remove_tags($_POST['address']);
    }
    if (isset($_POST['gender'])) {
      if (strlen(sanitize_remove_tags($_POST['gender'])) < 1) {
        $_SESSION['msg'][] = "Invalid Gender";
        return;
      }
      $arr['gender'] = sanitize_remove_tags($_POST['gender']);
    }
    if (isset($_POST['country'])) {
      if (!intval($_POST['country'])) {
        $_SESSION['msg'][] = "Invalid country id";
        return;
      }
      $contry = getData('countries', $_POST['country']);
      $arr['country'] = $contry['name'];
      $arr['country_code'] = $contry['code'];
    }
    if (isset($_POST['country_code'])) {
      if (strlen(sanitize_remove_tags($_POST['country_code'])) < 2) {
        $_SESSION['msg'][] = "Invalid Country Code ";
        return;
      }
      $arr['isd_code'] = sanitize_remove_tags($_POST['country_code']);
    }
    //email
    if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
      $_SESSION['msg'][] = "Invalid Email, please try with correct email";
      return;
    }
    //check regtered email
    $user_by_email = (new Model('pk_user'))->exists(['email' => $email]);
    if ($user_by_email != false) {
      $_SESSION['msg'][] = "User Email is already registered, please try again";
      return false;
    }
    //mobile
    if (isset($_POST['mobile'])) {
      if (filter_var($_POST['mobile'], FILTER_VALIDATE_INT) == false) {
        $_SESSION['msg'][] = "Invalid mobile";
        return;
      }
      $user_by_mobile = (new Model('pk_user'))->exists(['mobile' => $_POST['mobile']]);
      if ($user_by_mobile != false) {
        $_SESSION['msg'][] = "Mobile number is already registered";
        return;
      }
      $arr['mobile'] = sanitize_remove_tags($_POST['mobile']);
    }
    //national id number
    if (isset($_POST['national_id'])) {
      if ($_POST['national_id'] == "") {
        $_SESSION['msg'][] = "Empty National Id Number";
        return;
      }
      $user_by_national_id = (new Model('pk_user'))->exists(['national_id' => $_POST['national_id']]);
      if ($user_by_national_id != false) {
        $_SESSION['msg'][] = "Your National Id is already regsitered";
        return;
      }
      $arr['national_id'] = sanitize_remove_tags($_POST['national_id']);
    }
    //username

    if (isset($_POST['username'])) {
      $username = str_replace(" ", "", sanitize_remove_tags($_POST['username']));
      $arr['username'] = $username;
      if ((strlen($username) < 3) || (strlen($username) > 16)) {
        $_SESSION['msg'][] = "Username must be between 3 to 16 characters";
        return false;
      }
      //check regtered username
      $user_by_username = (new Model('pk_user'))->exists(['username' => $username]);
      if ($user_by_username != false) {
        $_SESSION['msg'][] = "Username is not available";
        return false;
      }
    } else {
      $arr['username'] = generate_username_by_email($email, $try = 500);
    }


    //empty pass
    if (($_POST['password']) == "") {
      $_SESSION['msg'][] = "Empty password is not allowed";
      return false;
    }
    //valid pass
    if (($password != $_POST['password'])) {
      $_SESSION['msg'][] = "Invalid characters used in password";
      return false;
    }
    //pass match
    if ($password != $cnf_password) {
      $_SESSION['msg'][] = "Password did not match";
      return false;
    }
    //if evrything valid then
    $dbcreate = new Model('pk_user');
    $arr['user_group'] = 'customer';
    $arr['username'] = strtolower($arr['username']);
    $userid = $dbcreate->store($arr);
    if ($userid == false) {
      $_SESSION['msg'][] = "User not created";
      return false;
    }
    if (intval($userid)) {
      $_SESSION['msg'][] = "User created successfully";
      return $userid;
    } else {
      $_SESSION['msg'][] = "User not created";
      return false;
    }

    // echo go_to("login");
    return true;
  } else {
    $_SESSION['msg'][] = "Missing required field";
    return false;
  }
}

function createAddess($userid, $post)
{
  $arr = null;
  $itemAddress = new Model('address');
  $arr['user_id'] = $userid;
  $arr['name'] = $post['first_name'];
  $arr['isd_code'] = $post['country_code'];
  $arr['mobile'] = $post['mobile'];
  $arr['locality'] = $post['address'];
  $arr['city'] = $post['city'];
  $arr['state'] = $post['state'];
  $country = getData('countries', $post['country']);
  if ($country == false) {
    $_SESSION['msg'][] = ('Invalid country');
    return;
  }
  $arr['country'] = $country['name'];
  $arr['country_code'] = $country['code'];
  $arr['zipcode'] = $post['zipcode'];
  $arr['address_type'] = 'primary';
  $arr['address_name'] =  'Sign up address';
  if ($arr['locality'] == "") {
    $_SESSION['msg'][] = ('locality cannot be empty');
    return;
  }
  if ($arr['city'] == "") {
    $_SESSION['msg'][] = ('city cannot be empty');
    return;
  }
  if ($arr['state'] == "") {
    $_SESSION['msg'][] = ('state cannot be empty');
    return;
  }
  if ($arr['country'] == "") {
    $_SESSION['msg'][] = ('country name cannot be empty');
    return;
  }

  $add = $itemAddress->store($arr);
  if (intval($add)) {
    $_SESSION['msg'][] = ('Address added Successfully');
    // echo RELOAD;
    return;
  } else {
    $_SESSION['msg'][] = ('Address not added');
    return;
  }
}

function go_to($link = "")
{
  $home = home;
  $var = <<<RED
            <script>
                location.href="/$home/$link";
            </script>
            RED;
  return $var;
}

function logut()
{
}

function count_commissions($partner_id)
{
  $obj = new Model('commissions');
  $cmsn = $obj->filter_index(array('credited_to' => $partner_id));
  return count($cmsn);
}

function cart_items($user_id)
{
  $obj = new Model('customer_order');
  $item = $obj->filter_index(array('status' => 'cart', 'user_id' => $user_id));
  return count($item);
}
// function partenrs($myid,$level=3)
// {
//   $ids = [];
//   $cmsn = 0;
//   $allPartenrs = (new Model('pk_user'))->filter_index(array('ref'=>$myid));
//   $i = 1;
//   foreach ($allPartenrs as $prtnr) {
//     $cmsn += get_pv($prtnr['id'],$level=1);
//     $ids[] = [
//       'first_ring'=> $prtnr['id'],
//       'pv'=>get_pv($prtnr['id'],$level=1),
//       'next_ring'=> partenrs($myid=$prtnr['id']),
//     ];

//     $i++;
//   }
//   return $ids;
// }

// function get_pv($buyerid,$level=1)
// {
//   $total_amt = 500;
//   $pv = (7 / 100) * $total_amt;
//   return $pv;
// }
function get_banks_by_country($code = MY_COUNTRY)
{
  $cntrObj = new Model('countries');
  $cuntry = $cntrObj->filter_index(['code' => $code]);
  if (count($cuntry)) {
    $pv = obj($cuntry[0]);
    $jsn = json_decode($pv->jsn);
    $banks = [];
    $gateways = [];
    if (isset($jsn->banks)) {
      $banks = $jsn->banks;
    }
    if (isset($jsn->gateways)) {
      $gateways = $jsn->gateways;
    }
  }
  return array(
    'banks' => $banks,
    'gateways' => $gateways
  );
}

$cmsn_arr = [];
function partners($myid, $percent = 7, $level = 1)
{
  $cmsndtls = [];
  $allPartenrs = (new Model('commissions'))->filter_index(array('credited_to' => $myid));
  global $cmsn_arr;
  $unique_order = 0;
  $uniqueOrders = []; // Array to store unique order_by values

  foreach ($allPartenrs as $ord) {
    if (!in_array($ord['order_by'], $uniqueOrders)) {
      $uniqueOrders[] = $ord['order_by'];
      $unique_order++;
    }

    if ($level == 1) {
      $percent = 7;
    }
    if ($level == 2) {
      $percent = 5;
    }
    if ($level == 3) {
      $percent = 3;
    }
    if ($level >= 4 && $level <= 8) {
      $percent = 0.5;
    }
    if ($level >= 9 && $level <= 10) {
      $percent = 0.5;
    }

    if ($unique_order >= $level) {
      $cmsn = $ord['total_amount'] * ($percent / 100);
    } else {
      $cmsn = 0;
    }
    $cmsn_arr[] = $cmsn;
    $cmsndtls[] = [
      'ring' => $level,
      // 'unique_order' => $unique_order,
      'order_by' => getData('pk_user', $ord['order_by'])['username'],
      'percentage' => $percent,
      'total_amount' => $ord['total_amount'],
      'commission' => $cmsn,
      'next_ring' => partners($ord['order_by'], $percent, ($level + 1)),
    ];
  }

  $data = array(
    'my_total_commission' => $cmsn_arr ? array_sum($cmsn_arr) : 0,
    'data' => $cmsndtls
  );

  // $jsonData = json_encode($data, JSON_PRETTY_PRINT);

  // $file = 'data.json'; // Specify the file name and path where you want to store the JSON data
  // file_put_contents($file, $jsonData);
  // return $data;
  return $cmsn_arr ? array_sum($cmsn_arr) : 0;
}
function msg($var)
{
  if (isset($GLOBALS[$var])) {
    foreach ($GLOBALS[$var] as $msg) {
      echo "{$msg}<br>";
    }
  }
}

function days_left($last_date)
{
  $startDate = new DateTime($last_date);
  $endDate = clone $startDate;
  $endDate->modify('+33 days');
  $currentDate = new DateTime();

  $interval = $endDate->diff($currentDate);
  if ($endDate < $currentDate) {
    return -$interval->days;
  } else if ($endDate >= $currentDate) {
    return $interval->days;
  }
}


// function days_left($last_date = LAST_PMT)
// {
//   $startDate = new DateTime($last_date);
//   $endDate = clone $startDate;
//   $endDate->modify('+33 days');
//   $currentDate = new DateTime();

//   $interval = $endDate->diff($currentDate);
//   $daysLeft = $interval->format('%r%a'); // Add %r to include the sign
//   return $daysLeft;
// }


function searchCountry($keyword = 'india')
{
  // $data = file_get_contents(RPATH . "/jsondata/country.json");
  // $data = json_decode($data, true);
  $plobj = new Model('countries');
  $data = $plobj->index();
  $results = array();
  foreach ($data as $item) {
    if (stripos($item['name'], $keyword) !== false) {
      $results[] = $item;
    }
    if (stripos($item['code'], $keyword) !== false) {
      $results[] = $item;
    }
  }
  return $results;
}
function getCurrency($keyword = 'CH')
{
  $data = file_get_contents(RPATH . "/jsondata/country-currency.json");
  $data = json_decode($data, true);
  foreach ($data as $item) {
    if ($keyword == $item['isoAlpha2']) {
      return $item;
    }
  }
  return [];
}
function searchPhone($keyword = '+91')
{
  $data = file_get_contents(RPATH . "/jsondata/phonecode.json");
  $data = json_decode($data, true);
  $results = array();
  foreach ($data as $item) {
    if (stripos($item['dial_code'], $keyword) !== false) {
      $results[] = $item;
    }
    if (stripos($item['code'], $keyword) !== false) {
      $results[] = $item;
    }
  }
  return $results;
}


function my_tree($ref=0, $depth = 1, $last_pmt='')
{
  if ($depth > 10) {
    return []; // Return an empty array if the maximum depth is reached
  }

  $prtdata = array();
  $db = new Dbobjects;
  $sql = "select pk_user.id, pk_user.username, pk_user.image, pk_user.ref from pk_user where pk_user.ref = $ref and pk_user.ref != 0 order by pk_user.id desc";
  $data = $db->show($sql);

  foreach ($data as $p) {
    $prtdata[] = array(
      'id' => $p['id'],
      'ring' => $depth,
      'username' => $p['username'],
      'is_active' => is_active_user($user_id = $p['id']),
      'image' => $p['image'],
      'purchase' => total_purchase_in($last_purchase_date = $last_pmt, $user_id = $p['id']),
      'purchase_array' => total_purchase_array($last_purchase_date = $last_pmt, $user_id = $p['id']),
      'pv' => total_pv_in($last_purchase_date = $last_pmt, $user_id = $p['id']),
      'rv' => total_rv_in($last_purchase_date = $last_pmt, $user_id = $p['id']),
      'tree' => my_tree($p['id'], $depth + 1, $last_pmt) // Increment the depth level by 1
    );
  }

  return $prtdata;
}

function total_purchase_in($last_purchase_date = null, $user_id = null)
{
  if (!$last_purchase_date || !$user_id) {
    return 0.0;
  } else {
    $db = new Dbobjects;
    $sql = "SELECT SUM(amount) AS purchase FROM payment WHERE user_id = $user_id AND status = 'paid' AND (invoice IS NOT NULL AND invoice <> '') AND DATEDIFF(created_at, '$last_purchase_date') <= 33";
    $amt = $db->show($sql);
  }
  if (!empty($amt) && isset($amt[0]['purchase'])) {
    return $amt[0]['purchase'];
  } else {
    return 0.0;
  }
}
function total_purchase_array($last_purchase_date = null, $user_id = null)
{
  if (!$last_purchase_date || !$user_id) {
    return array();
  } else {
    $db = new Dbobjects;
    // $sql = "SELECT id, unique_id,amount,status,payment_method,pv,created_at,updated_at FROM payment WHERE user_id = $user_id AND DATEDIFF(created_at, '$last_purchase_date') <= 28";
    $sql = "SELECT id, unique_id,amount,status,payment_method,pv,rv,created_at,updated_at FROM payment WHERE user_id = $user_id and status = 'paid' AND (invoice IS NOT NULL AND invoice <> '')";
    $data = $db->show($sql);
  }
  return $data;
}

function total_pv_in($last_purchase_date = null, $user_id = null)
{
  if (!$last_purchase_date || !$user_id) {
    return 0.0;
  } else {
    $db = new Dbobjects;
    // $sql = "SELECT SUM(pv) AS pv FROM payment WHERE user_id = $user_id AND DATEDIFF(created_at, '$last_purchase_date') <= 28";
    $sql = "SELECT SUM(pv) AS pv FROM payment WHERE user_id = $user_id and status = 'paid' AND (invoice IS NOT NULL AND invoice <> '')";
    $amt = $db->show($sql);
  }
  if (!empty($amt) && isset($amt[0]['pv'])) {
    return $amt[0]['pv'];
  } else {
    return 0.0;
  }
}
function total_rv_in($last_purchase_date = null, $user_id = null)
{
  if (!$last_purchase_date || !$user_id) {
    return 0.0;
  } else {
    $db = new Dbobjects;
    // $sql = "SELECT SUM(pv) AS pv FROM payment WHERE user_id = $user_id AND DATEDIFF(created_at, '$last_purchase_date') <= 28";
    $sql = "SELECT SUM(rv) AS rv FROM payment WHERE user_id = $user_id and status = 'paid' AND (invoice IS NOT NULL AND invoice <> '')";
    $amt = $db->show($sql);
  }
  if (!empty($amt) && isset($amt[0]['rv'])) {
    return $amt[0]['rv'];
  } else {
    return 0.0;
  }
}
function total_bonus($user_id = null)
{
  if (!$user_id) {
    return 0.0;
  } else {
    $db = new Dbobjects;
    $sqlPartners = "SELECT id AS ids FROM pk_user WHERE ref = $user_id and is_active = 1";
    $ids = $db->show($sqlPartners);
    // Extract the ids from the result into an array
    if (count($ids) > 0) {
      $idArray = array_column($ids, 'ids');
      // Convert the array to a comma-separated string for the IN clause
      $idString = implode(',', $idArray);
      // Query to get the sum of direct_bonus where user_id is found in the ids
      $sql = "SELECT SUM(direct_bonus) AS total_db FROM payment WHERE user_id IN ($idString) and status = 'paid' AND (invoice IS NOT NULL AND invoice <> '')";
      $amt = $db->show($sql);
    } else {
      return 0.0;
    }
  }
  if (!empty($amt) && isset($amt[0]['total_db'])) {
    return $amt[0]['total_db'];
  } else {
    return 0.0;
  }
}
function total_bonus_old($user_id = null)
{
  if (!$user_id) {
    return 0.0;
  } else {
    $db = new Dbobjects;
    $sql = "SELECT SUM(direct_bonus) AS total_db FROM payment WHERE user_id = $user_id and status = 'paid' AND (invoice IS NOT NULL AND invoice <> '')";
    $amt = $db->show($sql);
  }
  if (!empty($amt) && isset($amt[0]['total_db'])) {
    return $amt[0]['total_db'];
  } else {
    return 0.0;
  }
}

$cmsn_arr = [];
function calculatePercentageSum($tree, $depth, $treeLength, $userid = 0)
{
  global $cmsn_arr;
  $sum = 0;
  $rv_sum = 0;
  // Check if depth level is greater than the tree length
  if ($depth > $treeLength) {
    // return $sum;
    return array('sum' => $sum, 'rv_sum' => $rv_sum);
  }

  foreach ($tree as $node) {
    $pv = $node['pv'];
    $rv = $node['rv'];
    $purchase_array = $node['purchase_array'];
    $user = $node['username'];
    $ring = $depth;

    // Calculate percentage based on depth level and tree length
    if ($depth == 1 && $treeLength >= 1) {
      $percentage = 0.07;
    } elseif ($depth == 2 && $treeLength >= 2) {
      $percentage = 0.05;
    } elseif ($depth == 3 && $treeLength >= 3) {
      $percentage = 0.03;
    } else if ($depth == 4 && $treeLength >= 8) {
      $percentage = 0.01;
    } else if ($depth >= 5 && $treeLength >= 10) {
      $percentage = 0.005;
    } else {
      $percentage = 0.005;
    }

    // Add the calculated percentage of purchase to the sum
    $sum += $pv * $percentage;
    $cmsn = $pv * $percentage;
    $perc_rv = $rv * $percentage;
    $rv_sum += $rv * $percentage;
    $cmsn_arr[] = array('user' => $user, 'ring' => $ring, 'pv' => $pv, 'rv' => $rv, 'percentage' => $percentage, 'commission' => $cmsn, 'percentage_rv' => $perc_rv, 'purchase_array' => $purchase_array);
    // myprint($cmsn_arr);
    // $jsonData = json_encode($cmsn_arr, JSON_PRETTY_PRINT);
    // $file = "jsondata/trees/earning_" . $userid . '.json';
    // file_put_contents($file, $jsonData);
    // Recursively calculate the sum for child nodes
    $childTreeLength = count($node['tree']);
    $calc_arr = calculatePercentageSum($node['tree'], $depth + 1, $childTreeLength);
    $sum += $calc_arr['sum'];
    $rv_sum += $calc_arr['rv_sum'];
  }

  // return $sum;
  return array('sum' => $sum, 'rv_sum' => $rv_sum);
}
function family_tree($tree)
{
  $home = home;
  $html = '<ul>';

  foreach ($tree as $item) {
    $html .= '<li>';
    $html .= "<a href='javascript:void(0)'> " . $item['username'] . "<br><img class='user-img-icon-tree' data-tree-username='{$item['username']}' data-bs-target='#user-detail-model' data-bs-toggle='modal' class='tree-user' src='/$home/media/img/user-blank.png' alt='' width='80px' height='80px'> </a>";

    if (!empty($item['tree'])) {
      $html .= family_tree($item['tree']);
    }

    $html .= '</li>';
  }

  $html .= '</ul>';

  return $html;
}

function emaillog($msg = "", $file_name = 'email.log')
{
  $file = MEDIA_ROOT . "docs/$file_name";
  $log_file = fopen($file, 'a');
  $message = date('Y-m-d H:i:s') . " $msg\n";
  fwrite($log_file, $message);
  fclose($log_file);
}
function task_log($msg = "", $file_name = 'task.log')
{
  $file = MEDIA_ROOT . "docs/$file_name";

  createFolderIfNeeded(MEDIA_ROOT . "docs/", 0755);

  $log_file = fopen($file, 'a');
  $message = date('Y-m-d H:i:s') . " $msg\n";
  fwrite($log_file, $message);
  fclose($log_file);
}

function send_sign_up_email($obj)
{
  import(
    "apps/view/components/emails/signup_email.php",
    (object) array(
      "email" => $obj->email,
      "username" => $obj->username,
      "password" => $obj->password,
    )
  );
  if ($obj->partner_email != null) {
    import(
      "apps/view/components/emails/partners_signup_email.php",
      (object) array(
        "email" => $obj->partner_email,
        "username" => $obj->username,
        "name" => $obj->first_name . " " . $obj->last_name
      )
    );
  }
}

function obj($arr)
{
  return (object) $arr;
}
function arr($obj)
{
  return (array) $obj;
}

function is_active_user($user_id, $check_date = null)
{
  if ($check_date == null) {
    $check_date = date('Y-m-t');
  }
  $db = new Dbobjects;
  $sql = "SELECT status FROM payment WHERE status='paid' AND user_id = $user_id AND DATEDIFF(created_at, '$check_date') <= 33 ORDER BY created_at DESC LIMIT 1";
  $rw = $db->show($sql);
  if ($rw) {
    // Row found, return true
    return true;
  } else {
    // Row not found, return false
    return false;
  }
}
function active_member($data, $ringNumber)
{
  $activeCount = 0;
  $inactiveCount = 0;

  foreach ($data as $user) {
    if ($user['ring'] === $ringNumber) {
      if ($user['is_active']) {
        $activeCount++;
      } else {
        $inactiveCount++;
      }
    }

    if (!empty($user['tree'])) {
      $nestedCounts = active_member($user['tree'], $ringNumber);
      $activeCount += $nestedCounts['active'];
      $inactiveCount += $nestedCounts['inactive'];
    }
  }

  return array(
    'active' => $activeCount,
    'inactive' => $inactiveCount
  );
}

function getCommissions($req, $data_limit = 5)
{

  $req = obj($req);
  $current_page = 0;
  $data_limit = $data_limit;
  $page_limit = "0,$data_limit";
  $cp = 0;
  if (isset($req->page) && intval($req->page)) {
    $cp = $req->page;
    $current_page = (abs($req->page) - 1) * $data_limit;
    $page_limit = "$current_page,$data_limit";
  }
  $db = new Dbobjects;
  $tp = count($db->show("select id from ring_commissions"));
  if ($tp %  $data_limit == 0) {
    $tp = $tp / $data_limit;
  } else {
    $tp = floor($tp / $data_limit) + 1;
  }
  $q = null;
  if (isset($req->q)) {
    $q = $req->q;
  }
  $db = new Model('ring_commissions');
  $commissions =  $db->index(ord: "DESC", limit: $page_limit, change_order_by_col: 'created_at');
  return (object) array(
    'req' => obj($req),
    'total_cmsn' => $tp,
    'current_page' => $cp,
    'commissions' => $commissions
  );
}
function getMyCommissions($req, $data_limit = 5)
{

  // $req = obj($req);
  $current_page = 0;
  $data_limit = $data_limit;
  $page_limit = "0,$data_limit";
  $cp = 0;
  if (isset($req->page) && intval($req->page)) {
    $cp = $req->page;
    $current_page = (abs($req->page) - 1) * $data_limit;
    $page_limit = "$current_page,$data_limit";
  }
  $db = new Dbobjects;
  $tp = count($db->show("select id from ring_commissions where partner_id = '$req->my_id'"));
  if ($tp %  $data_limit == 0) {
    $tp = $tp / $data_limit;
  } else {
    $tp = floor($tp / $data_limit) + 1;
  }
  $q = null;
  if (isset($req->q)) {
    $q = $req->q;
  }
  $db = new Model('ring_commissions');
  $commissions =  $db->filter_index(assoc_arr: ['partner_id' => $req->my_id], ord: "DESC", limit: $page_limit, change_order_by_col: 'created_at');
  return (object) array(
    'req' => obj($req),
    'total_cmsn' => $tp,
    'current_page' => $cp,
    'commissions' => $commissions
  );
}
function getPage($req, $data_limit = 5)
{

  $req = obj($req);
  $current_page = 0;
  $data_limit = $data_limit;
  $page_limit = "0,$data_limit";
  $cp = 0;
  if (isset($req->page) && intval($req->page)) {
    $cp = $req->page;
    $current_page = (abs($req->page) - 1) * $data_limit;
    $page_limit = "$current_page,$data_limit";
  }
  $db = new Model('pk_user');
  $total_user = $db->index(ord: "DESC", limit: 1000000);
  $tp = count($total_user);
  if ($tp %  $data_limit == 0) {
    $tp = $tp / $data_limit;
  } else {
    $tp = floor($tp / $data_limit) + 1;
  }
  $q = null;
  if (isset($req->q)) {
    $q = $req->q;
  }
  $users = user_list($keywords = $q, $ord = "DESC", $limit = $page_limit, $active = 1);
  return (object) array(
    'req' => obj($req),
    'total_users' => $tp,
    'current_page' => $cp,
    'users' => $users
  );
}
function user_list($keywords = null, $ord = "DESC", $limit = 1, $active = 1)
{
  $cntobj = new Model('pk_user');
  $users = $cntobj->filter_index(array('is_active' => $active), $ord, $limit);
  if ($keywords != "") {
    $users = $cntobj->search(
      assoc_arr: array(
        'username' => $keywords,
        'email' => $keywords,
        'mobile' => $keywords,
        'first_name' => $keywords,
        'last_name' => $keywords,
        'zipcode' => $keywords,
        'city' => $keywords,
        'state' => $keywords,
        'country' => $keywords,
        'country_code' => $keywords
      ),
      whr_arr: array('is_active' => $active),
      ord: $ord,
      limit: $limit,
      change_order_by_col: 'id'
    );
  }
  $user_list = [];
  foreach ($users as $user) {
    $user = obj($user);
    $user_list[] = [
      'id' => $user->id,
      'username' => $user->username,
      'email' => $user->email,
      'created_at' => $user->created_at,
      'status' => $user->status,
      'ref' => $user->ref
    ];
  }
  return $user_list;
}
function updateUserDetails($data)
{
  $userexist = false;
  $useremailexists = false;
  $user = getData('pk_user', $data['userid']);
  if ($user == false) {
    $_SESSION['msg'][] = 'User does not exist';
    return false;
  }
  if (isset($data['username'])) {
    $unme = strtolower(str_replace(" ", "", $data['username']));
    $arrcheck['username'] = $unme;
    if (strlen($arrcheck['username']) > 2) {
      $userexist = (new Model('pk_user'))->exists($arrcheck);
    }
    if ($userexist == false) {
      $arr['username'] = $unme;
    } else {
      if ($unme != $user['username']) {
        $_SESSION['msg'][] = 'Username already existed';
      }
    }
  }
  if (isset($data['password']) && $data['password'] != "" && isset($data['change_password'])) {
    $arr['password'] = md5(trim($data['password']));
    $_SESSION['msg'][] = 'Password changed';
  }
  if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $email = $data['email'];
    $arremlcheck['email'] = $email;
    $useremailexists = (new Model('pk_user'))->exists($arremlcheck);
    if ($useremailexists == false) {
      $arr['email'] = $email;
    } else {
      if ($email != $user['email']) {
        $_SESSION['msg'][] = 'Email already existed';
      }
    }
  }
  if (isset($arr)) {
    (new Model('pk_user'))->update($data['userid'], $arr);
    $_SESSION['msg'][] = 'User updated';
    return true;
  } else {
    $_SESSION['msg'][] = 'No change detected';
    return false;
  }
}

// function getPosition($level)
// {
//   if ($level >= 0 && $level < 500) {
//     return "AFFILIATE Partner";
//   } elseif ($level >= 500 && $level < 1000) {
//     return "BRONZE Manager";
//   } elseif ($level >= 1000 && $level < 2500) {
//     return "SILVER Manager";
//   } elseif ($level >= 2500 && $level < 5000) {
//     return "GOLD Manager";
//   } elseif ($level >= 5000 && $level < 10000) {
//     return "PLATINUM Manager";
//   } elseif ($level >= 10000 && $level < 25000) {
//     return "DIRECTOR";
//   } elseif ($level >= 25000 && $level < 50000) {
//     return "TEAM DIRECTOR";
//   } elseif ($level >= 50000 && $level < 100000) {
//     return "MARKETING DIRECTOR";
//   } elseif ($level >= 100000 && $level < 250000) {
//     return "DIAMOND";
//   } elseif ($level >= 250000 && $level < 500000) {
//     return "BLUE DIAMOND";
//   } elseif ($level >= 500000 && $level < 1000000) {
//     return "PURPLE DIAMOND";
//   } elseif ($level >= 1000000 && $level < 2000000) {
//     return "GREEN DIAMOND";
//   } elseif ($level >= 2000000 && $level < 4000000) {
//     return "AMBASSADOR";
//   } elseif ($level >= 4000000 && $level < 8000000) {
//     return "ROYAL";
//   } elseif ($level >= 8000000 && $level < 16000000) {
//     return "ROYAL I";
//   } elseif ($level >= 16000000) {
//     return "ROYAL II";
//   } else {
//     return "No RV points to get a position";
//     // return "Need " . 50 - $level . ' more RV to become AFFILIATE Partner';
//   }
// }
function getPosition($level)
{
  if ($level >= 0 && $level < 500) {
    return "affiliate partner";
  } elseif ($level >= 500 && $level < 1000) {
    return "bronze manager";
  } elseif ($level >= 1000 && $level < 2500) {
    return "silver manager";
  } elseif ($level >= 2500 && $level < 5000) {
    return "gold manager";
  } elseif ($level >= 5000 && $level < 10000) {
    return "platinum manager";
  } elseif ($level >= 10000 && $level < 25000) {
    return "director";
  } elseif ($level >= 25000 && $level < 50000) {
    return "team director";
  } elseif ($level >= 50000 && $level < 100000) {
    return "marketing director";
  } elseif ($level >= 100000 && $level < 250000) {
    return "diamond";
  } elseif ($level >= 250000 && $level < 500000) {
    return "blue diamond";
  } elseif ($level >= 500000 && $level < 1000000) {
    return "purple diamond";
  } elseif ($level >= 1000000 && $level < 2000000) {
    return "green diamond";
  } elseif ($level >= 2000000 && $level < 4000000) {
    return "ambassador";
  } elseif ($level >= 4000000 && $level < 8000000) {
    return "royal";
  } elseif ($level >= 8000000 && $level < 16000000) {
    return "royal i";
  } elseif ($level >= 16000000) {
    return "royal ii";
  } else {
    return "no rv points to get a position";
    // return "need " . 50 - $level . ' more RV to become affiliate partner';
  }
}

function liveWallet($userid)
{
  $db = new Dbobjects;
  // $sql = "select SUM(amt) as total_amt from credits where user_id = {$userid} and status = 'lifetime'";
  // $cmsn = $db->show($sql);
  $lifetime_m = old_data('commission', $userid);
  $lifetime_m += old_data('direct_bonus', $userid);
  $pv = new Pv_ctrl;
  $lifetime_m  += $pv->my_lifetime_commission_sum($userid);
  $lifetime_m  += my_all_share($userid);
  $lifetime_m +=  (new Pv_ctrl)->my_lifetime_direct_bonus_sum($userid);
  ###############################################
  $sql = "select SUM(amt) as total_amt from credits where status = 'paid' and remark='confirmed' and user_id = {$userid}";
  $cmsn = $db->show($sql);
  $tm_paid = $cmsn[0]['total_amt'] ? $cmsn[0]['total_amt'] : 0;
  $amt_left = $lifetime_m - $tm_paid;
  return array(
    'lifetime_amt' => $lifetime_m,
    'amt_paid' => $tm_paid,
    'amt_left' => $amt_left
  );
}

function transactionAmt($data)
{
  $dbmny = new Dbobjects;
  $pdo = $dbmny->dbpdo();
  try {
    // Begin the transaction
    $pdo->beginTransaction();


    $sql = "select SUM(amt) as total_amt from credits where user_id = {$data['user']} and status = 'lifetime'";
    $cmsn = $dbmny->show($sql);
    $lifetime_m = $cmsn[0]['total_amt'] ? $cmsn[0]['total_amt'] : 0;
    # find total paid amt
    $sql = "select SUM(amt) as total_amt from credits where user_id = {$data['user']} and status = 'paid'";
    $cmsn = $dbmny->show($sql);
    $total_paid = $cmsn[0]['total_amt'] ? $cmsn[0]['total_amt'] : 0;
    $amntwd = abs($data['money_out']);
    if ((($lifetime_m - $total_paid) >= $amntwd) && $amntwd >= 10) {
      // Perform your database operations within the transaction
      // ...
      $sql = "insert into credits (user_id, amt, status) values({$data['user']},$amntwd,'paid')";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      // $lastid =  $pdo->lastInsertId();
      // Commit the transaction
      $pdo->commit();
    } else {
      $pdo->rollBack();
    }
  } catch (PDOException $e) {
    // An error occurred, rollback the transaction
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
  }
}
function sponser_username($ref_id)
{
  $sponser = null;
  $spObj = (new Dbobjects)->show("select username from pk_user where pk_user.id = $ref_id");
  if (count($spObj) > 0) {
    $sponser = $spObj[0]['username'];
  }
  return $sponser;
}
function update_inv_if_not($id, $invid, $dbobj = null)
{
  $updated_at = date('Y-m-d H:i:s');
  if ($dbobj == null) {
    (new Dbobjects)->show("update payment set invoice = $invid, updated_at='$updated_at' where payment.id = $id and (invoice IS NULL OR invoice = '')");
  } else {
    $dbobj->show("update payment set invoice = $invid, updated_at='$updated_at' where payment.id = $id and (invoice IS NULL OR invoice = '')");
  }
}


function get_order_details($orderid)
{
  $ord = [];
  $db = new Dbobjects;
  $pmtObj = ($db)->show("select * from payment where payment.id = $orderid");
  if (count($pmtObj) > 0) {
    $ord['payment'] = $pmtObj[0];
    $cartObj = ($db)->show("select * from customer_order where customer_order.payment_id = $orderid");
    if (count($cartObj) > 0) {
      foreach ($cartObj as $cart) {
        $itemObj = ($db)->show("select * from item where item.id = {$cart['item_id']}")[0];
        $jsn = json_decode($itemObj['jsn']);
        if ($cart['jsn'] != null && $cart['jsn'] != '') {
          $jsns = json_decode($cart['jsn']);
          $cart_jsn = $jsns->items;
        } else {
          $cart_jsn = $jsn->items;
        }

        if (isset($jsn->items)) {
          $ord['cart'][] = array(
            'package' => $cart,
            // 'products' => $jsn->items,
            'products' => $cart_jsn
          );
        }
      }
    }
  }
  return $ord;
}
function select_col($table, $id, $col)
{
  $spObj = (new Dbobjects)->show("select $col from $table where $table.id = $id");
  if (count($spObj) > 0) {
    return $spObj[0][$col];
  }
  return null;
}
function generate_id($item_group = 'product')
{
  $spObj = (new Dbobjects)->show("SELECT COALESCE(MAX(product_id), 0) + 1 as product_id FROM item WHERE item_group = '$item_group';");
  return $spObj[0]['product_id'];
}
function get_shipping_address($userid)
{
  $spObj = (new Dbobjects)->show("select * from address where address_type='primary' and user_id = $userid");
  if (count($spObj) > 0) {
    return $spObj[0];
  }
  return null;
}
function generate_invoice_id($dbobj = null)
{
  if ($dbobj == null) {
    $spObj = (new Dbobjects)->show("SELECT COALESCE(MAX(invoice), 7000) + 1 as invoice FROM payment;");
    return $spObj[0]['invoice'];
  } else {
    $spObj = $dbobj->show("SELECT COALESCE(MAX(invoice), 7000) + 1 as invoice FROM payment;");
    return $spObj[0]['invoice'];
  }
}

function getCountryData($countryCode)
{
  // JSON array containing country data
  $json_data = file_get_contents(RPATH . "/jsondata/country.json");
  $countries = json_decode($json_data);
  // Iterate over the array to find the matching country
  foreach ($countries as $country) {
    if ($country->code === $countryCode) {
      return $country;
    }
  }
  // Country not found
  return null;
}

function my_rv_and_admin_rv($user_id, $dbobj = null)
{
  $total_rv = 0;
  if ($dbobj == null) {
    $dbobj = new Dbobjects;
    $sql = "SELECT SUM(rv) AS rv FROM payment WHERE user_id = $user_id and status = 'paid' AND (invoice IS NOT NULL AND invoice <> '')";
    $rv_own_orders = $dbobj->show($sql)[0]['rv'];
    $sql = "select SUM(rv) as rv from rank_advance where added_to = {$user_id} and status = 'confirmed' and rv > 0;";
    $rv_by_admin = $dbobj->show($sql)[0]['rv'];
    $total_rv = $rv_own_orders + $rv_by_admin;
  } else {
    $sql = "SELECT SUM(rv) AS rv FROM payment WHERE user_id = $user_id and status = 'paid' AND (invoice IS NOT NULL AND invoice <> '')";
    $rv_own_orders = $dbobj->show($sql)[0]['rv'];
    $sql = "select SUM(rv) as rv from rank_advance where added_to = {$user_id} and status = 'confirmed' and rv > 0;";
    $rv_by_admin = $dbobj->show($sql)[0]['rv'];
    $total_rv = $rv_own_orders + $rv_by_admin;
  }
  return $total_rv;
}
function my_old_rv($user_id, $db = null)
{
  return old_data('rank_advance', $user_id, $db = null);
}
// function my_old_rv($user_id, $dbobj = null)
// {
//   $total_rv = 0;
//   if ($dbobj == null) {
//     $dbobj = new Dbobjects;
//     $sql = "select SUM(old_rv) as old_rv from rank_advance where added_to = {$user_id} and status = 'confirmed' and old_rv > 0";
//     $rv_old = $dbobj->show($sql)[0]['old_rv'];
//     $total_rv = $rv_old;
//   } else {
//     $sql = "select SUM(old_rv) as old_rv from rank_advance where added_to = {$user_id} and status = 'confirmed' and old_rv > 0";
//     $rv_old = $dbobj->show($sql)[0]['old_rv'];
//     $total_rv = $rv_old;
//   }
//   return $total_rv;
// }

function createFolderIfNeeded($folderPath, $permissions = 0755)
{
  // Check if the folder already exists
  if (!file_exists($folderPath) || !is_dir($folderPath)) {
    // Create the folder if it doesn't exist
    mkdir($folderPath, $permissions, true); // The third parameter 'true' creates parent directories if needed
  } else {
    // If the folder already exists, set the permissions (if not already set)
    if (substr(sprintf('%o', fileperms($folderPath)), -4) !== sprintf('%04o', $permissions)) {
      chmod($folderPath, $permissions);
    }
  }
}

function last_active_date($user_id)
{
  $user = getData('pk_user', $user_id);
  $reg_date = $user['created_at'];
  $pmt = new Model('payment');
  $lastpamt = $pmt->filter_index($assoc_arr = ['user_id' => $user['id'], 'status' => 'paid'], $ord = 'desc', $limit = 1, $change_order_by_col = 'created_at');
  $lp = count($lastpamt) > 0 ? $lastpamt[0]['created_at'] : $reg_date;
  return $lp;
}

function my_realtime_all_share($userid)
{
  $shrobj = new Dbobjects;
  $shrobj->tableName = "shares";
  $shre = $shrobj->filter(['user_id' => $userid, 'is_active' => 1]);
  $allshr = 0.0;
  foreach ($shre as $key => $srcnsn) {
    $srcnsn = obj($srcnsn);
    $jsnsh = json_decode($srcnsn->jsn);
    if (isset($jsnsh->shares)) {
      $allshr += $jsnsh->shares->pool1 + $jsnsh->shares->pool2 + $jsnsh->shares->pool3 + $jsnsh->shares->pool4;
    }
  }
  return $allshr;
}
function my_all_share($userid)
{
  $shrobj = new Dbobjects;
  $amt = (object) $shrobj->showOne("select SUM(amt) as amt from credits where status='share' and user_id = '$userid'");
  $allshr = $amt->amt!=null?$amt->amt:0.0;
  return $allshr;
}
function my_all_share_count($userid)
{
  $shrobj = new Dbobjects;
  $endOfLastMonth = date('Y-m-t', strtotime('last month'));
  
  $sql = "SELECT * FROM shares WHERE user_id = '$userid' and is_active=1 and date_to <= '$endOfLastMonth';";
  // echo $sql;
  $shre = $shrobj->show($sql);
  // $shrobj->tableName = "shares";
  // $shre = $shrobj->filter(['user_id' => $userid, 'is_active' => 1]);
  $allshr = 0.0;
  $allshr_cnt = 0;
  foreach ($shre as $key => $srcnsn) {
    $srcnsn = obj($srcnsn);
    $jsnsh = json_decode($srcnsn->jsn);
    if (isset($jsnsh->share_count)) {
      $allshr_cnt += $jsnsh->share_count->pool1 + $jsnsh->share_count->pool2 + $jsnsh->share_count->pool3 + $jsnsh->share_count->pool4;
    }
  }
  return $allshr_cnt;
}
function all_user_share_count()
{
  $shrobj = new Dbobjects;
  $endOfLastMonth = date('Y-m-t', strtotime('last month'));
  $sql = "SELECT * FROM shares WHERE is_active=1 and date_to <= '$endOfLastMonth';";
  $shre = $shrobj->show($sql);
  // $shrobj->tableName = "shares";
  // $shre = $shrobj->filter(['is_active' => 1]);
  $allshr = 0.0;
  $allshr_cnt = 0;
  foreach ($shre as $key => $srcnsn) {
    $srcnsn = obj($srcnsn);
    $jsnsh = json_decode($srcnsn->jsn);
    if (isset($jsnsh->share_count)) {
      $allshr_cnt += $jsnsh->share_count->pool1 + $jsnsh->share_count->pool2 + $jsnsh->share_count->pool3 + $jsnsh->share_count->pool4;
    }
    if (isset($jsnsh->shares)) {
      $allshr += $jsnsh->shares->pool1 + $jsnsh->shares->pool2 + $jsnsh->shares->pool3 + $jsnsh->shares->pool4;
    }
  }
  return array('share_count' => $allshr_cnt, 'share_value' => $allshr);
}
function my_last_month_share($userid)
{
  $curdate = new DateTime();

  // Clone the current date to a new object for the "from" date
  $fromDate = clone $curdate;
  $from = $fromDate->modify('-1 month')->format('Y-m-01');
  // Clone the current date to another object for the "to" date
  $toDate = clone $curdate;
  $to = $toDate->modify('-1 month')->format('Y-m-t');

  $shrobj = new Dbobjects;
  $shrobj->tableName = "shares";
  $shre = $shrobj->filter(['user_id' => $userid, 'date_from' => $from, 'date_to' => $to, 'is_active' => 1]);
  $allshr = 0.0;
  foreach ($shre as $key => $srcnsn) {
    $srcnsn = obj($srcnsn);
    $jsnsh = json_decode($srcnsn->jsn);
    if (isset($jsnsh->shares)) {
      $allshr += $jsnsh->shares->pool1 + $jsnsh->shares->pool2 + $jsnsh->shares->pool3 + $jsnsh->shares->pool4;
    }
  }
  return $allshr;
}

function email_has_valid_dns($email = 'email@example.com'): bool
{
  return (new Email_validator_lcl)->validate($email);
}
function day_check($openings, $day)
{
  $defaultTime = '00:00'; // Default time if the day is not found
  $day = strtolower($day); // Convert the input day to lowercase for case-insensitive matching

  foreach ($openings as $timing) {
    if ($timing['day'] === $day) {
      return (object)[
        'open_day' => true,
        'open_time' => $timing['open'],
        'close_time' => $timing['close']
      ];
    }
  }
}
function new_order_email($obj)
{
  if ($obj->email != null) {
    import(
      "apps/view/components/emails/order_confirmation.php",
      (object) array(
        "email" => $obj->email,
        "order_id" => $obj->order_id,
        "bank_account" => nl2br($obj->bank_account),
        "order_amt" => $obj->order_amt
      )
    );
  }
}
function de_activation_warning_email($obj)
{
  if ($obj->email != null) {
    import(
      "apps/view/components/emails/de-activation-information.php",
      (object) array(
        "email" => $obj->email
      )
    );
  }
}

function am_i_active($userid)
{
  $days = days_left(last_active_date($userid));
  if ($days >= 0) {
    return $days;
  } else {
    return false;
  }
}

function checkActivation($userid, $productId, object $cart)
{

  // Check if the user already has the same product in the cart
  $sql = "SELECT id, qty FROM customer_order WHERE user_id = $userid AND product_id = $productId";
  $existingProduct = $cart->show($sql);

  if (count($existingProduct) > 0) {
    // User has already purchased the same product, prevent adding
    echo js_alert('You have already purchased this product.');
    return false;
  }

  if ($productId === 8) {
    // Check if user has a higher-tier product (Gold) in the cart
    $higherTierProductId = 8;

    $sql = "SELECT id, qty FROM customer_order WHERE user_id = $userid AND product_id = $higherTierProductId";
    $higherTierProduct = $cart->show($sql);

    if (count($higherTierProduct) > 0) {
      return true;
    } else {
      // User does not have a higher-tier product, prevent adding Bronze
      echo js_alert('You can only purchase Bronze if you do not have a higher-tier product.');
      return false;
    }
  } elseif ($productId === 9) {
    // Check if user has a lower-tier product (Bronze) in the cart
    $lowerTierProductId = 10;

    $sql = "SELECT id, qty FROM customer_order WHERE user_id = $userid AND product_id = $lowerTierProductId";
    $lowerTierProduct = $cart->show($sql);

    if (count($lowerTierProduct) > 0) {
      // Downgrading is not allowed
      echo js_alert('Downgrading to a lower tier is not allowed.');
      return;
    }
  }

  // Allow the user to purchase the product
  // ...

}
function old_data($key_name = "direct_bonus", $userid = 0, $db = null)
{
  if ($db == null) {
    $db = new Dbobjects;
  }

  $sql = "select SUM(key_value) as $key_name from old_data where user_id = $userid and key_name='$key_name'";
  $dbqry = $db->show($sql);
  return count($dbqry) > 0 ? $dbqry[0][$key_name] : 0;
}

function structure_tree($data)
{
  $output = null;

  foreach ($data as $item) {
    $mmbrcnt = count($item['tree']);
    $text_muted = $mmbrcnt == 0 ? 'text-muted' : 'text-bold has-members';
    $partners = $mmbrcnt > 1 ? 'partners' : 'partner';
    $output .= '<li>';
    $output .= "<span class='caret $text_muted'>" . $item['username'] . " - (" . count($item['tree']) . " $partners)</span>";

    if (!empty($item['tree'])) {
      $output .= '<ul class="nested">';
      $output .= structure_tree($item['tree']);
      $output .= '</ul>';
    }

    $output .= '</li>';
  }
  return $output;
}
// use in package to sum grams of total product in a package
function calculate_gram(Object $item, float $qty)
{
  $total_gm = 0;
  switch ($item->unit) {
    case "g":
      $total_gm = $item->qty * $qty;
      break;
    case "kg":
      $total_gm = $item->qty * $qty * 1000;
      break;
    case "lb":
      // Convert pounds to grams (1 lb = 453.592 grams)
      $total_gm = $item->qty * $qty * 453.592;
      break;
    case "oz":
      // Convert ounces to grams (1 oz = 28.3495 grams)
      $total_gm = $item->qty * $qty * 28.3495;
      break;
  }
  return $total_gm;
}
// calculate shipping charges if gram and country code is available
function calculate_shipping_cost($db = new Dbobjects, $gram=0, $ccode='')
{
  $cost = 0;
  $shp = (object)$db->showOne("select shipping from countries where code = '$ccode';");
  if ($shp != '') {
    $shpng = json_decode($shp->shipping??'[]');
    if (isset($shpng->shipping_cost)) {
      $shpcost = $shpng->shipping_cost;
      if ($gram >= 0 &&  $gram < 1001) {
        $cost = isset($shpcost->f0t1001) ? $shpcost->f0t1001 : 0;
      } elseif ($gram >= 1001 &&  $gram < 7001) {
        $cost = isset($shpcost->f1001t7001) ? $shpcost->f1001t7001 : 0;
      } elseif ($gram >= 7001 &&  $gram < 15001) {
        $cost = isset($shpcost->f7001t15001) ? $shpcost->f7001t15001 : 0;
      } elseif ($gram >= 15001) {
        $cost = isset($shpcost->f15001t31001) ? $shpcost->f15001t31001 : 0;
      }
    }
  }
  return $cost;
}
