<?php
$email = getenv('EMAIL');
$password = getenv('EMAIL_PASSWORD');
var_dump($email);
var_dump($password);

/*$mail = new PHPMailer;

$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Username = 'raksa.e@gmail.com';
$mail->Password = '2012thenameilove';
$mail->SMTPSecure = 'tls';

$mail->setFrom('raksa.e@gmail.com');
$mail->addAddress('eng.raksa@gmail.com');

$mail->Subject = 'Uploaded Excel file';
$mail->Body = 'Detail Uploader <b>with attach excel file!</b>';
$mail->AltBody = 'name:Eng Raksa\n id:123';*/


require '../vendor/autoload.php';

$mail = new PHPMailer(true);
$mail->IsSMTP(); // telling the class to use SMTP
try {
//    $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port = 587;                   // set the SMTP port for the GMAIL server
    $mail->Username = $email;
    $mail->Password = $password;
    $mail->AddAddress('eng.raksa@gmail.com');
    $mail->Subject = 'PHPMailer Test Subject via mail(), advanced';
    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
    $mail->MsgHTML(file_get_contents('contents.html'));
    $mail->Send();
    echo "Message Sent OK<p></p>\n";
} catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
    echo $e->getMessage(); //Boring error messages from anything else!
}