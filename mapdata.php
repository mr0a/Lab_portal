<?php 
include('pdo.php');
header("Content-Type: application/json; charset = utf-8");
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM gps WHERE id=?");
$stmt->execute([$id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo(json_encode($result));
exit();  
 ?>