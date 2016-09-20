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
$mail->IsSMTP();
try {
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = $email;
    $mail->Password = $password;
    $mail->AddAddress('eng.raksa@gmail.com');
    $mail->Subject = 'PHPMailer Test Subject via mail(), advanced';
    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
    $mail->MsgHTML(file_get_contents('contents.html'));
    $mail->Body = 'This is body';
    $mail->Send();
    echo "Message Sent OK<p></p>\n";
} catch (phpmailerException $e) {
    echo $e->errorMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}