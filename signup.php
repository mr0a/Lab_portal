<?php
session_start();
require 'pdo.php';
require 'utils.php';
if ($_SERVER['REQUEST_METHOD'] == "POST"){
	if (isset($_POST['cancel'])){
		header("Location: index.php");
		return;
	}
	
	foreach ($_POST as $k=>$v){
		if ($v == '' || strlen($v) < 1){
			$_SESSION['msg'] = "All fields are required";
			$_SESSION['color'] = "red";
			header("Location: signup.php");
			return;
		}
	}
	
	$email = strtolower($_POST['email']);
	if (strpos($email,"@bitsathy.ac.in") === false){
		$_SESSION['msg'] = "Enter a valid bitsathy email id";
		$_SESSION['color'] = "red";
		header("Location: signup.php");
		return;
	}
	
	if (!is_numeric($_POST['mobile'])) {
			$_SESSION['msg'] = "Enter a valid mobile number";
			$_SESSION['color'] = "red";
			header("Location: signup.php");
			return;
	}
	
	$sname = $_POST['name'];
	$sroll = $_POST['rollno'];
	$mobile = $_POST['mobile'];
	$pass = $_POST['password'];
	$stmt = "INSERT INTO students(name, roll_no, email, password, mobile, regtime) VALUES(:name, :roll, :email, :pass, :mobile, NOW())";
	$params = array(
		':name'=>$sname,
		':roll'=>$sroll,
		':email'=>$email,
		':pass'=>$pass,
		':mobile'=>$mobile
	);
	
	if (insert_Stu($pdo, $stmt, $params)){
		$_SESSION['email'] = $email;
		$_SESSION['msg'] = "Kindly verify your email before login";
		$_SESSION['color'] = "green";
		header("Location: verify.php");
		return;
	}else {
		$_SESSION['msg'] = "Email already exists!";
		$_SESSION['color'] = "red";
		header("Location: signup.php");
		return;
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>IoT Lab Login</title>
		<?php include "bootstrap.php"?>
	</head>
	<body>
		<div class="container">
			<h1>
				IoT Lab Students Registration
			</h1>
			<?php flash_message();?>
			<form method="POST">
				<p>
					Name:
				</p>
				<input type="text" id="name" name="name">
				<p>
					Roll Number:
				</p>
				<input type="text" id="rollno" name="rollno">
				<p>
					Email:
				</p>
				<input type="text" name="email">
				<p>
					Mobile:
				</p>
				<input type="text" name="mobile">
				<p>
					Password:
				</p>
				<input type="password" name="password"><br>
				<br>
				<input type="submit" value="Register Now">
				<input type="submit" name="cancel" value="Cancel">
				<br><br>
				<input type="submit" id="login" value="Login">
			</form>
		</div>
	</body>
	<script type="text/javascript">
    document.getElementById("login").onclick = function () {
        location.href = "login.php";
		return false;
    };
</script>
</html>