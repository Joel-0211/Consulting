<?php
    error_reporting(E_ALL);
    include "./db/connect.php";
    include "./functions.php";

    $data = file_get_contents('php://input');
    $datas = json_decode($data);

    @$email = validateEmail($datas->email);
    @$password = validateSignUP($datas->password);

    isset($email, $password)?:response(false, "Fill in all fields", "");
    if(!$email){
        $arr = array(
            'status' => false,
            'msg' =>"Email required",
        ); 
            $array = json_encode($arr);
            echo $array;
            exit();
    }elseif(!$password){
        $arr = array(
            'status' => false,
            'msg' =>"password required",
         );
            $array = json_encode($arr);
            echo $array;
            exit();
    }else{
        $fetch_user_query = "SELECT * FROM users WHERE email = '$email' ";
        $fetch_user_result = mysqli_query($conn, $fetch_user_query);
        $user_conn = mysqli_num_rows($fetch_user_result);
        $user_record = mysqli_fetch_assoc($fetch_user_result);
        @$user_password = $user_record['password'];
        
        

        $fetch_professional_query = "SELECT * FROM professionals WHERE email = '$email' ";
        $fetch_professional_result = mysqli_query($conn, $fetch_professional_query);
        $professional_conn =  mysqli_num_rows($fetch_professional_result);
        $professional_record = mysqli_fetch_assoc($fetch_professional_result);
        @$professional_password = $professional_record['password'];

        
        if ($user_conn  and $professional_conn>0){
            if(password_verify($password, $user_password)){
                if($user_record['is_admin'] == 'client'){
                    $arr = array(
                        'status' => True,
                        'msg'=> "Login Successful'",
                        'account_type' => "Clients' account"
                    );
                        $array = json_encode($arr);
                        echo $array;
                        exit();
                }
                if(password_verify($password, $professional_password)){
                    if($professional_record['is_admin'] == 'professional'){
                        $arr = array(
                            'status' => True,
                            'msg'=> "Login Successful'",
                            'account_type' => "professionals' account"
                        );
                            $array = json_encode($arr);
                            echo $array;
                    }       exit();
                }else{
                    $arr = array(
                        'status' => false,
                        'msg' =>"Incorrect username or password",
                    );
                        $array = json_encode($arr);
                        echo $array;
                        exit();
                }
            }else{
                $arr = array(
                    'status' => false,
                    'msg' =>"Incorrect username or password",
                 );
                    $array = json_encode($arr);
                    echo $array;
                    exit();
            }
        }else{
            $arr = array(
                'status' => false,
                'msg' =>"User not found",
             );
                $array = json_encode($arr);
                echo $array;
                exit();
        }
    }