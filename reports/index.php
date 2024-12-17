<?php
require "../dbh.inc.php";
session_start();

$app = isset($_GET['appointment_id']) ? trim($_GET['appointment_id']) : '';

if (!isset($_SESSION['unique_id']) || empty($_SESSION['unique_id'])) {
    echo "<p>Unauthorized access. Please log in.</p>";
    exit;
}

$unique_id = $_SESSION['unique_id'];

if (!empty($app)) {
    $query1 = "SELECT * FROM doctors WHERE unique_id = :unique_id";
    $stmt = $pdo->prepare($query1);
    $stmt->bindParam(':unique_id', $unique_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $doctorName = $result['fname'] . " " . $result['lname'];

        $query = "SELECT * FROM appointments WHERE id = :appointmentId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":appointmentId", $app, PDO::PARAM_INT);
        $stmt->execute();
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($appointment) {
            if ($appointment['preferred_doctors'] === $doctorName) {
                $response = "<div class='appointment-details'>";
                $response .= "<h2>Appointment Details</h2>";
                $response .= "<p><strong>Patient Name:</strong> " . htmlspecialchars($appointment['name']) . "</p>";
                $response .= "<p><strong>Appointment Date:</strong> " . htmlspecialchars($appointment['date']) . "</p>";
                $response .= "<p><strong>Time:</strong> " . htmlspecialchars(substr($appointment['time'], 0, 8)) . "</p>";
                $response .= "<p><strong>Message:</strong> " . htmlspecialchars($appointment['message']) . "</p>";
                $response .= "<p><strong>Registered at:</strong> " . htmlspecialchars($appointment['time_of_reg']) . "</p>";
                $response .= "</div>";

                echo $response;
            } else {
                echo "<p>You cannot access other doctors' patients.</p>";
            }
        } else {
            echo "<p>No appointment found with the provided ID.</p>";
        }
    } else {
        echo "<p>Doctor information could not be found.</p>";
    }
} else {
    echo "<p>No valid appointment right now , start a active prescriptio by clicking on a booked appointment</p>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dosage = isset($_POST['dosage']) ? trim($_POST['dosage']) : '';
    $frequency = isset($_POST['frequency']) ? trim($_POST['frequency']) : '';
    $duration = isset($_POST['duration']) ? trim($_POST['duration']) : '';
    $route = isset($_POST['route']) ? trim($_POST['route']) : '';
    $instructions = isset($_POST['instructions']) ? trim($_POST['instructions']) : '';
    $refills = isset($_POST['refills']) ? (int)$_POST['refills'] : 0;
    $signature = isset($_POST['signature']) ? trim($_POST['signature']) : '';
    $drug_interaction_warnings = isset($_POST['drug_interaction_warnings']) ? trim($_POST['drug_interaction_warnings']) : '';
    $additional_notes = isset($_POST['additional_notes']) ? trim($_POST['additional_notes']) : '';

    if (!empty($app)) {
        $query = "UPDATE appointments SET 
                dosage = :dosage,
                frequency = :frequency,
                duration = :duration,
                route = :route,
                instructions = :instructions,
                refills = :refills,
                signature = :signature,
                drug_interaction_warnings = :drug_interaction_warnings,
                additional_notes = :additional_notes,done=1
                WHERE id = :appointment_id";

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
            $stmt->bindParam(':appointment_id', $app, PDO::PARAM_INT);
            
            $stmt->execute();

            echo "<p class='status'>Prescription updated successfully.</p>";
        } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        echo "<p>Appointment ID is missing.</p>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication Prescription Form</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .status{
            margin-left:40%;
            font-weight: bold;
            font-size: 20px;
        }
        nav img{
            width:90px;
            height:80px
        }
        nav a {
            color:black;
            font-weight: bold;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 30px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items:center;
            gap:20px
        }
    </style>
</head>

<body>
    <nav>
        <a href="../">
        <img src="../image/logo.png" alt="">
        <p>MedPulse</p>
        </a>
    </nav>
    <h1>Medication Prescription Form</h1>
    <div class="container">
        <form action="" method="POST">
            <!-- Hidden field to store the appointment ID -->
            <input type="hidden" name="app" value="<?php echo htmlspecialchars($app); ?>">

            <div class="form-group">
                <label for="dosage">Dosage:</label>
                <input type="text" id="dosage" name="dosage">
            </div>
            <div class="form-group">
                <label for="frequency">Frequency:</label>
                <input type="text" id="frequency" name="frequency">
            </div>
            <div class="form-group">
                <label for="duration">Duration:</label>
                <input type="text" id="duration" name="duration">
            </div>
            <div class="form-group">
                <label for="route">Route:</label>
                <input type="text" id="route" name="route">
            </div>
            <div class="form-group">
                <label for="instructions">Instructions:</label>
                <textarea id="instructions" name="instructions" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="refills">Refills:</label>
                <input type="number" id="refills" name="refills" min="0">
            </div>
            <div class="form-group">
                <label for="signature">Signature:</label>
                <input type="text" id="signature" name="signature">
            </div>
            <div class="form-group">
                <label for="drug_interaction_warnings">Drug Interaction Warnings:</label>
                <textarea id="drug_interaction_warnings" name="drug_interaction_warnings" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="additional_notes">Additional Notes:</label>
                <textarea id="additional_notes" name="additional_notes" rows="4"></textarea>
            </div>
            <div class="form-group">
                <button type="submit">Submit Prescription</button>
            </div>
        </form>
    </div>



</html>
</body>

</html>