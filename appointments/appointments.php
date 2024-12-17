<?php
require "../dbh.inc.php";
session_start();

if (isset($_SESSION['unique_id'])) {
    $unique_id = $_SESSION['unique_id'];
    echo $unique_id;

    $query = "SELECT * FROM appointments WHERE unique_id = :unique_id";
    $stmt = $pdo->prepare($query);
    
    $stmt->bindParam(':unique_id', $unique_id, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            print_r($result);
        } else {
            echo "No appointments found for the user.";
        }
    } else {
        echo "Query execution failed.";
    }
} else {
    echo "Session not found or unique_id is missing.";
}
