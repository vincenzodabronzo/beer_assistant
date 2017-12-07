<?php 

// ini_set('display_errors', 'On');

$command = $_GET["c"];
$id = $_GET["id"];
$step = $_GET["step"];

$dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
if($dbconn->connect_error) {
    die('Connection error: ' . $dbconn->connect_error);
}

$dbconn->query("UPDATE ".$step."_config SET heat = ".$command." WHERE ".$step."_config.id = ".$id.";");

?>