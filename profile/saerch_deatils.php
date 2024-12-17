<?php
session_start();
require "../dbh.inc.php";

$unique_id = $_SESSION['unique_id'];
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Start building the SQL query
$query = "
    SELECT a.*, CONCAT(d.fname, ' ', d.lname) AS doctor_name 
    FROM appointments a
    JOIN doctors d ON CONCAT(d.fname, ' ', d.lname) = a.preferred_doctors
    WHERE d.unique_id = :unique_id
";

if (!empty($searchTerm)) {
    $query .= " AND a.name LIKE :searchTerm"; // Search by patient name
}

$query .= " ORDER BY a.id DESC";

$stmt = $pdo->prepare($query);

$stmt->bindParam(':unique_id', $unique_id);

if (!empty($searchTerm)) {
    $searchTerm = "%$searchTerm%"; // Add wildcards for partial matching
    $stmt->bindParam(':searchTerm', $searchTerm);
}

$stmt->execute();

$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($appointments) {
    $output = "<h1><a href='../'>MedPulse</a></h1><ul>";
    foreach ($appointments as $appointment) {
        if ($appointment['dosage'] != NULL) {
            $status = "<p class='status Done'>Status: Done</p>";
            $path_to_app = "../reports/appointment_done?appointment_id=" . urlencode($appointment['id']);
        } else {
            $status = "<p class='status Pending'>Status: Pending</p>";
            $path_to_app = "../reports?appointment_id=" . urlencode($appointment['id']);
        }

        $output .= "
        <a href='$path_to_app'>
            <div class='appointment-box'>
                <p><strong>Patient Name:</strong> " . htmlspecialchars($appointment['name']) . "</p>
                <p><strong>Appointment Date:</strong> " . htmlspecialchars($appointment['date']) . "</p>
                <p><strong>Time:</strong> " . htmlspecialchars(substr($appointment['time'], 0, 9)) . "</p>
                <p><strong>Appointment Message:</strong> " . htmlspecialchars($appointment['message']) . "</p>
                <p><strong>Registered at:</strong> " . htmlspecialchars(substr($appointment['time_of_reg'], 0, 20)) . "</p>
                $status
            </div>
        </a>
        <hr>";
    }
    $output .= "</ul>";
    echo $output;
} else {
    echo "<p>No appointments found for this doctor.";
    if (!empty($searchTerm)) {
        echo " Matching the search term: '" . htmlspecialchars(trim($_GET['search'])) . "'";
    }
    echo "</p>";
}
