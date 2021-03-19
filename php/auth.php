<?php
    session_start();

    //Config
    define("HOST", "localhost");
    define("DATABASE", "users");
    define("USER", "root");
    define("PASSWORD", "root");

    $credentials = $_POST['credentials'];
    $password = $_POST['password'];

    //Check data
    if(isset($credentials, $password)) {
        $cred = htmlentities($_POST["credentials"]);
        $password = htmlentities($_POST["password"]);
        
        //Connect to database
        $link = mysqli_connect(HOST, USER, PASSWORD, DATABASE) or die("Error connection" . mysqli_error($link));

        $query = "SELECT login, email, password FROM users WHERE login='$cred' OR email='$cred'";

        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
        mysqli_close($link);

        $result = mysqli_fetch_row($result);
        if($result){
            if(password_verify($password, $result[2])){
                $data["result"] = ["credentials" => $cred];
                $_SESSION["data"] = $data;
            }else{
                http_response_code(400);
                $data["result"] = "Авторизация не удалась! Пожалуйста проверьте свои поля.";
                session_destroy();
            }
        }else{
            http_response_code(400);
            $data["result"] = "Авторизация не удалась! Пожалуйста проверьте свои поля.";
            session_destroy();
        } 
    }
    else{
        http_response_code(400);
        $data["result"] = "Форма не заполнена.";
        session_destroy();
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);             
?>