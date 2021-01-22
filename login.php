<?php
session_start();
include('pdo.php');
include('utils.php');

if ($_SERVER['REQUEST_METHOD'] == "POST"){
	if (isset($_POST['cancel'])){
		header("Location: index.php");
		return;
	}
	
	foreach ($_POST as $k=>$v){
		if ($v == '' || strlen($v) < 1){
			$_SESSION['msg'] = "All fields are required";
			$_SESSION['color'] = "red";
			header("Location: login.php");
			return;
		}
	}
	
	$email = strtolower($_POST['email']);
	if (strpos($email,"@bitsathy.ac.in") === false){
		$_SESSION['msg'] = "Enter Your registered bitsathy email id";
		$_SESSION['color'] = "red";
		header("Location: login.php");
		return;
	}
	$pass = $_POST['password'];
	
	$stmt = $pdo->prepare("SELECT * FROM students WHERE email=:email AND password=:pass");
	$stmt->execute(array(':email'=>$email, ':pass'=>$pass));
	if($res = $stmt->fetch(PDO::FETCH_ASSOC)){
		//debug $_SESSION['db'] = $res;
		if ((int)$res['everify'] !== 1){
			$_SESSION['email'] = $res['email'];
			$_SESSION['msg'] = "Kindly verify your registered bitsathy email id before login";
			$_SESSION['color'] = "red";
			header("Location: verify.php");
			return;
		}
		$_SESSION['username'] = htmlentities($res['name']);
		$_SESSION['roll_no'] = htmlentities($res['roll_no']);
		$_SESSION['msg'] = "Login Successful";
		$_SESSION['color'] = "green";
		header("Location: index.php");
		return;
	}else{
		$_SESSION['msg'] = "Email or Password incorrect";
		$_SESSION['color'] = "red";
		header("Location: login.php");
		return;
	}
	
	
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>IoT Lab Login</title>
		<?php include("bootstrap.php")?>
	</head>
	<body>
		<div class="container">
			<h1>
				Login
			</h1>
			<?php flash_message() ?>
			<form method="POST">
				<p>
					Email:
				</p>
				<input type="text" name="email">
				<p>
					Password:
				</p>
				<input type="password" name="password">
				<br><br>
				<input type="submit" value="Login">
				<input type="submit" name="cancel" value="Cancel">
			</form>
		</div>
	</body>
</html>