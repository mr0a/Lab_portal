<?php
session_start();
include("utils.php");
include("bootstrap.php");
?>
<html>
<head>
	<title>IOT Lab</title>
</head>
<body>
	<div class="container">
		<h1>Hello Students</h1>
		<p>
			<?php
			flash_message();
			//var_dump($_SESSION);
			echo "Welcome to IOT Lab";
			?>
		</p>
		<?php
		if(!isset($_SESSION['username'])){
			echo "<ul>
				<li><a href=\"login.php\">Login</a></li>
				<li><a href=\"signup.php\">Sign Up</a></li>
			</ul>";
		}else{
			echo "<p>Welcome ".$_SESSION['username']."</p>";
			echo "<p>Your Roll number is ".$_SESSION['roll_no']."</p>";
			echo '<a href="logout.php">Logout</a>';
		}
	?>
	</div>
</body>
</html>