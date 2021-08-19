<?php



$user= "hsweat12";

echo showProfile($user);



function showProfile($user){
    if (file_exists("./profile_images/$user.jpg")){
        echo "<img src=./profile_images/".$user.".jpg style='float:left;'>";
    }
        $mysqlQuerys = new MysqlQuerys();
        // $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");
        $result = $mysqlQuerys->dbResult($user);

    if ($result->num_rows){
        $row = $result->fetch_array(MYSQLI_ASSOC);
        echo stripcslashes($row['text'])."<br style='clear:left;'><br>";
    }

    else echo "<p>Nothing to see here, yet</p><br>";
}

class MysqlQuerys 
{
    function __construct(){
        require_once "./login_creds.php";
        $conn = new mysqli($hn, $un, $pw, $db);
        if(!$conn) echo "Failed to connect to db";
        $this->conn = $conn;
    }
        
    function dbResult($user)
    {
        $stmt = $this->conn->prepare('SELECT * FROM profiles WHERE user = '.' (?)');
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $result = $stmt->get_result();
        // $result = $result->fetch_assoc();
        $stmt->close();
        return $result;
    }
}

    ?>