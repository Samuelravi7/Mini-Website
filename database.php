<?php
session_start();

$servername = "lrgs.ftsm.ukm.my";
$username = "a194594";
$password = "littlepinkdog";
$dbname = "a194594";

$db = null;
try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

