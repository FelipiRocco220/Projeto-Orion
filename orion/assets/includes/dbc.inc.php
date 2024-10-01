<?php
$dbhost = 'localhost';
$dbname = 'orion';
$dbuser = 'root';
$dbpwd = '';

try {
    $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpwd);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed :(");
}