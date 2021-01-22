<?php
$username = 'Server';
$pass = 'SafeServer';
$host = 'localhost';
$dbname = 'iotlab';

try{
	$pdo = new PDO ("mysql:host=$host;dbname=$dbname", $username, $pass);
	//echo "connected successfully";
}catch(PDOException $e){
	die("Message: ".$e->getMessage());
}

$create = false;
if ($create){
	$pdo->query("CREATE TABLE students (
	id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name CHAR(70),
    roll_no CHAR(10),
    email CHAR(70) UNIQUE,
    mobile CHAR(15),
	password CHAR(15),
	regtime DATETIME,
    everify BIT NOT NULL DEFAULT 0,
	otp INT,
	otpcount TINYINT(20) DEFAULT 0,
	otpexp DATETIME
);");
}
?>