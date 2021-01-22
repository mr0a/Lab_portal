<?php
session_start();
if(isset($_SESSION['email'])) $email = $_SESSION['email'];
include("pdo.php");
include("utils.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$_SESSION['msg'] = $_POST;
	$_SESSION['color'] = "green";
	if (isset($_POST['cancel'])){
		header("Location: index.php");
		return;
	}
	
	if (isset($_POST['resend'])){
		$stmt = $pdo->prepare("SELECT name, roll_no, otp, otpcount, otpexp FROM students WHERE email=:email");
		$stmt->execute(array(":email"=>$_SESSION['email']));
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$name = $res['name'];
		$otp = $res['otp'];
		if($mail = otp_mail($email, $otp, $name)){
			$_SESSION['msg'] = "OTP sent successfully";
			$_SESSION['color'] = "green";
			header("Location: verify.php");
			return;
		}
		
	}
	
	if(isset($_POST['otp'])){
		$stmt = $pdo->prepare("SELECT name, roll_no, otp, otpcount, otpexp FROM students WHERE email=:email");
		$stmt->execute(array(":email"=>$email));
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$otp = $res['otp'];
		$name = $res['name'];
		$_SESSION['otp'] = $otp."   ".$_POST['otp'];
		if ($otp == $_POST['otp']){
			$stmt = $pdo->prepare("UPDATE students SET everify = 1 WHERE email = :email");
			$stmt->execute(array(
				':email' => $email
			));
			$_SESSION['msg'] = "Successfully verified";
			$_SESSION['color'] = "green";
			$_SESSION['username'] = $res['name'];
			$_SESSION['roll_no'] = $res['roll_no'];
			header("Location: index.php");
			return;
		}
		$_SESSION['msg'] = "Incorrect OTP";
		$_SESSION['color'] = "red";
		header("Location: verify.php");
		return;
		
	}
	
}

if(isset($_SESSION['email']) && $_SERVER['REQUEST_METHOD'] == "GET"){
	$stmt = $pdo->prepare("SELECT name, otp, otpcount, otpexp FROM students WHERE email=:email");
	$stmt->execute(array(":email"=>$email));
	$res = $stmt->fetch(PDO::FETCH_ASSOC);
	$otpcount = $res['otpcount'];
	$now = new DateTime();
	$name = $res['name'];
	if($res['otpcount'] > 0 && $res['otpexp'] < $now){
		$otp = $res['otp'];
	}else {
		$now->modify("+1 hour");
		$otp = mt_rand(100000, 999999);
		if($mail = otp_mail($email, $otp, $name)){
			$_SESSION['msg'] = "OTP sent successfully";
			$_SESSION['color'] = "green";
		}
		//var_dump($mail);
		$otpcount = $otpcount + 1;
		$stmt = $pdo->prepare("UPDATE students SET otp = :otp, otpcount = :otpcount, otpexp = :otpexp WHERE email = :email");
		$stmt->execute(array(
			':otp' => $otp,
			':otpcount' => $otpcount, 
			':otpexp' => $now->format('Y-m-d H:i:s'),
			':email' => $_SESSION['email']
		));
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Email Verification</title>
		<?php include("bootstrap.php") ?>
	</head>
	<body>
		<div class="container">
			<?php flash_message() ?>
			<form method="post">
				<p>
					Enter the otp sent to your registered email:
				</p>
				<input type="text" name="otp" size="20">
				<br><br>
				<input type="submit" value="submit">
				<input type="submit" value="Cancel" name="cancel">
				<br>
				<input type="submit" value="Resend OTP" name="resend">
			</form>
		</div>
	</body>
</html>