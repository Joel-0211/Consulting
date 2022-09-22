<?php
include "./db/connect.php";

function validateSignUP($data){
    $data = trim($data);

    $data = stripslashes($data);

    $data = htmlspecialchars($data);
    
    $data = filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    return $data;
}

function validateEmail($data){
    $data = trim($data);

    $data = stripslashes($data);

    $data = htmlspecialchars($data);
    
    $data = filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $data = filter_var($data, FILTER_SANITIZE_EMAIL);

    $data = filter_var($data, FILTER_VALIDATE_EMAIL);

    return $data;
}

function response($x,$y,$v){
    global $conn;
    $arr = array(
        'status' => $x,
        'msg' =>"$y"
    );
        $array = json_encode($arr);
        echo $array;
        exit(); 
}
