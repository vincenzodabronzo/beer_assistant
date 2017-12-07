<?php

// ini_set('display_errors', 'On');

$command = $_GET["command"];
$id = $_GET["id"];
$step = $_GET["step"];
$device_name = $_GET["device_name"];

$dbconn =  new mysqli('localhost', 'pi', 'raspberry', 'dbeer');
if($dbconn->connect_error) {
    die('Connection error: ' . $dbconn->connect_error);
}

$dbconn->query("UPDATE ".$step."_config SET ".$device_name." = ".$command." WHERE ".$step."_config.id = ".$id.";");

?>