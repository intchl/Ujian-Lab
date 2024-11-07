<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'skripsi';

$conn = new mysqli($host,$user,$pass,$db_name);

if ($conn->connect_error){
    die("Connection Failed: " .$conn->connect_error);
}

$conn->set_charset("utf8");