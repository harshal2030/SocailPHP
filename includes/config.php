<?php
ob_start();
date_default_timezone_set("Asia/Kolkata");
$salt1 = "#@$53";
$salt2 = "*&%^2";
try{
    $con = new PDO("mysql:dbname=school;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>