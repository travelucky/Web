<?php
// fetchcontacts.php

header('Content-Type: application/json');

// Include the configuration file
require_once 'config.php';

// Fetch all contact submissions using mysqli
$sql = "SELECT id, name, email, subject, message, submitted_at FROM contacts ORDER BY submitted_at DESC";

$result = $conn->query($sql);

if ($result === false) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Failed to retrieve contact submissions: ' . $conn->error]);
    exit();
}

$contacts = $result->fetch_all(MYSQLI_ASSOC); // Fetch all results as associative arrays

echo json_encode($contacts);
