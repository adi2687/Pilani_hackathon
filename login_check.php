<?php
require "dbh.inc.php";  

session_start();

$unique_id=$_SESSION['unique_id'];
$query="SELECT * FROM doctors WHERE unique_id=:unique_id";
$stmt=$pdo->prepare($query);
$stmt->bindParam(':unique_id',$unique_id);
$stmt->execute();
$result=$stmt->fetch(PDO::FETCH_ASSOC);
// $query1="SELECT * FROM clinics WHERE "
// $unique_id=$_SESSION['unique_id'];
$query1="SELECT * FROM users WHERE unique_id=:unique_id";
$stmt1=$pdo->prepare($query1);
$stmt1->bindParam(':unique_id',$unique_id);
$stmt1->execute();
$result1=$stmt1->fetch(PDO::FETCH_ASSOC);

$query2="SELECT * FROM clinics WHERE clinic_id=:unique_id";
$stmt2=$pdo->prepare($query2);
$stmt2->bindParam(':unique_id',$unique_id);
$stmt2->execute();
$result2=$stmt2->fetch(PDO::FETCH_ASSOC);
if ($result || $result1 || $result2){
    echo "good";
    
}
else{
    echo "notgood";
}