<?php
require_once 'connect.php';

// Check if all required parameters are provided
if (isset($_GET['name'], $_GET['roomNb'])) {
    // Assign values to variables
    $name = $_GET['name'];
    $roomNb = $_GET['roomNb'];

    // Prepare SQL statement to insert data
    $sql = "INSERT INTO patients(id, name, room_no, oxygen_level, heart_rate, body_temp) VALUES (NULL,'$name','$roomNb',NULL,NULL,NULL)";

    // Execute SQL statement
    if ($con->query($sql) === TRUE) {
      header("location: index.php");
        echo "<script> alert('new pateint added successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} 

// Close connection
$con->close();
?>








<html>
<head>
    <title>
    </title>
    <link href="login.css" rel="stylesheet">
</head>

<body class="text-center">
    
<main class="form-signin">
  <form method="get">

    <div class="form-floating">
      <input type="text" name="name" class="form-control" id="name" placeholder="Pateint name">
    </div>
    <div class="form-floating">
      <input type="text" name="roomNb" class="form-control" id="roomNb" placeholder="Room number">
    </div>

    <button class="w-100 btn btn-lg btn-primary" type="submit">Add</button>
       
        
    </table>
  </form>
</main>
</body>

</html>