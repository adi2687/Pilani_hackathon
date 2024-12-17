<?php
session_start();
require "dbh.inc.php";
if($_SERVER["REQUEST_METHOD"]=="POST"){
$cred = $_POST["cred"];
$password=$_POST["password"];
$query = "SELECT * FROM users WHERE (email = :cred OR number = :cred) AND password=:password";
$stmt = $pdo->prepare($query);
$stmt->bindParam(":cred", $cred);
$stmt->bindParam(":password",$password);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC); 

if ($result) {
    $_SESSION["unique_id"] = $result['unique_id'];
    echo "success";
    
} else {
    echo "No email or phone number found with this info!";
}
}
else{
    echo "What are you doing here ? invalid request methd";
}
?>
