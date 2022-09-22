<?php
    include "./db/connect.php";
    include "./functions.php";

    $data = file_get_contents("php://input");
    $info = json_decode($data);

    $professionals_name = validateSignUP($info->name);
    $professionals_field = validateSignUP($info->field);
    $email = validateEmail($info->email);
    $password = validateSignUP($info->password);
    $confirmpassword = validateSignUP($info->confirmpassword);

    isset($professionals_name,
    $professionals_field,
    $email,
    $password,
    $confirmpassword,)?:response(false,"invalid parameter","");

    if(!$professionals_name){
        response(false,"enter your name","");
    }
    if(!$professionals_field){
        response(false,"enter your field","");
    }
    if(!$email){
        response(false,"enter a valid email","");
    }elseif(!$password){
        response(false,"enter a strong passsword","");
    } elseif(!$confirmpassword){
        response(false,"Please confirm your password","");
    } elseif(strlen($password) < 8 || strlen($confirmpassword) < 8){
        response(false,"password should be 8+ characters","");
    }else{
        if(strlen($password) < 8 || strlen($confirmpassword) < 8){
            response(false,"password should be 8+ characters","");
        }else{
        if($password !== $confirmpassword){
            $arr = array(
                'status' => false,
                'msg' =>"password dont match ",
                );
                $array = json_encode($arr);
                echo $array;
        }else{
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $user_check_query = "SELECT * FROM users where  email = '$email'";
            $user_check_result = mysqli_query($conn, $user_check_query);

            $professional_check_query = "SELECT * FROM professionals where email= '$email'";
            $professional_check_result = mysqli_query($conn, $professional_check_query);
        
            if(mysqli_num_rows($user_check_result) > 0){
                $arr = array(
                    'status' => false,
                    'msg' =>"This email is currently in use",
                    );
                    $array = json_encode($arr);
                    echo $array;
            }elseif(mysqli_num_rows($professional_check_result) > 0){
                $arr = array(
                    'status' => false,
                    'msg' =>"This email is currently in use",
                    );
                    $array = json_encode($arr);
                    echo $array;
                    exit();
            }else{
                $insert_user_query = "INSERT INTO professionals (email, password, professionals_name, professionals_field, is_admin) VALUES('$email', '$hashed_password', '$professionals_name', '$professionals_field','professional')";
        
                $insert_user_result = mysqli_query($conn, $insert_user_query);
        
                if(!mysqli_error($conn)){
                    $arr = array(
                        'status' => true,
                        'msg' =>"Registration successful",
                        );
                        $array = json_encode($arr);
                        echo $array;
                }else{
                    $arr = array(
                        'status' => false,
                        'msg' =>"Regstration unsuccessful",
                        );
                        $array = json_encode($arr);
                        echo $array;
                    }
                }
            }
        }
       
    }