<?php
    // ini_set('display_errors', 'On');
    
    $receipe_name = urldecode( $_GET["receipe_name"] );
    $target_temp = $_GET["upper_limit"];
    
    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    $dbconn->query("INSERT INTO batch(name) VALUES ('".$receipe_name."') " );
    
    $id = $dbconn->insert_id;
    
    $dbconn->query("INSERT INTO mashing_config(id, starting_time, pump) VALUES ( '".$id."', CURRENT_TIMESTAMP, '0' ) " );
    $dbconn->query("INSERT INTO mashing_step(id, target_temp, step_number) VALUES ( '".$id."', '".$target_temp."', '1' ) " );
    
    echo    $receipe_name.'<div id="batch_id">'. $id .'</div> ';

?>