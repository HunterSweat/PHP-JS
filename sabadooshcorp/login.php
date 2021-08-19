<?php

    include_once 'header.php';

    $error = $user = $password = "";

    if(isset($_POST['user'])){
        $usertemp = sanatizeString($_POST['user']);
        $user = strtolower($usertemp);
        $password = sanatizeString($_POST['password']);

        if ($user == '' || $password == ''){
            $error = 'Not all fields were entered';
        }
        else{
            
            $result = queryMysql("SELECT user,pass FROM members WHERE user='$user'");
            $rowFromTable = $result->fetch_array(MYSQLI_NUM);
            $verifiedPassword = password_verify($password, $rowFromTable[1]);
            if(!$verifiedPassword) $error = "Invalid Username/Password";

            else {
                $_SESSION['user'] = $user;
                
                $_SESSION['password'] = $verifiedPassword;
                
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                
                echo "You are logged in as $user. Please <a data-transition='slide' href='members.php?view=$user'> Click here</a> to continue.</div></body></html>";
                exit;
            }
        }
    }


    echo <<<_END

        <form method="POST" action="login.php">$error

        <div data-role="fieldcontain">
            <label></label>
            Please enter your details to login
        </div>

        <div data-role="fieldcontain">
            <label>Username</label>
            <input type="text" maxlength="16" name="user" value="$user">
            <label></label>
        </div>

        <div data-role="fieldcontain">
            <label>Password</label>
            <input type="password" maxlength="16" name="password" value="$password">
            <label></label>
        </div>

        <div data-role="fieldcontain">
            <input data-transition="slide" type="submit" value="Login In">
        </div>


        </form>
        </div>
        </body>
        </html>


    _END;




?>