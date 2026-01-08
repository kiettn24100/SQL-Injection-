<?php

$nameservice = "db-blind-sqli";
$username = "user";
$password = "123456";
$dbname = "blind_sqli_db";

$conn = new mysqli($nameservice, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại ". $conn->connect_error);
}
?>