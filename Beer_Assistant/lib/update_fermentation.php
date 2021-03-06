<?php
    // ini_set('display_errors', 'On');
    
    $id = $_GET["id"];
    $end_fermentation = $_GET["end_fermentation"];
    
    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    if ($end_fermentation == "1") {
        $dbconn->query("UPDATE fermentation_config SET ending_time = CURRENT_TIMESTAMP WHERE fermentation_config.id = ".$id.";");
    }
    
    
    $result = $dbconn->query("SELECT ba.id, ba.name, ft.temperature, ft.timestamp, ft.heated, ft.cooled, fc.starting_time, fc.ending_time, fc.cooler, fc.heater, fs.temp_max, fs.temp_min  FROM batch AS ba INNER JOIN fermentation_temp as ft ON ba.id = ft.id INNER JOIN fermentation_config AS fc ON ft.id = fc.id INNER JOIN fermentation_step as fs ON fc.id = fs.id WHERE ba.id=".$id." ORDER BY timestamp DESC LIMIT 1");
    
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo    '  Temperature &deg;C:
                <div id="fermentation_temp">' . $row['temperature'] . '</div>
                Max temp &deg;C:
                <div id="max_temp">' . $row['temp_max'] . '</div>
                Min temp &deg;C:
                <div id="min_temp">' . $row['temp_min'] . '</div>
                Collected at:
                <div id="current_timestamp">' . $row['timestamp'] . '</div>
                Heat:
                <div id="heat">' . $row['heated'] . '</div>
                Cool:
                <div id="cool">' . $row['cooled'] . '</div>
                Starting time at:
                <div id="starting_time">' . $row['starting_time'] . '</div>';
        }
    } else {
        echo '  Temperature &deg;C (No data collected): 
                <div id="fermentation_temp">0.0</div>
                Max temp &deg;C: 
                <div id="max_temp">0.0</div>
                Min temp &deg;C: 
                <div id="min_temp">0.0</div>
                Collected at:
                <div id="current_timestamp">--</div>
                Heat:
                <div id="heat">--</div>
                Cool:
                <div id="cool">--</div>
                Starting time at:
                <div id="starting_time">--</div>' ;
    }
    
?>