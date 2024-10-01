<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$errorMSG = "";

// Helper function to sanitize inputs
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Validate Form Inputs
if (empty($_POST["name"])) {
    $errorMSG .= "Name is required ";
} else {
    $name = clean_input($_POST["name"]);
}

if (empty($_POST["email"])) {
    $errorMSG .= "Email is required ";
} elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $errorMSG .= "Invalid email format ";
} else {
    $email = clean_input($_POST["email"]);
}

if (empty($_POST["msg_subject"])) {
    $errorMSG .= "Subject is required ";
} else {
    $msg_subject = clean_input($_POST["msg_subject"]);
}

if (empty($_POST["phone_number"])) {
    $errorMSG .= "Phone number is required ";
} elseif (!preg_match("/^[0-9]{10}$/", $_POST["phone_number"])) {
    $errorMSG .= "Invalid phone number format ";
} else {
    $phone_number = clean_input($_POST["phone_number"]);
}

if (empty($_POST["message"])) {
    $errorMSG .= "Message is required ";
} else {
    $message = clean_input($_POST["message"]);
}

// Database connection
$host = "localhost"; 
$dbname = "contact_form";
$username = "root";  
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Insert form data into the database if no error
if (empty($errorMSG)) {
    $stmt = $conn->prepare("INSERT INTO submissions (name, email, subject, phone_number, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $msg_subject, $phone_number, $message);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo $errorMSG;
}

$conn->close();
?>
