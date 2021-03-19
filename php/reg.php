<?php
    //Config
    define("HOST", "localhost");
    define("DATABASE", "users");
    define("USER", "root");
    define("PASSWORD", "root");
    define("SECRET", "CAPTCHA_CODE");

    $firstname = htmlentities($_POST['firstname']);
    $surname = htmlentities($_POST['surname']);
    $email = htmlentities($_POST['email']);
    $login = htmlentities($_POST['login']);
    $password = htmlentities($_POST['password']);
    $sex = htmlentities($_POST['sex']);
    $age = htmlentities($_POST['age']);
    $recaptcha_post = $_POST['g-recaptcha-response']; 

    //Проверка всех полей формы
    if(isset($firstname, $surname, $email, $login, $password, $sex, $age, $recaptcha_post)){
        //Подключаем библиотеку для капчи
        require_once (dirname(__FILE__).'/recaptcha/autoload.php');
        //Создаем класс из библиотеки
        $recaptcha = new \ReCaptcha\ReCaptcha(SECRET);
        //Чекаем пришедший ключ
        $resp = $recaptcha->verify($recaptcha_post, $_SERVER['REMOTE_ADDR']);
        //Если ок, то идем по обычной проверке и регистрации.
        if ($resp->isSuccess()){
            $regex = "/(?=.*[0-9])(?=.*[!-.:-@[-`{-~])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!-.:-@[-`{-~]{8,}/";
            if(preg_match($regex, $password)){
                $password = password_hash($password, PASSWORD_DEFAULT);

                $link = mysqli_connect(HOST, USER, PASSWORD, DATABASE) or die("Error connection" . mysqli_error($link));

                $check_email = "SELECT email FROM users WHERE email = '$email'";
                $check_result = mysqli_query($link, $check_email) or die("Ошибка " . mysqli_error($link));
                $check_result = mysqli_fetch_row($check_result);
                if($check_result){
                    http_response_code(400);
                    $data["result"] = 'Регистрация не удалась. Ваша почта уже зарегистрирована.';
                }else{
                    
                    $check_login = "SELECT login FROM users WHERE login = '$login'";
                    $check_result = mysqli_query($link, $check_login) or die("Ошибка " . mysqli_error($link));
                    $check_result = mysqli_fetch_row($check_result);

                    if($check_result){
                        http_response_code(400);
                        $data["result"] = 'Регистрация не удалась. Такой логин существует. Придумайте другой.';
                    }else{
                        $query = "INSERT INTO users (login, email, password, age, sex, firstname, surname) VALUES ('$login', '$email', '$password', '$age', '$sex', '$firstname', '$surname')";

                        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
                        mysqli_close($link);

                        if($result){
                            http_response_code(200);
                            $data["result"] = "Регистрация прошла успешно!";
                        }
                        else{
                            http_response_code(400);
                            $data["result"] = 'Регистрация не удалась. Проверьте все поля.';
                        }
                    }
                }
            } else {
                http_response_code(400);
                $data["result"] = "Извините! Ваш пароль должен иметь спец.символы, латинские заглавные и прописные буквы.";
            }   
        } else {
            http_response_code(400);
            $errors = $resp->getErrorCodes();
            $data['error-captcha']=$errors;
            $data['result']='Ошибка капчи';
        }
    }else{
        http_response_code(400);
        $data["result"] = "Извините! Ваш запрос неполный!";
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>