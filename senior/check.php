<?php
require_once 'connect.php';


    // Prepare SQL statement to select patient details
    $sql = "SELECT * FROM patients WHERE id = $patient_id";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            
            $oxygen_level = $row['oxygen_level'];
            $heart_rate = $row['heart_rate'];
            $body_temp = $row['body_temp'];

        }
    }
            // Display patient details using HTML
?>
<script>
                    // Function to check vital signs and trigger beep sound if out of range
                    function checkVitalSigns(temperature, oxygenLevel, heartRate) {
                        // Check temperature, oxygen level, and heart rate
                        if (temperature < 36 || temperature > 38 || oxygenLevel < 90 || oxygenLevel > 100 || heartRate < 60 || heartRate > 100) {
                            // Trigger beep sound
                            document.getElementById('con').style.backgroundColor="red";
                        }
                    }
                    // Call the function with the provided vital signs
                    checkVitalSigns(<?php echo $body_temp; ?>, <?php echo $oxygen_level; ?>, <?php echo $heart_rate; ?>);
                </script>