<?php
require_once 'config.php';

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// Tell the browser to automatically reconnect every 2 seconds
echo "retry: 2000\n";

$result = $conn->query("SELECT COALESCE(MAX(updated_at), '0') AS last_update, COUNT(*) AS total FROM contacts");
$row = $result->fetch_assoc();

$state = $row['last_update'] . '|' . $row['total'];

echo "data: " . $state . "\n\n";
ob_flush();
flush();
?>
