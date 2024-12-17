<?php

require "../dbh.inc.php";
session_start();
$diseaseInput = trim($_POST['disease']);
if ($diseaseInput) {
    $jsonFile = 'diseases.json';
    $jsonData = file_get_contents($jsonFile);
    $diseaseToSpecialization = json_decode($jsonData, true);

    $specializations = [];

    foreach ($diseaseToSpecialization as $disease => $specialization) {
        if (stripos($disease, $diseaseInput) !== false) {
            $specializations = array_merge($specializations, $specialization);
        }
    }

    if (empty($specializations)) {
        echo "No matching specializations found for this disease.";
        exit;
    }

    $specializations = array_unique($specializations);

    $placeholders = str_repeat('?,', count($specializations) - 1) . '?';
    $query = "SELECT * FROM doctors 
              WHERE specialization1 IN ($placeholders)
              OR specialization2 IN ($placeholders)
              OR specialization3 IN ($placeholders)
              OR specialization4 IN ($placeholders)";

    $stmt = $pdo->prepare($query);

    $paramIndex = 1;
    foreach ($specializations as $specialization) {
        $stmt->bindValue($paramIndex++, $specialization, PDO::PARAM_STR);
    }
    foreach ($specializations as $specialization) {
        $stmt->bindValue($paramIndex++, $specialization, PDO::PARAM_STR);
    }
    foreach ($specializations as $specialization) {
        $stmt->bindValue($paramIndex++, $specialization, PDO::PARAM_STR);
    }
    foreach ($specializations as $specialization) {
        $stmt->bindValue($paramIndex++, $specialization, PDO::PARAM_STR);
    }

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $detail = "";

    foreach ($result as $row) {
        $detail .= '<input type="checkbox" class="doctor-checkbox" value="' . htmlspecialchars($row['fname'] . ' ' . $row['lname']) . '">' .
            htmlspecialchars($row['fname'] . ' ' . $row['lname']) . '<br>' .
            "Clinic Name: " . htmlspecialchars($row['clinic']) . "<br>" .
            "Address: " . htmlspecialchars($row['address']) . "<br>" .
            "Specializations: " . htmlspecialchars($row['specialization1']) . " " .
            htmlspecialchars($row['specialization2']) . " " .
            htmlspecialchars($row['specialization3']) . " " .
            htmlspecialchars($row['specialization4']) . "<br>";
            // "Location: Latitude: " . htmlspecialchars($row['latiude']) . " Longitude: " . htmlspecialchars($row['longitude']) . "<br><br>";
    }

    if (!empty($detail)) {
        echo $detail;
    } else {
        echo "No matches found.";
    }
} else {

    $name = $_POST['name'];
    if (empty($name)) {
        echo "it is empty";
        exit();
    }

    $query2 = "SELECT * FROM doctors WHERE fname LIKE :name OR lname LIKE :name OR CONCAT(fname, lname) LIKE :name";
    $stmt = $pdo->prepare($query2);
    $stmt->execute(['name' => '%' . $name . '%']);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $detail = "";
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
    foreach ($result as $row) {
        $clinic_od = $row['clinic_id'];
        $query2 = "SELECT * FROM clinics WHERE clinic_id=:clinic_id";
        $stmt2 = $pdo->prepare($query2);
        $stmt2->execute(['clinic_id' => $clinic_od]);
        $stmt2->execute();
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $latto = $result2['latiude'];
        $longto = $result2['longitude'];
        $unique_id=$_SESSION['unique_id'];
        $query7 = "SELECT * FROM users WHERE unique_id=:unique_id";
        $stmt7 = $pdo->prepare($query7);
        $stmt7->bindParam(':unique_id', $unique_id);
        $stmt7->execute();
        $user = $stmt7->fetch(PDO::FETCH_ASSOC);
        $latitudeFrom = $user['latiude'];
        $longitudeFrom = $user['longitude'];

        $distance=substr(haversineDistance($latitudeFrom,$longitudeFrom,$latto,$longto),0,6);
        $detail .= '<input type="checkbox" class="doctor-checkbox" value="' . htmlspecialchars($row['fname'] . ' ' . $row['lname']) . '">' .
            htmlspecialchars($row['fname'] . ' ' . $row['lname']) . '<br>' .
            "Clinic Name: " . htmlspecialchars($row['clinic']) . "<br>" .
            "Address: " . htmlspecialchars($row['address']) . "<br>" .
            "Specializations: " . htmlspecialchars($row['specialization1']) . " " .
            htmlspecialchars($row['specialization2']) . " " .
            htmlspecialchars($row['specialization3']) . " " .
            htmlspecialchars($row['specialization4']) . "<br>$distance km from you
            ";


        // "Location: Latitude: " . htmlspecialchars($result2['latiude']) . " Longitude: " . htmlspecialchars($result2['longitude']) . "<br><br>";
    }

    echo $detail;
}
?>