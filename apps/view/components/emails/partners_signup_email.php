<?php

use PHPMailer\PHPMailer\PHPMailer;

// $from_email = email;
// $subject = "Welcome to Domswiss";

// Set the email headers
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
<h2>Congratulations!</h2>

<p>Your team is growing and a new direct partner has registered:</p>

<p><strong>Name:</strong> $context->name<br>
<strong>Username:</strong> $context->username</p>

<p>Please support your new partner for your mutual success!</p>

<p>You can log in and view your team at <a href="www.partners.domswiss.me">www.partners.domswiss.me</a>.</p>

<p><strong>Vital greetings,</strong></p>

<p>Your DOM-Swiss back office team</p>

  <hr>
  
  <h2>Herzlichen Glückwunsch!</h2>

  <p>Dein Team wächst und ein neuer direkter Partner hat sich registriert:</p>
  
  <p><strong>Name:</strong> $context->name <br>
  <strong>Username:</strong> $context->username</p>
  
  <p>Bitte unterstütze deinen neuen Partner für euren gemeinsamen Erfolg!</p>
  
  <p>Du kannst dich einloggen und dein Team unter <a href="www.partners.domswiss.me">www.partners.domswiss.me</a> ansehen.</p>
  
  <p><strong>Vitale Grüße,</strong></p>
  
  <p>Dein DOM-Swiss Backoffice-Team</p>
  
</body>
</html>

MSG;

$mail = php_mailer(new PHPMailer());
$mail->isHTML(true);
$mail->Subject = 'Congratulations!!!';
$mail->Body = $message;
$mail->setFrom(email, SITE_NAME."-Partner registration");  
if (!email_has_valid_dns($context->email)) {
  // $mail->addAddress($context->partner_email, $context->partner_email);
  $_SESSION['msg'][] = "Invalid email, mail not sent to customer: $context->email";
}
$mail->addAddress($context->email, $context->email);

emaillog($msg = "============== $context->email Email sending task start ===============");

try {
  $mail->send();
  $msg = "| Success: Partner Registration Email sent to partner successfully {$context->email}";
  emaillog($msg);
  return;
} catch (ErrorException $e) {
  $msg = "| Failed: Partner Registration Email not sent {$context->email}";
  emaillog($msg);
  return;
}
emaillog($msg = "================== End ==================\n"); 
// $eml =  array('email' => $context->email);



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