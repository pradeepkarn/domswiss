<?php

use PHPMailer\PHPMailer\PHPMailer;

$message = <<<MSG
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Confirmation</title>
</head>

<body>
  <div style="max-width: 100%; margin: 0 auto; font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Vielen Dank für die Bestellung!</h2>
    <p>Deine Bestellung mit der Nummer <strong>$context->order_id</strong> wurde erfolgreich verbucht.</p>
    <p>Gesamtsumme der Bestellung beträgt: <strong>€ $context->order_amt</strong></p>
    <p>Bitte überweise die Gesamtsumme auf das folgende Konto:</p>
    $context->bank_account
    <p>Bitte bei Zahlungsreferenz "<strong>Order Nr. $context->order_id</strong>" angeben.</p>
   
    <p>Zahlung ausgeführt</p>
    <p>You can log in as a reseller partner at DOM-Swiss.</p>
    <p>Best regards,</p>
    <p>DOM-Swiss Backoffice-Team</p>

  <hr>
  
    <h2>Thank you for your order!</h2>
    <p>Your order with the number <strong>$context->order_id</strong> has been successfully processed.</p>
    <p>Total amount of the order is: <strong>€ $context->order_amt</strong></p>
    <p>Please transfer the total amount to the following bank account:</p>
    $context->bank_account
    <p>Please use "<strong>Order Nr. $context->order_id</strong>" as payment reference.</p>

    <p>Payment executed</p>
    <p>You can log in as a reseller partner at DOM-Swiss.</p>

    <p>Best regards,</p>
    <p>DOM-Swiss Backoffice-Team</p>
</div>

</body>

</html>

MSG;

$mail = php_mailer(new PHPMailer());
$mail->isHTML(true);
$mail->Subject = 'DOM SWISS: Order Placed!!!';
$mail->Body = $message;
$mail->setFrom(orderemail, SITE_NAME . "-New Order");
if (!email_has_valid_dns($context->email)) {
  $_SESSION['msg'][] = "Invalid email, mail not sent to customer: $context->email";
}
$mail->addAddress($context->email, $context->email);

if (!email_has_valid_dns($context->email)) {
  $_SESSION['msg'][] = "Invalid email, mail not sent to customer: $context->email";
}
$mail->addAddress($context->email, $context->email);

emaillog($msg = "============== Order Email sending task start ===============");

try {
  $mail->send();
  $msg = "| Success: Order email sent to partner successfully {$context->email}";
  emaillog($msg);
  emaillog($msg = "================== End ==================\n");
  return true;
} catch (ErrorException $e) {
  $msg = "| Failed: Order email not sent {$context->email}";
  emaillog($msg);
  emaillog($msg = "================== End ==================\n");
  return false;
}

