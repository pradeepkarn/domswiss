<?php

use PHPMailer\PHPMailer\PHPMailer;

$message = <<<MSG
<!DOCTYPE html>
<html>
<head>
    <title>Notification</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; font-size: 14px;">
        <p>Hallo africa,</p>

        <p>vorsorglich informieren wir dich, dass dein DOM-Swiss - Aktivitäts-Status in 3 Tagen ausläuft.
        Überlege, welche Produkte du für dich oder deine Kunden nutzen kannst,
        um deine Bestellung auszuführen und damit weitere 28 Tage provisionsberechtigt zu sein.</p>

        <p>Vitale Grüße<br>
        Dein DOM-Swiss Backoffice-Team</p>

        <p>Hello africa,</p>

        <p>as a precaution, we inform you that your DOM-Swiss activity status will expire in 3 days.
        Think about which products you can use for yourself or your customers,
        to fulfill your order and be eligible for commission for an additional 28 days.</p>

        <p>Vital greetings<br>
        Your DOM-Swiss backoffice team</p>
    </div>
</body>
</html>


MSG;
// $context->email = 'mail2pkarn@gmail.com';
$mail = php_mailer(new PHPMailer());
$mail->isHTML(true);
$mail->Subject = 'DOM SWISS: Account de-activation information!!!';
$mail->Body = $message;
$mail->setFrom(email, SITE_NAME . "Info - Account de-activation");
if (!email_has_valid_dns($context->email)) {
  $_SESSION['msg'][] = "Invalid email, mail not sent to customer: $context->email";
}
$mail->addAddress($context->email, $context->email);

if (!email_has_valid_dns($context->email)) {
  $_SESSION['msg'][] = "Invalid email, mail not sent to customer: $context->email";
}
$mail->addAddress($context->email, $context->email);

emaillog(file_name:'de-activation-email-log',msg:$msg = "============== Deactivation Email sending task start ===============");

try {
  $mail->send();
  $msg = "| Success: Account de-activation warning email sent to partner successfully {$context->email}";
  emaillog(file_name:'de-activation-email.log',msg:$msg);
  emaillog(file_name:'de-activation-email.log',msg:$msg = "================== End ==================\n");
  (new Model('warning_emails'))->store(['email'=>$context->email,'status'=>'sent','created_at'=>date('Y-m-d H:i:s')]);
  return true;
} catch (ErrorException $e) {
  $msg = "| Failed: Account de-activation warning email not sent {$context->email}";
  emaillog(file_name:'de-activation-email.log',msg:$msg);
  emaillog(file_name:'de-activation-email.log',msg:$msg = "================== End ==================\n");
  return false;
}

