<?php
    //Config
    define("HOST", "localhost");
    define("DATABASE", "users");
    define("USER", "root");
    define("PASSWORD", "root");

    $user_search = $_POST['usersearch'];

    if (isset($user_search)){
        $link = mysqli_connect(HOST, USER, PASSWORD, DATABASE) or die("Error connection" . mysqli_error($link));

        $query_usersearch = "SELECT login FROM users WHERE firstname LIKE '%$user_search%' OR surname LIKE '%$user_search%'";
        $result_usersearch = mysqli_query($link, $query_usersearch);
        
        mysqli_close($link);
        
        $array_usersearch = mysqli_fetch_row($result_usersearch);
        if($array_usersearch)
            $data["result"] = $array_usersearch[0];
        else
            $data["result"] = "Нету";
    }else{
        $data["result"] = "Пусто у вас было.";
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>