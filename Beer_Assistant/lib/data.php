<?php

    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    #$result = $dbconn->query("SELECT temperature FROM temp_mashing", $query);
    $result = $dbconn->query("SELECT * FROM temp_mashing ORDER BY timestamp DESC LIMIT 1", $query);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo 'Current Temperature: <b>' . $row['temperature'] . '</b><br>' . $row['timestamp'];
        }
    }
    
?>