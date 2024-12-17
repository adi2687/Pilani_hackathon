<?php

require "../dbh.inc.php";

$name = $_POST['name'];
if(empty($name)){
    echo "it is empty";
    exit();
}
$query = "SELECT * FROM doctors WHERE fname LIKE :name OR lname LIKE :name OR CONCAT(fname, lname) LIKE :name";
$stmt = $pdo->prepare($query);
$stmt->execute(['name' => '%' . $name . '%']);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$detail = "";

foreach($result as $row) {
    $detail .= $row['fname'] . " " . $row['lname'] . "<br>" .
               $row['email'] . " " . $row['number'] . "<br><br>"
               .$row['specialization1']."\t". $row['specialization2']."\t".$row['specialization3']."\t".$row['specialization4'];
}

echo $detail;


