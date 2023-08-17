<?php

use PHPMailer\PHPMailer\PHPMailer;

// $from_email = email;
// $subject = "Welcome to Domswiss";

// // Set the email headers
// $headers = "MIME-Version: 1.0" . "\r\n";
// $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
// $headers .= "From: $from_email" . "\r\n";
$message = <<<MSG
<!DOCTYPE html>
<html>
<head>
  <title>Welcome to DOM Swiss</title>
</head>
<body>
  <h2>It's time. Get started with us now!</h2>
  
  <p>If you want to write history with us and make a difference yourself, then take this really unique opportunity.</p>
  
  <p>If this is important to you:</p>
  <ul>
    <li>Your health and that of your loved ones</li>
    <li>A sustainable really good income</li>
    <li>Career without risk</li>
  </ul>
  
  <p>... and would like to get actively involved with us in one of the fastest growing markets, then log in now and pass this opportunity on!</p>
  
  <p><strong>The login details are:</strong></p>
  <p>Username: $context->username</p>
  <p>Password: $context->password</p>
  
  <p>You can log in as a reseller partner at <a href="http://www.partners.domswiss.me">www.partners.domswiss.me</a></p>
  
  <p>Best regards,<br>Your DOM Swiss back office team</p>

  <hr>
  
  <h2>Herzlichen Glückwunsch!</h2>
  
  <p>Dein Team wächst und ein neuer direkter Partner hat sich registriert:</p>
  
  <p>Username: $context->username</p>
  <p>Password: $context->password</p>
  
  <p>Bitte unterstütze deinen neuen Partner für euren gemeinsamen Erfolg!</p>
  
  <p>Unter <a href="http://www.partners.domswiss.me">www.partners.domswiss.me</a> kannst du dich einloggen und dein Team ansehen.</p>
  
  <p>Vitale Grüße,<br>Dein DOM-Swiss Backoffice-Team</p>
</body>
</html>

MSG;

$mail = php_mailer(new PHPMailer());
$mail->isHTML(true);
$mail->Subject = 'Congratulations!!!';
$mail->Body = $message;
$mail->setFrom(email, SITE_NAME."-Registration");  
if (!email_has_valid_dns($context->email)) {
  // $mail->addAddress($context->partner_email, $context->partner_email);
  $_SESSION['msg'][] = "Invalid email, mail not sent to customer: $context->email";
}
$mail->addAddress($context->email, $context->email);

emaillog($msg = "============== Email sending task start ===============");

try {
  $mail->send();
  $msg = "| Success: Registration Email sent to partner successfully {$context->email}";
  emaillog($msg);
  return;
} catch (ErrorException $e) {
  $msg = "| Failed: Registration Email not sent {$context->email}";
  emaillog($msg);
  return;
}
emaillog($msg = "================== End ==================\n"); 
// $eml =  array('email' => $context->email);

// emaillog($msg = "============== Email sending task start ===============");

// $vl =   (object) $eml;
// $to = $vl->email;
// try {
//   if (mail($to, $subject, $message, $headers)) {
//     $msg = "| Success: Registration Email sent successfully {$to}";
//     emaillog($msg);
//   } else {
//     $msg = "| Failed: Registration Email not sent {$to}";
//     emaillog($msg);
//   }
// } catch (\Throwable $th) {
//   $msg = "| Error: $th while sending $to";
//   emaillog($msg);
// }

// emaillog($msg = "================== End ==================\n"); 

// mail($to, $subject, $message, $headers);
// echo $message;