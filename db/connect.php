<?php 
$host = "localhost";
$username = "root";
$password = "";
$database = "consulting";
$conn = mysqli_connect($host, $username,$password,$database);

if(!$conn = mysqli_connect($host, $username, $password, $database)){
    echo ("failed to connect");
    exit;
}
