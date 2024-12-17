<?php

include "../dbh.inc.php";


// require "book_appointment.php";
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$message = $_POST['message'] ?? '';
$preferredDoctors = $_POST['preferred_doctors'] ?? '';

// $preferredDoctorsJson = json_encode($preferredDoctors);

// echo $preferredDoctors."hiiiiii" ;
session_start();
$unique_id = $_SESSION['unique_id'] ?? '';
if (empty($unique_id)) {
    echo "User not logged in.";
    exit;
}

$querymain = "SELECT * FROM users WHERE unique_id = :unique_id";
$stmtmain = $pdo->prepare($querymain);
$stmtmain->bindParam(':unique_id', $unique_id);
$stmtmain->execute();
$resultmain = $stmtmain->fetch(PDO::FETCH_ASSOC);

if ($resultmain) {
    $name = $resultmain['fname'] . " " . $resultmain['lname'];
} else {
    echo "User not found.";
    exit;
}
echo $preferredDoctors;
$pat_id=$unique_id;
$sql = "INSERT INTO appointments (date, name,pat_id, time, message, preferred_doctors) VALUES (:date, :name,:pat_id, :time, :message, :preferred_doctors)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':name', $name);$stmt->bindParam(":pat_id",$pat_id);
$stmt->bindParam(':time', $time);
$stmt->bindParam(':message', $message);
$stmt->bindParam(':preferred_doctors', $preferredDoctors);

try {
    $stmt->execute();
    echo "Appointment booked successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}