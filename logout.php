<?php
session_start();
require "dbh.inc.php";

if (isset($_SESSION['unique_id'])) {



    session_unset();
    session_destroy();

    header("Location: ../unstop");
    exit();
} else {
    echo "cant logout";
}
?>