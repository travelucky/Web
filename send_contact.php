<?php

// Include the configuration file
require_once 'config.php';

// Database connection parameters
$host = "localhost";
$db   = "contact_form_db";
$user = "root";
$pass = "123456";
$charset = 'utf8mb4';

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Disable emulation
];

try {
    // Establish database connection
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    // Log the error and terminate the script
    error_log($e->getMessage());
    exit('Database connection failed.');
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        exit('Please fill in all required fields.');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit('Invalid email format.');
    }

    // Insert data into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);
        $status = "Your information has been successfully submitted!";
    } catch (Exception $e) {
        // Log the error and set the status message
        error_log("Database Insert Error: " . $e->getMessage());
        $status = "Submission failed. Please try again later.";
    }

    // Display status message and provide a link to return to the form
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta http-equiv='refresh' content='5;url=contact.html'>
        <title>Submission Status</title>
        <link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap' rel='stylesheet'>
        <style>
            body {
                font-family: 'Roboto', sans-serif;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .status-container {
                background: #fff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                text-align: center;
            }
            .status-container h1 {
                margin-bottom: 20px;
            }
            .status-container p {
                font-size: 18px;
                color: #333;
            }
            .status-container a {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #333;
                color: #fff;
                text-decoration: none;
                border-radius: 4px;
            }
            .status-container a:hover {
                background-color: #575757;
            }
        </style>
    </head>
    <body>
        <div class='status-container'>
            <h1>Submission Status</h1>
            <p>$status</p>
            <a href='contact.html'>Return to Contact Form</a>
        </div>
    </body>
    </html>";
}
