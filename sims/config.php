<?php

$conn = mysqli_connect('localhost','root','','sims');
    ob_start();
    //Setup Database connection.
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sims";
    $connect = mysqli_connect($hostname,$username,$password, $dbname);

?>