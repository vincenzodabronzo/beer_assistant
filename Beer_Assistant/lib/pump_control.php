<?php 

$command = $_GET["c"];
$id = $_GET["id"];

$dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
if($dbconn->connect_error) {
    die('Connection error: ' . $dbconn->connect_error);
}

$dbconn->query("UPDATE mashing_config SET heat = ".$command." WHERE mashing_config.id = ".$id.";");

?>