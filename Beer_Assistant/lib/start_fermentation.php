<?php
    // ini_set('display_errors', 'On');
    
    $receipe_name = urldecode( $_GET["receipe_name"] );
    $upper_limit = $_GET["upper_limit"];
    $lower_limit = $_GET["lower_limit"];
    $upper_buffer = $_GET["upper_buffer"];
    $lower_buffer = $_GET["lower_buffer"];

    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    $dbconn->query("INSERT INTO batch(name) VALUES ('".$receipe_name."') " );
    
    $id = $dbconn->insert_id;
    
    $dbconn->query("INSERT INTO fermentation_config(id, starting_time, upper_buffer, lower_buffer) VALUES ( '".$id."', CURRENT_TIMESTAMP, '".$upper_buffer."', '".$lower_buffer."' ) " );
    $dbconn->query("INSERT INTO fermentation_step(id, temp_min, temp_max, step_number) VALUES ( '".$id."', '".$lower_limit."', '".$upper_limit."', '1' ) " );
    
    echo    $receipe_name.'<div id="batch_id">'. $id .'</div> ';
    
    
?>