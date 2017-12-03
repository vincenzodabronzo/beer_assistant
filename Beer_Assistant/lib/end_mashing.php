<?php
    // ini_set('display_errors', 'On');
    
    $id = $_GET["id"];

    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }

   $dbconn->query("UPDATE batch SET ending_time = CURRENT_TIMESTAMP WHERE batch.id = ".$id.";");
   
   $result = $dbconn->query("SELECT tm.id, ba.name, tm.temperature, tm.timestamp, ba.starting_time, ba.ending_time FROM temp_mashing AS tm INNER JOIN batch AS ba ON tm.id = ba.id WHERE ba.id=".$id." ORDER BY timestamp DESC LIMIT 1");
   
   if ($result->num_rows > 0) {
       while ($row = $result->fetch_assoc()){
           echo    'Temperature &deg;C:
                    <div id="mashing_temp">' . $row['temperature'] . '</div>
                    Collected at:
                    <div id="current_timestamp">' . $row['timestamp'] . '</div>
                    Starting time at:
                    <div id="starting_time">' . $row['starting_time'] . '</div>
                    Ending time at:
                    <div id="ending_time">' . $row['ending_time'] . '</div>';
           
       }
   }
    
?>