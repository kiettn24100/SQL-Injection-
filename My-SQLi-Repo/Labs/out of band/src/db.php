<?php
    $service_name = 'db-oob-sqli';
    $username = 'user';
    $password = '123456';
    $dbname = 'oob_sqli_db';

    $conn = new mysqli($service_name, $username, $password, $dbname);

    if ($conn->connect_error) {
        die('Kết nối thất bại '. $conn->connect_error);
    }
?>