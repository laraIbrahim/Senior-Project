<?php
require_once 'connect.php';

// Check if all required parameters are provided
if (isset($_GET['id'], $_GET['oxygen_level'], $_GET['heart_rate'], $_GET['body_temp'])) {
    // Assign values to variables
    $id = $_GET['id'];
    $oxygen_level = $_GET['oxygen_level'];
    $heart_rate = $_GET['heart_rate'];
    $body_temp = $_GET['body_temp'];

    // Prepare SQL statement to insert data

    $sql = "INSERT INTO savedvalues (patient_id, body_temp, heart_rate, oxygen_level, time) 
VALUES ($id, $body_temp, $heart_rate, $oxygen_level, NOW())";

    // Execute SQL statement
    if ($con->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "All parameters are required";
}

// Close connection
$con->close();
