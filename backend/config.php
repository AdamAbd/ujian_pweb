<?php
function getConnect()
{
    $host = 'localhost';
    $db_name = 'db_notes';
    $username = 'root';
    $password = '';
    $conn = new mysqli($host, $username, $password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
