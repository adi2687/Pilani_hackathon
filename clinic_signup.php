<?php

require "dbh.inc.php";
session_start();
$name = $_POST['clinic'];
$address = $_POST['address'];
$password = $_POST['password'];

// Check if the clinic name or address already exists in the database
$query = "SELECT * FROM clinics WHERE clinics_name = :name OR address = :address";
$stmt = $pdo->prepare($query);
$stmt->bindParam(":name", $name);
$stmt->bindParam(":address", $address);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo "failed.";
    exit();
} else {
    $random=rand(100000,999998);
    $query = "INSERT INTO clinics (clinic_id,clinics_name, address, password) VALUES (:random,:name, :address, :password)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":random", $random);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":address", $address);
    $stmt->bindParam(":password", $password);
    $stmt->execute();
    $_SESSION['unique_id']=$random;
    echo "Good";
}
