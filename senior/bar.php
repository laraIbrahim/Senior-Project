<?php
require 'connect.php';
// Function to retrieve oxygen level, heart rate, body temperature values from database
function getDataFromDatabase($con) {
    $data = array();

    // Query to retrieve oxygen level, heart rate, and body temp values
    $sql = "SELECT oxygen_level, heart_rate, body_temp FROM patients";
    $result = $con->query($sql);

    if ($result && $result->num_rows > 0) {
        // Fetch data and store in array
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }


    return $data;
}

// Get data from database
$data = getDataFromDatabase($con);

// Close database connection
$con->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar Graph</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <h1>Bar Graph</h1>
    <canvas id="barGraph"></canvas>

    <script>
        // Extract oxygen, hr, and bt values from PHP data
        var data = <?php echo json_encode($data); ?>;

        // Extract individual arrays for each value
       
        var ids = data.map(obj => obj.id);
        var oxygenLevel = data.map(obj => obj.oxygen_level);
        var heartRate = data.map(obj => obj.heart_rate);
        var bodyTemp = data.map(obj => obj.body_temp);

        // Chart.js bar graph configuration
        var ctx = document.getElementById('barGraph').getContext('2d');
        var barGraph = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Array.from(Array(data.length).keys()), // Assuming a sequential index for data points
                datasets: [
                    {
                        label: 'OxygenLevel',
                        data: oxygenLevel,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'HeartRate',
                        data: heartRate,
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'BodyTemp',
                        data: bodyTemp,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'ID' // Label for x-axis
                },
                min: 1, // Minimum value for x-axis
                max: 100 // Maximum value for x-axis
            },
            y: {
                type: 'linear', // Use 'linear' for a numeric scale
                min: 0, // Minimum value on the y-axis
                max: 100, // Maximum value on the y-axis
                ticks: {
                    stepSize: 20 // Specify the interval between ticks
                }
            }
            
        }
    }
});
    </script>
    
                <script>
                  //  Refresh the page every 15 seconds
                    setInterval(function() {
                        location.reload();
                    }, 15000);
                </script>
</body>
</html>