<?php
// Database Credentials
 $host = 'localhost';
 $db   = 'catering_db';
 $user = 'root';
 $pass = ''; 

 $conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>