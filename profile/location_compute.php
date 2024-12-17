<?php

session_start();

require "../dbh.inc.php";
function haversineDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
{
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $a = sin($latDelta / 2) * sin($latDelta / 2) +
        cos($latFrom) * cos($latTo) *
        sin($lonDelta / 2) * sin($lonDelta / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distance = $earthRadius * $c;

    return $distance;
}
$unique_id = $_SESSION['unique_id'];
// echo $unique_id;
$query = "SELECT * FROM users WHERE unique_id=:unique_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':unique_id', $_SESSION['unique_id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$latitudeFrom = $result['latiude'];
$longitudeFrom = $result['longitude'];

$query1 = "SELECT * FROM clinics";
$stmt1 = $pdo->prepare($query1);
$stmt1->execute();
$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
foreach ($result1 as $row) {
    // echo $row['latiude'] . " " . $row['longitude'] . "<br>";
    $latitudeTo = $row['latiude'];
    $longitudeTo = $row['longitude'];

    $distance = haversineDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo);
    // echo $distance."<br>";
echo "" . substr($distance,0,7) . " kilometers away from you.<br><br>";
// 
}

// ?>