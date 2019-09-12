<?php
error_reporting(E_ALL);
ini_set('display','On');

$host="localhost:33065";
$user="root";
$pass="";
$database="gymdb";

    $db = new mysqli($host, $user, $pass, $database);
    if ($db -> connect_errno){
        die( "Fallo de conexion a MySql: (" . $db ->mysqli_connect_errno()
        .") ". $db ->mysqli_connect_errno());
    }
    mysqli_set_charset($db,'utf8');

?>