<?php
    // ini_set('display_errors', 'On');
    
    $receipe_name = "nuovo nome";

    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    $dbconn->query("INSERT INTO batch(name) VALUES ('".$receipe_name."') " );
    
    $id = $dbconn->insert_id;
    
    $dbconn->query("INSERT INTO fermentation_config(id, starting_time) VALUES ( '".$id."', CURRENT_TIMESTAMP ) " );
    $dbconn->query("INSERT INTO fermentation_step(id, temp_min, temp_max, minutes, step_number) VALUES ( '".$id."', '18', '22', '60', '1' ) " );
    
    echo    '(Receipe name)<div id="batch_id">'. $id .'</div> ';
    
?>