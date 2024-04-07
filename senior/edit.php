<html>

<head>
    <title>
    </title>
    <link href="login.css" rel="stylesheet">
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

    <main class="form-signin">
        <form method="get">

            <div class="form-floating">
                <input type="text" name="name" class="form-control" id="name" placeholder="Pateint name" value="<?php echo $name; ?>">
            </div>
            <div class="form-floating">
                <input type="text" name="room_no" class="form-control" id="room_no" placeholder="Room number" value="<?php echo $room_no; ?>">
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <button class="w-100 btn btn-lg btn-primary" type="submit">Edit</button>


            </table>
        </form>
    </main>
</body>

</html>