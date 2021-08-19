<?php

    require_once 'header.php';

    if(!$loggedIn) die("</div></body></html");

    if (isset($_GET['view'])) $view = sanatizeString($_GET['view']);
    
    else $view = $user;

    if ($view == $user)
    {
        $name1 = $name2 = "Your";
        $name3 = "You are";
    }

    else
    {
        $name1 = "<a data-transition='slide' href='member.php?view=$view>$view</a>'s";
        $name2 = "$view's";
        $name3 = "$view is";
    }

    showProfile($view);

    $followers = array();
    $following = array();
    
    $result = queryMysql("SELECT * FROM friends WHERE user='$view'");
    $num = $result->num_rows;

    for ($j=0; $j<$num; ++$j)
    {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $followers[$j] = $row['friend'];
    }

    $result = queryMysql("SELECT * FROM friends WHERE friend='$view'");
    $num = $result->num_rows;

    for ($j=0; $j<$num; ++$j)
    {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $following[$j] = $row['user'];
    }

    $mutual = array_intersect($followers, $following);
    $followers = array_diff($followers, $mutual);
    $following = array_diff($following, $mutual);
    $friends = FALSE;

    echo "<fieldset data-role='controlgroup' data-type='horizontal'>
             <a data-role='button' data-transition='slide' href='messages.php?view=$view'>View $name2 messages</a>
             <a data-role='button' data-transition='slide' href='codebase.php?view=$view'>View $name2 codebse</a>";

    echo "<br><br><br>";
    
    if(sizeof($mutual))
    {
        echo "<span class='subhead'>$name2 mutal friends</span><ul>";
        foreach($mutual as $friend)
        {
            echo "<li><a data-transition='slide' href='friends.php?view=$friend'>$friend</a></li>";
        }
        echo "</ul>";
        $friends = TRUE;
    }

    echo "<br>";
    if(sizeof($followers))
    {
        echo "<span class='subhead'>$name2 followers</span><ul>";
        foreach($followers as $friend)
        {
            echo "<li><a data-transition='slide' href='friends.php?view=$friend'>$friend</a></li>";
        }
        echo "</ul>";
        $friends = TRUE;
    }

    echo "<br>";
    if(sizeof($following))
    {
        echo "<span class='subhead'>$name3 following</span><ul>";
        foreach($following as $friend)
        {
            echo "<li><a data-transition='slide' href='friends.php?view=$friend'>$friend</a></li>";
        }
        echo "</ul>";
        $friends = TRUE;
    }
    
    if (!$friends) echo "You do not have any friends yet.<br><br>";

    

?>
</div>
</body>