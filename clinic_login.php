<?php
session_start();
require "dbh.inc.php";
$clinic_id=$_POST['clinic-id'];
$password=$_POST['password'];

$query="SELECT * FROM clinics WHERE clinic_id=:clinic_id AND password=:password";
$stmt=$pdo->prepare($query);
$stmt->bindParam(':clinic_id',$clinic_id,PDO::PARAM_INT);
$stmt->bindParam(':password',$password,PDO::PARAM_STR);
$stmt->execute();
$result=$stmt->fetch(PDO::FETCH_ASSOC);
// echo $password;
if($result){
    echo "Good";
    $_SESSION['unique_id']=$clinic_id;
    exit();
}
else{
    echo "Login details are not correct";
}