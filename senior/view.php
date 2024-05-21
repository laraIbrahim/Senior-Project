<?php
require_once 'connect.php';

// Check if patient ID is provided
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    // Prepare SQL statement to select patient details
    $sql = "SELECT p.*, sv.body_temp, sv.heart_rate, sv.oxygen_level
    FROM patients p
    JOIN savedvalues sv ON p.id = sv.patient_id
    WHERE p.id = $patient_id
    ORDER BY sv.time DESC
    LIMIT 1";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // Output data of the row
        while ($row = $result->fetch_assoc()) {
            $name = $row['name'];
            $room_no = $row['room_no'];
            $body_temp = $row['body_temp'];
            $heart_rate = $row['heart_rate'];
            $oxygen_level = $row['oxygen_level'];
?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Patient Details</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                <link href="/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f4f4f4;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                    }

                    .container {
                        background-color: #fff;
                        border-radius: 10px;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                        padding: 20px;
                        width: 80%;
                        max-width: 300px;
                    }

                    .icon {
                        color: #007bff;
                        margin-right: 10px;
                        width: 80px;
                        height: 80px;
                        font-size: 50px;
                        display: inline-flex;
                        justify-content: center;
                        align-items: center;
                    }

                    .item {
                        display: flex;
                        align-items: center;
                    }

                    .label {
                        font-weight: bold;
                        margin-bottom: 5px;
                        width: 150px;
                        /* Set a fixed width for the labels to ensure alignment */
                    }

                    .value {
                        font-size: xx-large;
                        flex: 1;
                        /* Allow the value text to fill the remaining space */
                        text-align: end;
                        /* Align the value text to the center */
                    }
                </style>
            </head>

            <body>
                <div class="container" id="con">
                    <h1 style="text-align: center;">Patient Details</h1>
                    <div class="details">
                        <div class="item">
                            <span class="label"><i class="fas fa-id-badge icon"></i></span>
                            <span class="value"><?php echo $patient_id; ?></span>
                        </div>
                        <div class="item">
                            <span class="label"><i class="fas fa-user icon"></i></span>
                            <span class="value"><?php echo $name; ?></span>
                        </div>
                        <div class="item">
                            <span class="label"><i class="fas fa-door-open icon"></i></span>
                            <span class="value"><?php echo $room_no; ?></span>
                        </div>
                        <div class="item">
                            <span class="label"><i class="fas fa-lungs icon"></i></span>
                            <span class="value"><?php echo $oxygen_level; ?></span>
                        </div>
                        <div class="item">
                            <span class="label"><i class="fas fa-heartbeat icon"></i></span>
                            <span class="value"><?php echo $heart_rate; ?></span>
                        </div>
                        <div class="item">
                            <span class="label"><i class="fas fa-thermometer-half icon"></i></span>
                            <span class="value"><?php echo $body_temp; ?></span>
                        </div>
                    </div>
                    <?php
                    $barUrl = 'charts.php?id=' . $row['id'];
                    echo '<a href="' . $barUrl . '" class="btn btn-outline-primary">view as charts</a>';
                    ?>

                </div>
                <script>
                    // Function to check vital signs and trigger beep sound if out of range
                    function checkVitalSigns(temperature, oxygenLevel, heartRate) {
                        // Check temperature, oxygen level, and heart rate
                        if (temperature < 34 || temperature > 38 || oxygenLevel < 90 || heartRate < 60 || heartRate > 100) {
                            // Trigger beep sound
                            document.getElementById('con').style.backgroundColor = "red";
                        }
                    }
                    // Call the function with the provided vital signs
                    checkVitalSigns(<?php echo $body_temp; ?>, <?php echo $oxygen_level; ?>, <?php echo $heart_rate; ?>);
                </script>
                <script>
                    //  Refresh the page every 15 seconds
                    setInterval(function() {
                        location.reload();
                    }, 15000);
                </script>
            </body>

            </html>

<?php
        }
    } else {
        echo "No patient found with ID: $patient_id";
    }
} else {
    echo "Patient ID is required";
}

// Close connection
$con->close();

?>