<?php
    $theme = $_POST["theme"];
    if(isset($theme)){
        setcookie("theme", "", time() - 36, "/");
        setcookie("theme", $theme, 0, "/");
    }else{
        $cookie = $_COOKIE["theme"];
        if(isset($cookie))
            echo $cookie;
        else{
            $cookie = 0;
            setcookie("theme", "", time() - 36, "/");
            setcookie("theme", $cookie, 0, "/");
            echo $cookie;
        }
    }
?>