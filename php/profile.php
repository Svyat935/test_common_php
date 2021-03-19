<?php
    session_start();

    //Config
    define("HOST", "localhost");
    define("DATABASE", "users");
    define("USER", "root");
    define("PASSWORD", "root");
    
    $credentials = $_SESSION["data"]["result"]["credentials"];
    if(isset($credentials)){
        //Connect to database
        $link = mysqli_connect(HOST, USER, PASSWORD, DATABASE) or die("Error connection" . mysqli_error($link));

        $query = "SELECT * FROM users WHERE login='$credentials' OR email='$credentials'";
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
        
        $query_stat = "SELECT COUNT(*) FROM users";
        $result_stat = mysqli_query($link, $query_stat) or die("Ошибка " . mysqli_error($link));
        
        $date_array = getdate();
        $begin_date = date("Y-m-d", mktime(0,0,0, $date_array['mon'],1, $date_array['year']));
        $end_date = date("Y-m-d", mktime(0,0,0, $date_array['mon'] + 1,0, $date_array['year']));
        $query_data = "SELECT COUNT(*) FROM users WHERE datetime>='$begin_date' AND datetime<='$end_date'";
        $result_data = mysqli_query($link, $query_data) or die("Ошибка " . mysqli_error($link));

        $query_last_row = "SELECT email, login FROM users ORDER BY datetime DESC LIMIT 0,1";
        $result_last_row = mysqli_query($link, $query_last_row) or die("Ошибка " . mysqli_error($link));   

        mysqli_close($link);
        
        $result = mysqli_fetch_row($result);
        $result_stat = mysqli_fetch_row($result_stat);
        $result_data = mysqli_fetch_row($result_data);
        $result_last_row = mysqli_fetch_row($result_last_row);

        $data["result"] = [
            "profile" => [
                "login" => $result[1],
                "email" => $result[2],
                "age" => $result[4] == "1" ? "Старше 18 лет." : "Младше 18 лет.",
                "sex" => $result[5],
                "firstname" => $result[6],
                "surname" => $result[7]
            ],
            "statistics" => [
                "count" => $result_stat[0],
                "rows_for_date" => $result_data[0],
                "last_row" => ("email:".$result_last_row[0]."; login:".$result_last_row[1])
            ]
        ];
    }
    else{
        http_response_code(400);
        $data["result"] = "Ах вы хитрец!";
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
