<?php

    require_once './login_creds.php';

    $connection = new mysqli($hn, $un, $pw,$db);
    if (!$connection) die("Could not connect to database");
    

    

    function createTable($name, $query){
        queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
        echo "Table $name has been made or already exists.<br>";
    }

    //Make class with prepared statements
    function queryMysql($query){
        global $connection;
        $result = $connection->query($query);
        if(!$result) die("Fatal Error Querying the Database");
        return $result;
    }

    function destroySession(){
        //set session super global to empty array
        $_SESSION = array();

        //clear session id out of any cookies
        if (session_id() != "" || isset($_COOKIE[session_id()])){
            setcookie(session_id(), '', time()-2592000, '/');
        }
        session_destroy();
    }

    function sanatizeString($string){
        global $connection;
        $string = strip_tags($string);
        $string = htmlentities($string);
        if (get_magic_quotes_gpc()){
            $string = stripcslashes($string);
        }
        return $connection->real_escape_string($string);
    }

    function sanatizeCode($code){
        $code = htmlentities($code);
        return $code;
    }

    function showProfile($user){
        if (file_exists("./profile_images/$user.jpg")){
            echo "<img src=./profile_images/".$user.".jpg style='float:left;'>";
        }

            $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");
            

        if ($result->num_rows){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            echo stripcslashes($row['text'])."<br style='clear:left;'><br>";
        }

        else echo "<p>Nothing to see here, yet</p><br>";
    }

    

?>