<?php

    require_once 'functions.php';


    if (isset($_POST['user'])){
        $usertemp = sanatizeString($_POST['user']);
        $user = strtolower($usertemp);
        $result = queryMysql("SELECT * FROM members WHERE user='$user'");
    
   

        if($result->num_rows){
            echo "<li class='taken'>&nbsp;&#x2718; "." Username '$usertemp' is taken<br></li>";
        }
        else if(strlen($user) < 5 ){
            echo "<li class='taken'>&nbsp;&#x2718;"." Username must be longer than 5 charecters<br></li>";
        }
        else if(!preg_match("/[a-z]/", $user) ||
            !preg_match("/[0-9]/", $user)){
                echo "<li class='taken'>&nbsp;&#x2718;"." Username must contain atleast one Lowercase charecter and a number</li><br>";
            }

        else{
            echo"<span class='available'>&nbsp;&#x2714; "." Username '$usertemp' is available</span>";
        }
    }

    if (isset($_POST['password'])){
        $pass = sanatizeString($_POST['password']);

        if(strlen($pass) < 5 ){
            echo "<li class='taken'>&nbsp;&#x2718;"." Password must be longer than 5 charecters<br></li>";
        }
        if(!preg_match("/[a-z]/", $pass) ||
            !preg_match("/[0-9]/", $pass) ||
            !preg_match("/[A-Z]/", $pass)){
                echo "<li class='taken'>&nbsp;&#x2718;"." Password must contain atleast one (Lowercase, Uppercase, Number) charecter</li><br>";
            }

        else{
            echo"<span class='available'>&nbsp;&#x2714; "." Password '$pass' is available</span>";
        }
    }
?>