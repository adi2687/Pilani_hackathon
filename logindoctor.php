<?php
require "dbh.inc.php";  // Your database connection file

    $ref_id = $_POST['ref_id'];
    $password = $_POST['password'];
    $query = "SELECT * FROM doctors WHERE unique_id = :ref_id AND password=:password";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':ref_id', $ref_id);
    $stmt->bindParam(":password",$password);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    if ($result){
        session_start();
        $_SESSION['unique_id']=$result['unique_id'];
        echo "success";
    }
    else{
        echo "error";
    }

    
?>
