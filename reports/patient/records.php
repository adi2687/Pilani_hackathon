<?php
require "../../dbh.inc.php"; // Include your database connection file

session_start();

// Check if the user is logged in
if (!isset($_SESSION['unique_id']) || empty($_SESSION['unique_id'])) {
    echo "<p>Unauthorized access. Please log in.</p>";
    exit;
}

$userUniqueId = $_SESSION['unique_id'];

$query = "SELECT * FROM appointments WHERE pat_id = :unique_id ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':unique_id', $userUniqueId, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($result) {
    foreach ($result as $appointment) {
        echo "<div class='appointment'>";
        echo "<h2>Appointment ID: " . htmlspecialchars($appointment['id']) . "</h2>";
        echo "<p><strong>Patient ID:</strong> " . htmlspecialchars($appointment['pat_id']) . "</p>";
        echo "<p><strong>Date:</strong> " . htmlspecialchars($appointment['date']) . "</p>";
        echo "<p><strong>Time:</strong> " . htmlspecialchars($appointment['time']) . "</p>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($appointment['message']) . "</p>";
        echo "<p><strong>Time of Registration:</strong> " . htmlspecialchars($appointment['time_of_reg']) . "</p>";
        echo "<p><strong>Preferred Doctor:</strong> " . htmlspecialchars($appointment['preferred_doctors']) . "</p>";
        echo "<p><strong>Dosage:</strong> " . htmlspecialchars($appointment['dosage']) . "</p>";
        echo "<p><strong>Frequency:</strong> " . htmlspecialchars($appointment['frequency']) . "</p>";
        echo "<p><strong>Duration:</strong> " . htmlspecialchars($appointment['duration']) . "</p>";
        echo "<p><strong>Route:</strong> " . htmlspecialchars($appointment['route']) . "</p>";
        echo "<p><strong>Instructions:</strong> " . htmlspecialchars($appointment['instructions']) . "</p>";
        echo "<p><strong>Refills:</strong> " . htmlspecialchars($appointment['refills']) . "</p>";
        echo "<p><strong>Signature:</strong> " . htmlspecialchars($appointment['signature']) . "</p>";
        echo "<p><strong>Drug Interaction Warnings:</strong> " . htmlspecialchars($appointment['drug_interaction_warnings']) . "</p>";
        echo "<p><strong>Additional Notes:</strong> " . htmlspecialchars($appointment['additional_notes']) . "</p>";
        echo "";
        echo "<br><form action='generate_pdf.php' method='get'>";
        echo "<input type='hidden' name='appointment_id' value='" . htmlspecialchars($appointment['id']) . "'>";
        echo "<button type='submit'>Download PDF</button>";
        echo "</form>";
        echo "</div><hr></div><hr>";
    }
} else {
    echo "<p>No appointments found.</p>";
}
?>
