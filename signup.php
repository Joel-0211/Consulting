<?php
    include "./db/connect.php";
    include "./functions.php";

    $data = file_get_contents('php://input');
    $datas = json_decode($data);
    // var_dump($datas);
    
    
    $fullname = validateSignUP($datas->fullname);
    $email = validateEmail($datas->email);
    $password = validateSignUP($datas->password);
    $confirmpassword = validateSignUP($datas->confirmpassword);
    // echo $datas->fullname;
    // exit;
    
    isset($datas->fullname, $datas->email, $datas->password,  $datas->confirmpassword,)?:response(false,"invalid parameter","");
    // echo $fullname;
    // exit;

    if(!$fullname){
        response(false,"Enter your full name","");
    }elseif(!$email){
        response(false,"enter a valid mail","");
    }elseif(!$password){
        response(false,"enter your password","");
    }elseif(!$confirmpassword){
        response(false, "confirm your password", "");
    }elseif(strlen($password) < 8 || strlen($confirmpassword) < 8){
        response(false,"password should be 8+ characters","");  
    }else{
        if(strlen($password) < 8 || strlen($confirmpassword) < 8){
            $arr = array(
                'status' => false,
                'msg' =>"password should be 8+ characters ",
             );
                $array = json_encode($arr);
                echo $array;
                exit();
        }else{
            if($password !== $confirmpassword){
                $arr = array(
                    'status' => false,
                    'msg' =>"password dont match ",
                );
                    $array = json_encode($arr);
                    echo $array;
                    exit();
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
                        exit();
                }elseif(mysqli_num_rows($professional_check_result) > 0){
                    $arr = array(
                        'status' => false,
                        'msg' =>"This email is currently in use",
                        );
                        $array = json_encode($arr);
                        echo $array;
                        exit();
                }else{ 
                    $insert_user_query = "INSERT INTO users (fullname, email, password, is_admin) VALUES('$fullname','$email', '$hashed_password', 'client')";
                    
                    if($conn->query($insert_user_query)){
                        response(true,"Registration successful. pls Login","");
                    }else{
                        response(false,"Registration unsuccessful","");
                    };
                }
                
            }
        }
    }
