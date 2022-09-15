<?php
session_start();

require "db/connect.php";

$data = file_get_contents('php://input');
$info = json_decode($data);


    $email = filter_var($info->email, FILTER_VALIDATE_EMAIL);
    $createpassword = filter_var($info->createpassword, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
    $confirmpassword = filter_var($info->confirmpassword, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
    
    //checking input vaalues
    if (!$email ) {
        echo "Please enter a valid Email";
        exit();
    }
    elseif(strlen($createpassword) < 8 || strlen($confirmpassword) < 8){
       echo "Password should be 8+ characters!";
       exit();
    }
    elseif ($createpassword !== $confirmpassword) {
        echo'Passwords do not match';
        exit();
    }else {
        // hash password
        $hashed_password = password_hash($createpassword, PASSWORD_DEFAULT); 
    }

    // check if username or email already exist in database
    $inset_check_query = "SELECT * FROM users WHERE  email = '$email'";
    $inset_check_result = mysqli_query($conn, $inset_check_query); 
    if (mysqli_num_rows($inset_check_result)) {
        echo "Username or Email already exits";
        exit;
    }
    else {
        // inser new user into user table
        $inset_user_query = "INSERT INTO users (email, password) 
        VALUES ('$email', '$hashed_password')";
        $insert_user_result = mysqli_query($conn, $inset_user_query);
        if (!mysqli_errno($conn)) {
            $status = array(
                'status' => true,
                'msg' => "User Successfully Register"
              );
                $status = json_encode($status);    
                echo($status);
                exit();    
        }
        else {
            $status = array(
                'status' => false,
                'msg' => "User Fail Register"
              );
                $status = json_encode($status);    
                echo($status);
                exit();    
        }
    }   