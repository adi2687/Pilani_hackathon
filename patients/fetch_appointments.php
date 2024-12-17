<?php
session_start();
require "../dbh.inc.php";

// Check if the session variable is set
if (!isset($_SESSION['unique_id'])) {
    echo "<p>Unauthorized access. Please log in.</p>";
    exit;
}

$unique_id = $_SESSION['unique_id'];

try {
    $query = "SELECT * FROM doctors WHERE unique_id = :unique_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":unique_id", $unique_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $name = $result['fname'] . " " . $result['lname'];

        $query1 = "SELECT * FROM appointments WHERE preferred_doctors = :name";
        $stmt1 = $pdo->prepare($query1);
        $stmt1->bindParam(":name", $name);
        $stmt1->execute();
        $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        $detail = "";
        foreach ($result1 as $row) {
            $detail .= "<div class='appointment'>
                            <p><strong>Patient Name:</strong> " . htmlspecialchars($row['name']) . "</p>
                            <p><strong>Appointment Date:</strong> " . htmlspecialchars($row['date']) . "</p>
                            <p><strong>Time:</strong> " . htmlspecialchars(substr($row['time'], 0, 9)) . "</p>
                            <p><strong>Message:</strong> " . htmlspecialchars($row['message']) . "</p>
                            <p><strong>Registered At:</strong> " . htmlspecialchars($row['time_of_reg']) . "</p>
                            <p><strong>Dosage:</strong> " . htmlspecialchars($row['dosage']) . "</p>
                            <p><strong>Frequency:</strong> " . htmlspecialchars($row['frequency']) . "</p>
                            <p><strong>Duration:</strong> " . htmlspecialchars($row['duration']) . "</p>
                            <p><strong>Route:</strong> " . htmlspecialchars($row['route']) . "</p>
                            <p><strong>Instructions:</strong> " . htmlspecialchars($row['instructions']) . "</p>
                            <p><strong>Refills:</strong> " . htmlspecialchars($row['refills']) . "</p>
                            <p><strong>Signature:</strong> " . htmlspecialchars($row['signature']) . "</p>
                            <p><strong>Drug Interaction Warnings:</strong> " . htmlspecialchars($row['drug_interaction_warnings']) . "</p>
                            <p><strong>Additional Notes:</strong> " . htmlspecialchars($row['additional_notes']) . "</p>
                        </div>";
        }

        if ($detail) {
            echo $detail;
        } else {
            echo "<p>No appointments found for this doctor.</p>";
        }
    } else {
        echo "<p>Doctor information could not be found.</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
