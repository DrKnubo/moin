<?php 
$host = "localhost";
$user = "root";
$name = "aedaa";
$password = "";

try {
    $mysql = new PDO("mysql:host=$host;dbname=$name", $user, $password);
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e){
        echo "SQL Error: ".$e->getMessage();
    }

?>