<?php
require "dbh.inc.php";

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Getting form data
    $clinic = $_POST['clinic'] ?? '';
    $address = $_POST['address'] ?? '';
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $email = $_POST['email'] ?? '';
    $number = $_POST['number'] ?? '';
    $password = $_POST['password'] ?? '';
    $clinic_id=$_POST['clinic_id'];
    // echo $clinic_id;
    // Specialization data
    $specialization1 = $_POST['specialization1'] ?? '';
    $specialization2 = $_POST['specialization2'] ?? '';
    $specialization3 = $_POST['specialization3'] ?? '';
    $specialization4 = $_POST['specialization4'] ?? '';

    $specializations = implode(', ', array_filter([$specialization1, $specialization2, $specialization3, $specialization4]));
    
    $query = "SELECT * FROM doctors WHERE (fname = :fname AND lname = :lname) OR email = :email OR number = :number";
    $stmt = $pdo->prepare($query);
    
    // Bind the parameters
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':number', $number);
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "Error: A doctor with the same details already exists!";
    } else {
        $unique_id = rand(100000, 999999);

        // Insert new doctor
        $insertQuery = "INSERT INTO doctors (clinic, address, unique_id, fname, lname, email, number, password, specialization1, specialization2, specialization3, specialization4,clinic_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->execute([$clinic, $address, $unique_id, $fname, $lname, $email, $number, $password, $specialization1, $specialization2, $specialization3, $specialization4,$clinic_id ]);

        echo "Doctor added successfully!";
        header("Location: profile");
    }
// }
// } else {
//     // Redirect if the request is not POST
//     header("Location: ./index.html");
// }
?>
