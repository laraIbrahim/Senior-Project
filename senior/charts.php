<?php
require_once 'connect.php';

// Check if patient ID is provided
if (isset($_GET['id'])) {
    $patient_id = intval($_GET['id']); // Ensure patient ID is an integer to prevent SQL injection

    // Prepare SQL statement to select all patient readings
    $sql = "SELECT * FROM savedvalues WHERE patient_id = $patient_id ORDER BY time ASC";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // Initialize arrays to hold the data
        $time = [];
        $body_temp = [];
        $heart_rate = [];
        $oxygen_level = [];

        // Fetch the data
        while ($row = $result->fetch_assoc()) {
            $time[] = $row['time'];
            $body_temp[] = $row['body_temp'];
            $heart_rate[] = $row['heart_rate'];
            $oxygen_level[] = $row['oxygen_level'];
        }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0"></script>
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
            max-width: 1500px;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 80%;
            max-width: 400px;
        }

        canvas {
            margin-bottom: 20px;
            background-color: whitesmoke;
        }
    </style>
</head>

<body>
    <div class="container" id="con">
        <h1 style="text-align: center;">Patient Details</h1>
        <canvas id="temperatureChart" width="400" height="200"></canvas>
        <canvas id="oxygenLevelChart" width="400" height="200"></canvas>
        <canvas id="heartRateChart" width="400" height="200"></canvas>
    </div>
    <script>
        // PHP arrays passed to JavaScript
        var time = <?php echo json_encode($time); ?>;
        var body_temp = <?php echo json_encode($body_temp); ?>;
        var oxygen_level = <?php echo json_encode($oxygen_level); ?>;
        var heart_rate = <?php echo json_encode($heart_rate); ?>;

        // Function to create charts using Chart.js
        function createCharts() {
            // Chart for Body Temperature
            var temperatureChartCanvas = document.getElementById('temperatureChart').getContext('2d');
            var temperatureChart = new Chart(temperatureChartCanvas, {
                type: 'line',
                data: {
                    labels: time,
                    datasets: [{
                        label: 'Body Temperature',
                        data: body_temp,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'minute'
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart for Oxygen Level
            var oxygenLevelChartCanvas = document.getElementById('oxygenLevelChart').getContext('2d');
            var oxygenLevelChart = new Chart(oxygenLevelChartCanvas, {
                type: 'line',
                data: {
                    labels: time,
                    datasets: [{
                        label: 'Oxygen Level',
                        data: oxygen_level,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'minute'
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart for Heart Rate
            var heartRateChartCanvas = document.getElementById('heartRateChart').getContext('2d');
            var heartRateChart = new Chart(heartRateChartCanvas, {
                type: 'line',
                data: {
                    labels: time,
                    datasets: [{
                        label: 'Heart Rate',
                        data: heart_rate,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'minute'
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Call the function to create charts
        createCharts();
    </script>
    <script>
        // Refresh the page every 15 seconds
        setInterval(function() {
            location.reload();
        }, 15000);
    </script>
</body>

</html>

<?php
    } else {
        echo "No health readings found for patient ID: $patient_id";
    }
} else {
    echo "Patient ID is required";
}

// Close connection
$con->close();
?>
