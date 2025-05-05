<?php
session_start();

$servername = "fill in the using your information";
$username = "fill in the using your information";
$password = "fill in the using your information";
$dbname = "fill in the using your information";

$db = null;
try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

