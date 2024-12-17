<?php

require "../dbh.inc.php";
session_start();

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
$query = "SELECT * FROM users WHERE unique_id=:unique_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':unique_id', $unique_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if($user){
$latitudeFrom = $user['latiude'] || 0;
$longitudeFrom = $user['longitude'] | 0;
}
$querycheck="SELECT * FROM doctors WHERE unique_id=:unique_id";
$stmtcheck=$pdo->prepare($querycheck);
$stmtcheck->bindParam(':unique_id',$unique_id);
$stmtcheck->execute();
$check=$stmtcheck->fetch(PDO::FETCH_ASSOC);
if ($check){
    $clinic=$check['clinic'];
    $query = "SELECT * FROM clinics WHERE clinics_name=:clinic";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':clinic', $clinic);
    $stmt->execute();
    $clinic = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($clinic){
        $longitudeFrom=$clinic['longitude'];
        $latitudeFrom=$clinic['latiude'];

    }
}
$query = "SELECT DISTINCT * FROM clinics";
$stmt = $pdo->prepare($query);
$stmt->execute();

$clinics = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($clinics as $clinic) {
    $clinicName = $clinic['clinics_name'];
    $address = $clinic['address'];
    $latitudeTo = $clinic['latiude']; // Corrected typo
    $longitudeTo = $clinic['longitude']; // Corrected typo
$clinic_id=$clinic['clinic_id'];
    // Calculate the distance between user and clinic
    $distance = haversineDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo);

    echo "<div class='clinic-container'>";
    echo "<div class='clinic-title'>Clinic: " . htmlspecialchars($clinicName) . "</div>";   
    echo "<div class='clinic-address'>Address: " . htmlspecialchars($address) . "</div>";
    echo "<div class='clinic-distance'>Distance: " . round($distance, 2) . " km from you</div>";
    echo "<div><a href='../google/hospital?lat=$latitudeTo&lon=$longitudeTo'>See $clinicName on maps </a></div>";
    // Query to get doctors for each clinic
    $query1 = "SELECT * FROM doctors WHERE clinic_id = :clinic_id";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindParam(":clinic_id", $clinic_id);
    $stmt1->execute();

    $doctors = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Display doctor details
    foreach ($doctors as $doctor) {
        $doctorName = $doctor['fname'] . " " . $doctor['lname'];
        $specializations = $doctor['specialization1'] . " " . $doctor['specialization2'] . " " . $doctor['specialization3'] . " " . $doctor['specialization4'];

        // Fetch rating count for the doctor
        $query2 = "SELECT COUNT(*) AS rating_count FROM rating WHERE doctor=:name";
        $stmt2 = $pdo->prepare($query2);
        $stmt2->bindParam(":name", $doctorName);
        $stmt2->execute();
        $ratingCount = $stmt2->fetch(PDO::FETCH_ASSOC)['rating_count'];

        echo "<div class='doctor-info'>";
        echo "<div class='doctor-name'>" . htmlspecialchars($doctorName) . "</div>";
        echo "<div class='doctor-details'>Contact: " . htmlspecialchars($doctor['number']) . "</div>";
        echo "<div class='doctor-details'>Email: " . htmlspecialchars($doctor['email']) . "</div>";
        echo "<div class='specializations'>Specializations: " . htmlspecialchars($specializations) . "</div>";
        echo "<div class='rating'>Rating: " . htmlspecialchars($doctor['rating']) . " stars. Rated by $ratingCount patients</div>";
        echo "</div><br>";
    }

    echo "</div><br>";
}
?>
