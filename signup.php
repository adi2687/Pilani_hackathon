<?php
require "dbh.inc.php";
session_start();

try {
    // Retrieve and sanitize input data
    $fname = htmlspecialchars(trim($_POST["fname"]));
    $lname = htmlspecialchars(trim($_POST["lname"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $num = htmlspecialchars(trim($_POST["number"]));
    $password = htmlspecialchars(trim($_POST["password"]));
    $unique_id = rand(100000, 99999999);  // Generate a unique ID
    $staticImageName = '1726225483_patient.png'; // Replace with your static image name

    if ($email === false) {
        echo "Invalid email format.";
        exit();
    }

    // Check for existing email or number
    $checkQuery = "SELECT * FROM users WHERE email = :email OR number = :number";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(":email", $email);
    $checkStmt->bindParam(":number", $num);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        echo "Email or number already exists.";
        exit();
    }

    // Insert user data including static image
    $query = "INSERT INTO users (unique_id, fname, lname, email, number, password, image) 
              VALUES (:unique_id, :fname, :lname, :email, :number, :password, :image)";
    $stm = $pdo->prepare($query);
    $stm->bindParam(":unique_id", $unique_id);
    $stm->bindParam(":fname", $fname);
    $stm->bindParam(":lname", $lname);
    $stm->bindParam(":email", $email);
    $stm->bindParam(":number", $num);
    $stm->bindParam(":password", $password);
    $stm->bindParam(":image", $staticImageName);

    if ($stm->execute()) {
        $_SESSION['unique_id'] = $unique_id;
        echo "success";
    } else {
        echo "failed";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
