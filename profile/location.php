<?php
require "../dbh.inc.php";

session_start();
$unique_id = $_SESSION['unique_id'];

$longitude = filter_input(INPUT_POST, 'longitude', FILTER_SANITIZE_STRING);
$latitude = filter_input(INPUT_POST, 'latitude', FILTER_SANITIZE_STRING);

if ($longitude && $latitude) {
    echo "Latitude: " . $latitude . ", Longitude: " . $longitude;

    $query = "SELECT * FROM users WHERE unique_id = :unique_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":unique_id", $unique_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $query1="SELECT * FROM clinics WHERE clinic_id=:unique_id";
    $stmt1=$pdo->prepare($query1);
    $stmt1->bindParam(":unique_id",$unique_id);
    $stmt1->execute();
    $result1=$stmt1->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $sql = "UPDATE users SET latiude = :latitude, longitude = :longitude WHERE unique_id = :unique_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":latitude", $latitude);
        $stmt->bindParam(":longitude", $longitude);
        $stmt->bindParam(":unique_id", $unique_id);
        $stmt->execute();
        echo "User location updated successfully.";
    } 
    else if ($result1){
        $sql = "UPDATE clinics SET latiude = :latitude, longitude = :longitude WHERE clinic_id = :unique_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":latitude", $latitude);
        $stmt->bindParam(":longitude", $longitude);
        $stmt->bindParam(":unique_id", $unique_id);
        $stmt->execute();
        echo "User location updated successfully.";
    }
} else {
    echo "Invalid location data received.";
}
