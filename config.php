<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'address_book';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// 32-character secret key for AES-256 encryption
define('ENC_KEY', 'MySuperSecretKeyForAddressBook26');
define('ENC_METHOD', 'AES-256-CBC');
?>
