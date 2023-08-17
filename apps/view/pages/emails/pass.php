

<?php 
$from_email = email;
$subject = "Password reset";

// Set the email headers
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: $from_email" . "\r\n";
$message = <<<MSG
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$ctxObj->head->title}</title>
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
            <p class="text-center">{$ctxObj->link}</p>
        </div>
    </div>
    </div>
</body>
</html>
MSG;

$emailsObj = new Model('emails');
$allEmails =  ($emailsObj->filter_index(array('attempt'=>0)));
emaillog($msg="============== Task Start ===============");
foreach ($allEmails as $key => $vl) { 
    $vl =   (object) $vl;
    $to = $vl->email;
    try {
        if (mail($to, $subject, $message, $headers)) {
            $msg = "| $key Success: Email sent successfully {$to}";
            emaillog($msg);
            $id = (new Model('emails'))->update($vl->id,array('attempt'=>1));
            if (intval($id) && $id>0) {
                $msg = "| $key Database: Attempt count for {$to}";
                emaillog($msg);
            }else{
                $msg = "| $key Database: Attempt not count for {$to}";
                emaillog($msg);
            }
        }else{
            $msg = "| $key Failed: Email not sent {$to}";
            emaillog($msg);
        }
    } catch (\Throwable $th) {
        $msg = "| $key Error: $th while sending $to";
        emaillog($msg);
    }
    
}
emaillog($msg="================== End ==================\n"); 
// mail($to, $subject, $message, $headers);
// echo $message;