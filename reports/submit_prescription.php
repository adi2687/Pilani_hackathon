<?php
require "../dbh.inc.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $appointmentIdmain = isset($_POST['app']) ? trim($_POST['app']) : '';
    $dosage = isset($_POST['dosage']) ? trim($_POST['dosage']) : '';
    $frequency = isset($_POST['frequency']) ? trim($_POST['frequency']) : '';
    $duration = isset($_POST['duration']) ? trim($_POST['duration']) : '';
    $route = isset($_POST['route']) ? trim($_POST['route']) : '';
    $instructions = isset($_POST['instructions']) ? trim($_POST['instructions']) : '';
    $refills = isset($_POST['refills']) ? (int)$_POST['refills'] : 0;
    $signature = isset($_POST['signature']) ? trim($_POST['signature']) : '';
    $drug_interaction_warnings = isset($_POST['drug_interaction_warnings']) ? trim($_POST['drug_interaction_warnings']) : '';
    $additional_notes = isset($_POST['additional_notes']) ? trim($_POST['additional_notes']) : '';

    // Check if appointment ID is provided
    if (!empty($appointmentIdmain)) {
        // Construct the SQL query to update the prescription
        $query = "UPDATE prescriptions SET 
            dosage = :dosage,
            frequency = :frequency,
            duration = :duration,
            route = :route,
            instructions = :instructions,
            refills = :refills,
            signature = :signature,
            drug_interaction_warnings = :drug_interaction_warnings,
            additional_notes = :additional_notes
            WHERE appointment_id = :appointment_id";

        try {
            $stmt = $pdo->prepare($query);

            // Bind parameters
            $stmt->bindParam(':dosage', $dosage);
            $stmt->bindParam(':frequency', $frequency);
            $stmt->bindParam(':duration', $duration);
            $stmt->bindParam(':route', $route);
            $stmt->bindParam(':instructions', $instructions);
            $stmt->bindParam(':refills', $refills, PDO::PARAM_INT);
            $stmt->bindParam(':signature', $signature);
            $stmt->bindParam(':drug_interaction_warnings', $drug_interaction_warnings);
            $stmt->bindParam(':additional_notes', $additional_notes);
            $stmt->bindParam(':appointment_id', $appointmentIdmain, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            echo "Prescription updated successfully.";
        } catch (PDOException $e) {
            // Handle error
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        echo "No valid appointment right now , start a active prescriptio by clicking on a booked appointment";
    }
} else {
    echo "Invalid request method.";
}
?>
