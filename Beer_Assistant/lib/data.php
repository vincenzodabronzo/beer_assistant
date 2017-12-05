<?php
    // ini_set('display_errors', 'On');
    
    $id = $_GET["id"];
    
    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    $result = $dbconn->query("SELECT ba.id, ba.name, mt.temperature, mt.timestamp, mc.starting_time, mc.ending_time, mc.pump_recirculation, mc.heat FROM batch AS ba INNER JOIN mashing_temp as mt ON ba.id = mt.id INNER JOIN mashing_config AS mc ON mt.id = mc.id WHERE ba.id=".$id." ORDER BY timestamp DESC LIMIT 1");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo    'Temperature &deg;C:
                    <div id="mashing_temp">' . $row['temperature'] . '</div>
                    Collected at:
                    <div id="current_timestamp">' . $row['timestamp'] . '</div>
                    Heat:
                    <div id="heat">' . $row['heat'] . '</div>
                    Pump recirculation:
                    <div id="pump_recirculation">' . $row['pump_recirculation'] . '</div>
                    Starting time at:
                    <div id="starting_time">' . $row['starting_time'] . '</div>
                    Ending time at:
                    <div id="ending_time">--</div>';
        }
    } else {
        echo 'Temperature &deg;C: (No data collected)
                     <div id="mashing_temp">0.0</div>
                     Collected at:
                    <div id="current_timestamp">--</div>
                    Heat:
                    <div id="heat">--</div>
                    Pump recirculation:
                    <div id="pump_recirculation">--</div>
                    Starting time at:
                    <div id="starting_time">--</div>
                    Ending time at:
                    <div id="ending_time">--</div>' ;
    }
    
?>