<?php
    include_once 'header.php';

    if(!$loggedIn) die("</div></body></html>");

    if(isset($_GET['view'])){
        $view = sanatizeString($_GET['view']);
        if($view == $user) $name = "Your";
        else $name = "$view's";
        echo "<h3>$name Profile</h3>";
        showProfile($view);
    }

    if(isset($_GET['add']))
    {
        $add = sanatizeString($_GET['add']);
        $result = queryMysql("SELECT * FROM friends WHERE user='$add' AND friend='$user'");

        if(!$result->num_rows)
        {
            queryMysql("INSERT INTO friends VALUES('$add', '$user')");
        }
    }

    if(isset($_GET['remove']))
    {
        $remove = sanatizeString($_GET['remove']);
        queryMysql("DELETE FROM friends WHERE user='$remove' AND friend='$user'");

    }



    $results = queryMysql("SELECT user FROM members ORDER BY user");
    if(!$results) die("Could not retrieve users");

    $num = $results->num_rows;
    echo "<h3>Other members</h3><ul>";

    for($j=0; $j < $num; ++$j)
    {
        $row = $results->fetch_array(MYSQLI_ASSOC);
        if ($row['user'] == $user) continue;

        echo "<li><a data-transition='slide' href='members.php?view=".$row['user']."'>".$row['user']."</a>";
        $follow = "follow";

        //If logged in user is friends 
        $result1 = queryMysql("SELECT * FROM friends WHERE friend='$user' AND user='".$row['user']."'");
        $t1 = $result1->num_rows;
        
        //If logged in user has friends
        $result1 = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='".$row['user']."'");
        $t2 = $result1->num_rows;

        if($t1 + $t2 > 1)
        {
            echo " &harr; is a mutual friend [<a data-transition='slide' href='members.php?remove=".$row['user']."'>drop</a>]";
        }

        elseif($t1)
        {
            echo " &larr; you are following [<a data-transition='slide' href='members.php?remove=".$row['user']."'>drop</a>]";
        }
        
        elseif($t2)
        {
            $follow = "recip";
            echo " &rarr; is following you [<a data-transition='slide' href='members.php?add=".$row['user']."'>".$follow."</a>]";
        }
        else{
            echo " [<a data-transition='slide' href='members.php?add=".$row['user']."'>".$follow."</a>]";
        }
        
    }
    

?>