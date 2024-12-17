<?php
session_start();
require "../dbh.inc.php";

$name = $_POST['doc_name'];
$rating = $_POST['rating'];

$name_parts = explode(" ", $name);
$fname = isset($name_parts[0]) ? $name_parts[0] : '';
$lname = isset($name_parts[1]) ? $name_parts[1] : '';

$query1 = "SELECT rating FROM doctors WHERE fname = :fname AND lname = :lname";
$stmt = $pdo->prepare($query1);
$stmt->bindParam(':fname', $fname);
$stmt->bindParam(':lname', $lname);
$stmt->execute();
$result = $stmt->fetch();
$rate=$rating;
echo $fname;
if ($result) {
    // echo "heyyyy";
    $doc=$result['fname']." ".$result['lname'];
    $query="SELECT * FROM rating WHERE doctor=:doc";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':doc', $doc);
    $stmt->Execute();
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
    if($result){
        $rating_prev = $result['rating'];
        echo $rating_prev;
        // if ($rating_prev >= 5) {
        //     exit();
        // }
    
        $rating = ($rating_prev + $rating) / 2;
        if ($rating > 5) {
            $rating = 5;
        }
    }
    else{
        $rating=$_POST['rating'];
    }
    

    $query1 = "UPDATE doctors SET rating = :rating WHERE fname = :fname AND lname = :lname";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindParam(':fname', $fname);
    $stmt1->bindParam(':lname', $lname);
    $stmt1->bindParam(':rating', $rating);
    $stmt1->execute();
    // echo $rating;
    echo "done";
    $patient_id = $_SESSION['unique_id'];
    $doctor=$name;
    $query2 = "INSERT INTO rating (patient_id, doctor, rate) VALUES (:patient_id, :doctor, :rate)";
    $stmt = $pdo->prepare($query2);
    $stmt->bindParam(':patient_id', $patient_id);
    $stmt->bindParam(':doctor', $doctor);
    $stmt->bindParam(':rate', $rate);
    $stmt->execute();

    echo "done";
    header("Location: ../profile");
} else {
    echo "Doctor not found.";
}
?>
