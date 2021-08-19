<?php

require_once 'header.php';

if(isset($_SESSION['user']))
{
    destroySession();
    echo "<br><div class='center'>You have been Logged Out.
          <a data-transition='slide' href='index.php'>Click here</a> 
           to refresh the screen</div>";
}

else
{
    echo "<div class='center'>Cannot log you out because you are not logged in</div>";
}


?>