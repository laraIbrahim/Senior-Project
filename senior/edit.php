<html>

<head>
    <title>
    </title>
    <link href="editstyle.css" rel="stylesheet">
    <script>
        // Function to handle the alert and redirection
        function handleResponse(response) {
            // Display an alert with the response
            alert(response);

            // Redirect to index.html
            window.location.href = "index.php";
        }
    </script>
</head>

<body class="text-center">
    <?php

    require_once 'connect.php';
    $name = "";
    $room_no = "";
    $id = "";
    if (isset($_GET['id'], $_GET['name'], $_GET['room_no'])) {
        $id = $_GET['id'];
        $name = $_GET['name'];
        $room_no = $_GET['room_no'];
        $sql = "UPDATE patients
SET room_no = '$room_no', name = '$name'
WHERE id = $id";

        if ($con->query($sql) === TRUE) {
            echo "<script>handleResponse(\"Patient update successfully\")</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $name = "";
        $room_no = "";
        $getPatientData = "SELECT * FROM patients WHERE id = $id";
        $result = mysqli_query($con, $getPatientData);
        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['name'];
            $room_no = $row['room_no'];
        }
    } else {
        echo "All parameters are required";
    }

    // Close connection
    $con->close();
    ?>

<div class="wrapper">
        <form method="get">
            <h1>Edit Form</h1>
            <div class="input-box">
                <input type="text" placeholder="Paient Name" name="name" class="form-control" id="name" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="text" placeholder="Room Number" name="room_no" class="form-control" id="room_no" required>
                <i class='bx bxs-clinic' ></i>
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" class="btn">Edit</button>
        </form>
    </div>
</body>

</html>