<?php
session_start();
require "dbh.inc.php"; 

if (isset($_SESSION["unique_id"])) {
    $unique_id = $_SESSION["unique_id"];
    
    // Query to fetch data from users table
    $query = "SELECT * FROM users WHERE unique_id = :unique_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":unique_id", $unique_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Query to fetch data from doctors table
    $query1 = "SELECT * FROM doctors WHERE unique_id = :unique_id"; // Corrected column name
    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindParam(":unique_id", $unique_id);
    $stmt1->execute(); // This was missing
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

    // Check if data was found in either table
    if ($result || $result1) {
        echo "Session active";
    } else {
        echo "Session inactive";
    }
} else {
    echo "No session";
}
?>
