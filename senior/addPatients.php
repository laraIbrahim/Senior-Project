<?php
require_once 'connect.php';

// Check if all required parameters are provided
if (isset($_GET['name'], $_GET['roomNb'])) {
    // Assign values to variables
    $name = $_GET['name'];
    $roomNb = $_GET['roomNb'];

    // Check if the room number is already taken
    $checkRoomSql = "SELECT * FROM patients WHERE room_no = '$roomNb'";
    $checkRoomResult = $con->query($checkRoomSql);

    if ($checkRoomResult->num_rows > 0) {
        // Room number is already taken, alert the website
        echo "<script>alert('Room number $roomNb is already taken. Please choose another room.');</script>";
    } else {
        // Room number is available, proceed with inserting data
        // Prepare SQL statement to insert data
        $sql = "INSERT INTO patients(id, name, room_no, oxygen_level, heart_rate, body_temp) VALUES (NULL,'$name','$roomNb',NULL,NULL,NULL)";

        // Execute SQL statement
        if ($con->query($sql) === TRUE) {
            header("location: index.php");
            echo "<script> alert('New patient added successfully');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }
} 

// Close connection
$con->close();
?>









<html>
<head>
    <title>
    </title>
    <link href="addstyle.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="text-center">
<div class="wrapper">
        <form method="get">
            <h1>Add Patients Form</h1>
            <div class="input-box">
                <input type="text" placeholder="Paient Name" name="name" class="form-control" id="name" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="text" placeholder="Room Number" name="roomNb" class="form-control" id="roomNb" required>
                <i class='bx bxs-clinic' ></i>
            </div>
           
            <button type="submit" class="btn">Add Patient</button>
        </form>
    </div>
</body>

</html>