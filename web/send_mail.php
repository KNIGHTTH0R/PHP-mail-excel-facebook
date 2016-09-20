<?php
$email = getenv('EMAIL');
$password = getenv('EMAIL_PASSWORD');

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


require 'PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer(); // create a new object
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only


$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
$mail->Host       = "up-excel.herokuapp.com";      // sets GMAIL as the SMTP server
$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
$mail->Username   = $email;  // GMAIL username
$mail->Password   = $password;
$mail->SetFrom($email);

$mail->Subject = "Test";
$mail->Body = "hello";
$mail->AddAddress("eng.raksa@gmail.com");

if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message has been sent";
}