<?php
// import("apps/view/inc/header.php");
// import("apps/view/inc/navbar.php");
if (!authenticate()) {
  die('Login required');
}
if (!isset($_GET['tid'])) {
  die('Transaction id is required');
}
if (!intval($_GET['tid'])) {
  die('Transaction id is invalid');
}
$unpaid_cmsn = getData('credits', intval($_GET['tid']));
if ($unpaid_cmsn == false) {
  die('Data not found in database');
}

$remark = 'requested';
if (isset($_GET['remark'])) {
  $remark = $_GET['remark'];
} else {
  $remark = 'requested';
}

$cms = obj($unpaid_cmsn);
// myprint($cms);
$db = new Dbobjects;
$sql = "SELECT * FROM pk_user WHERE pk_user.id = $cms->user_id;";
$payeedata = $db->show($sql);
$payee = null;
if (count($payeedata) > 0) {
  $payee = obj($payeedata[0]);
}
$bank_account = null;
$bank_name = null;
$swift_code = null;
$iban = null;
$country_code = null;
$bank_country_name = null;
$flag = null;
$jsnob = $payee->jsn;
$jsn = json_decode($jsnob);
// if (isset($jsn->banks)) {
//   $bank = isset($jsn->banks[0]->bank) ? $jsn->banks[0]->bank : null;
//   $country_code = isset($jsn->banks[0]->country_code) ? $jsn->banks[0]->country_code : null;
//   $bank_country = isset($jsn->banks[0]->country_name) ? $jsn->banks[0]->country_name : null;
//   $flag = isset(getCurrency($country_code)['flag'])?getCurrency($country_code)['flag']:null;
// }
if (isset($jsn->banks)) {
  $bank_account = isset($jsn->banks[0]->bank_account) ? $jsn->banks[0]->bank_account : null;
  $bank_name = isset($jsn->banks[0]->bank_name) ? $jsn->banks[0]->bank_name : null;
  $swift_code = isset($jsn->banks[0]->swift_code) ? $jsn->banks[0]->swift_code : null;
  $iban = isset($jsn->banks[0]->iban) ? $jsn->banks[0]->iban : null;
  $country_code = isset($jsn->banks[0]->country_code) ? $jsn->banks[0]->country_code : null;
  $bank_country_name = isset($jsn->banks[0]->country_name) ? $jsn->banks[0]->country_name : null;
  $flag = isset(getCurrency($country_code)['flag']) ? getCurrency($country_code)['flag'] : null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Commission Payment Invoice</title>
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
</head>

<body>
<section style="min-height: 100vh;">
<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header text-white <?php echo $cms->remark=='cancelled'?'bg-danger':'bg-primary'; ?>">
            <h4 class="m-0">Commission Payment Invoice</h4>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-6">
                <h5>Transaction Number:</h5>
                <p><?php echo $cms->id; ?></p>
              </div>
              <div class="col-6 text-end">
                <h5>Date:</h5>
                <p><?php echo $cms->calculated_on; ?></p>
              </div>
            </div>
            <hr>
            <div class="row mb-3">
              <div class="col-6">
                <h5>Client Name:</h5>
                <p>Username and ID:<?php echo $payee ? $payee->username : "NA"; ?></p>
                <p>Email:<?php echo $payee ? $payee->email : "NA"; ?></p>
              </div>
              <div class="col-6 text-end">
                <h5>Client ID:</h5>
                <p><?php echo $payee ? "$payee->username [$payee->id]" : "NA"; ?></p>
                <hr>

                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td>Bank Account number</td>
                      <td><?php echo $bank_account; ?></td>
                    </tr>
                    <tr>
                      <td>Bank Name</td>
                      <td><?php echo $bank_name; ?></td>
                    </tr>
                    <tr>
                      <td>IBAN</td>
                      <td><?php echo $iban; ?></td>
                    </tr>
                    <tr>
                      <td>Swift code</td>
                      <td><?php echo $swift_code; ?></td>
                    </tr>
                    <tr>
                      <td>Country</td>
                      <td><?php echo $bank_country_name; ?></td>
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>
            <hr>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Commission</th>
                    <th>Request status</th>

                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?php echo $cms->amt; ?></td>
                    <td class="<?php echo $cms->remark=='cancelled'?'text-danger':null; ?>"><?php echo $cms->remark; ?></td>
                  </tr>

                  <!-- Add more rows for other products if needed -->
                </tbody>

              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="text-center mt-4">
          <button class="btn text-white <?php echo $cms->remark=='cancelled'?'btn-danger':'btn-primary'; ?>" onclick="window.print();">Print Invoice</button>
        </div>
      </div>
    </div>
  </div>
</section>
</body>

</html>