<?php
    // ini_set('display_errors', 'On');

    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    #$result = $dbconn->query("SELECT temperature FROM temp_mashing", $query);
    
    $result = $dbconn->query("SELECT * FROM temp_mashing ORDER BY timestamp DESC LIMIT 1");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo    'Temperature &deg;C: 
                    <div id="mashing_temp">' . $row['temperature'] . '</div>
                    Collected at:
                    <div id="current_timestamp">' . $row['timestamp'] . '</div>';
        }
    }
    
?>