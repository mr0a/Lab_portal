<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function flash_message(){
	if (isset($_SESSION['msg'])){
		echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['msg'].'</p>';
		unset($_SESSION['msg'],$_SESSION['color']);
	}
}

function insert_Stu($pdo, $stmt, $ary){
	$prepared = $pdo->prepare($stmt);
	if($prepared->execute($ary) === true){
		return true;
	}
	//var_dump($prepared->errorInfo());
	return false;
}

function otp_mail($receive, $otp, $name){
	$mail = new PHPMailer(true);
	$mail->isSMTP();
	$mail->SMTPDebug = SMTP::DEBUG_OFF;
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->SMTPAuth = true;
	$mail->Username = 'demo@mail.com';
	$mail->Password = 'demo';
	$mail->setFrom('noreply@mail.com', 'No Reply IOTLab BIT');
	$mail->addAddress($receive, $name);
	$mail->Subject = 'IotLab OTP Verification';
	$mail->Body    = 'Enter this otp in the verification page to complete the registration. This OTP is valid only for 1 hour. OTP: '.$otp;
	#$mail->AltBody = 
	if (!$mail->send()) {
    	echo 'Mailer Error: '. $mail->ErrorInfo;
		return false;
	} else {
    	echo 'Message sent!';
		return true;
	}
}
?>