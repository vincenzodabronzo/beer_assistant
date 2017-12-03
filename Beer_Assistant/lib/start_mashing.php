<?php
    ini_set('display_errors', 'On');

    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    $dbconn->query("INSERT INTO batch(name) VALUES ('nuovo nome') " );
    
    $id = $mysqli->insert_id;
    
    $dbconn->query("INSERT INTO mashing_config(id, starting_time, pump_recirculation) VALUES ( '".$id."', CURRENT_TIMESTAMP, '0' ) " );
    
    
    $result = $dbconn->query("SELECT id FROM batch WHERE mc.ending_time is NULL ORDER BY mc.id DESC LIMIT 1");
    
    if ($result->num_rows > 0) {
        if ($row = $result->fetch_assoc()){
            echo    '(Receipe name)<div id="batch_id">'. $row['id'] .'</div> ';
            // shell_exec("python py/mashtune_heat.py > /dev/null 2>/dev/null &");
        }
    } else {
        echo    '(No open batch found - Start mashing)<div id="batch_id">0</div> ';
    }

    
    
    
    $result = $dbconn->query("SELECT mc.id FROM mashing_config AS mc WHERE mc.ending_time is NULL ORDER BY mc.id DESC LIMIT 1");
    
    if ($result->num_rows > 0) {
        if ($row = $result->fetch_assoc()){
            echo    '(Receipe name)<div id="batch_id">'. $row['id'] .'</div> ';
            // shell_exec("python py/mashtune_heat.py > /dev/null 2>/dev/null &");
        }
    } else {
        echo    '(No open batch found - Start mashing)<div id="batch_id">0</div> ';
    }

?>