<?php
require "../../dbh.inc.php";
require('fpdf.php'); 

session_start();

if (!isset($_SESSION['unique_id']) || empty($_SESSION['unique_id'])) {
    echo "<p>Unauthorized access. Please log in.</p>";
    exit;
}

if (!isset($_GET['appointment_id']) || empty($_GET['appointment_id'])) {
    echo "<p>No appointment ID provided.</p>";
    exit;
}

$appointmentId = $_GET['appointment_id'];
$userUniqueId = $_SESSION['unique_id'];

try {
    $query = "SELECT * FROM appointments WHERE id = :appointment_id AND pat_id = :unique_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
    $stmt->bindParam(':unique_id', $userUniqueId, PDO::PARAM_INT);
    $stmt->execute();
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($appointment) {
        $query1 = "SELECT * FROM users WHERE unique_id = :userUniqueId";
        $stmt1 = $pdo->prepare($query1);
        $stmt1->bindParam(":userUniqueId", $userUniqueId, PDO::PARAM_INT);
        $stmt1->execute();
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $name = $result1['fname'] . " " . $result1['lname'];

        $pdf = new FPDF();
        $pdf->AddPage();

        // Add MedPulse logo at the top (ensure the path to the image is correct)
        $pdf->Image('../../image/logo.png', 10, 10, 30);  // Adjust the position and size
        $pdf->Ln(20); // Add space below the logo

        // Set the title font
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 10, 'Appointment Details', 0, 1, 'C');
        $pdf->Ln(10);

        // Patient Information Section
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Patient Information', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Patient Name: ' . htmlspecialchars($name), 0, 1);
        $pdf->Cell(0, 10, 'Patient ID: ' . $appointment['pat_id'], 0, 1);
        $pdf->Ln(5);

        // Appointment Information Section
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Appointment Information', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Appointment ID: ' . $appointment['id'], 0, 1);
        $pdf->Cell(0, 10, 'Date: ' . $appointment['date'], 0, 1);
        $pdf->Cell(0, 10, 'Time: ' . $appointment['time'], 0, 1);
        $pdf->Ln(5);

        // Medication Information Section
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Medication Information', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Dosage: ' . htmlspecialchars($appointment['dosage']), 0, 1);
        $pdf->Cell(0, 10, 'Frequency: ' . htmlspecialchars($appointment['frequency']), 0, 1);
        $pdf->Cell(0, 10, 'Duration: ' . htmlspecialchars($appointment['duration']), 0, 1);
        $pdf->Cell(0, 10, 'Route: ' . htmlspecialchars($appointment['route']), 0, 1);
        $pdf->Cell(0, 10, 'Instructions: ', 0, 1);
        
        // Use MultiCell for wrapping long text
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, htmlspecialchars($appointment['instructions']));
        $pdf->Ln(5);

        // Additional Information Section
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Additional Information', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Message: ' . htmlspecialchars($appointment['message']), 0, 1);
        $pdf->Cell(0, 10, 'Preferred Doctor: ' . htmlspecialchars($appointment['preferred_doctors']), 0, 1);
        $pdf->Cell(0, 10, 'Drug Interaction Warnings: ' . htmlspecialchars($appointment['drug_interaction_warnings']), 0, 1);
        $pdf->Cell(0, 10, 'Additional Notes: ' . htmlspecialchars($appointment['additional_notes']), 0, 1);
        $pdf->Ln(5);

        // Signature Section: Digital signature of the doctor at the bottom
        // $pdf->Image('doctor_signature.png', 10, -30, 40); // Adjust position and size of the signature

        // Time of Registration Section
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Time of Registration: ' . htmlspecialchars($appointment['time_of_reg']), 0, 1);
        $pdf->Ln(10);

        // Output PDF
        $pdf->Output('D', 'appointment_' . $appointment['id'] . '.pdf');
    } else {
        echo "<p>Appointment not found or you do not have permission to access it.</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error retrieving appointment: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
