<?php
    // ini_set('display_errors', 'On');
    
    $receipe_name = "nuovo nome";

    $dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
    if($dbconn->connect_error) {
        die('Connection error: ' . $dbconn->connect_error);
    }
    
    $dbconn->query("INSERT INTO batch(name) VALUES ('".$receipe_name."') " );
    
    $id = $dbconn->insert_id;
    
    $dbconn->query("INSERT INTO mashing_config(id, starting_time, pump_recirculation) VALUES ( '".$id."', CURRENT_TIMESTAMP, '0' ) " );
 
    echo    '(Receipe name)<div id="batch_id">'. $id .'</div> ';
    
    $dbconn->query("INSERT INTO mashing_step(id, target_temp, minutes, step_number) VALUES ( '".$id."', '68.0', '60', '1' ) " );
    
    // echo shell_exec("python /var/www/html/beer_assistant/Beer_Assistant/py/mashtune_heat.py 2>&1");  // error collection
    shell_exec("python /var/www/html/beer_assistant/Beer_Assistant/lib/mashtune_heat.py > /dev/null 2>/dev/null &");
?>