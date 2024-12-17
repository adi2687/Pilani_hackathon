<?php
session_start();
require "../dbh.inc.php";

if (!isset($_SESSION['unique_id']) || empty($_SESSION['unique_id'])) {
    echo "<p>Unauthorized access. Please log in.</p>";
    exit;
}

$unique_id = $_SESSION['unique_id'];

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointmentId = trim($_POST['appointment_id']);
    
    if (!empty($appointmentId)) {
        // Fetch doctor's name using the unique_id
        $query1 = "SELECT * FROM doctors WHERE unique_id = :unique_id";
        $stmt = $pdo->prepare($query1);
        $stmt->bindParam(':unique_id', $unique_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $doctorName = $result['fname'] . " " . $result['lname'];

            // Fetch appointment details
            $query = "SELECT * FROM appointments WHERE id = :appointmentId";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":appointmentId", $appointmentId, PDO::PARAM_INT);
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
        echo "<p>No valid appointment right now , start a active prescriptio by clicking on a booked appointment.</p>";
    }
// } else {
//     echo "<p>Invalid request.</p>";
// }
?>
