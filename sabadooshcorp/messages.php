<?php

    require_once 'header.php';

    if (!$loggedIn) die("</div></body></html>");

    if (isset($_GET['view'])) $viewedAccount = sanatizeString($_GET['view']);
    else $viewedAccount = $user;

    if (isset($_POST['text']) && !empty($_POST['text']))
    {
        $textOfMessage = sanatizeString($_POST['text']);
        $messageType = substr(sanatizeString($_POST['pm']), 0, 1);
        $timeOfMessage = time();  
        queryMysql("INSERT INTO messages VALUES(NULL, '$user', '$viewedAccount', '$messageType', '$timeOfMessage', '$textOfMessage')");
    }


    if (!empty($viewedAccount))
    {
        if ($viewedAccount == $user) $name1 = $name2 = "Your";
        else
        {
            $name1 = "<a href='members.php?view=$viewedAccount'>$viewedAccount</a>";
            $name2 = "$viewedAccount's";
        }
    }

    echo "<h3>$name1 Messages</h3>";
    showProfile($viewedAccount);

    if($name2 == "$viewedAccount's")
    {
        echo <<<_END

            <form method='post' action='messages.php?view=$viewedAccount'>
                <fieldset data-role="controlgroup" data-type="horizontal">
                    <legend>Type here to leave your message</legend>

                    <input type="radio" name="pm" id="public" value="0" checked="checked">
                    <label for="public">Public</label>    

                    <input type="radio" name="pm" id="private" value="1">
                    <label for="private">Private</label>
                </fieldset>

                <textarea name='text'></textarea>
                <input data-transition='slide' type='submit' value='Post Message'>
            </form><br>

        _END;
    }

    date_default_timezone_set('America/Chicago');
    
    if (isset($_GET['erase']))
    {
        $erase = sanatizeString($_GET['erase']);
        queryMysql("DELETE FROM messages WHERE id=$erase and recip='$user'");
    }

    $databaseQuery = "SELECT * FROM messages WHERE recip='$viewedAccount' ORDER BY time DESC";
    $resultFromQuery = queryMysql($databaseQuery);
    $numberOfRowsInTable = $resultFromQuery->num_rows;

    for ($j=0; $j<$numberOfRowsInTable; ++$j)
    {
        $rowOfTable = $resultFromQuery->fetch_array(MYSQLI_ASSOC);

        if ($rowOfTable['pm'] == 0 || $rowOfTable['auth'] == $user || $rowOfTable['recip'] == $user)
        {
            echo date('M j \'y g:ia:', $rowOfTable['time']);
            echo "<a href='messages.php?view=".$rowOfTable['auth']."'>".$rowOfTable['auth']."</a>";

            if ($rowOfTable['pm'] == 0) 
        {
            echo " wrote: &quot;" . $rowOfTable['message']. "&quot;";
        }

        else
        {
            echo " whisper: <span class='whisper'>&quot;". $rowOfTable['message']. "&quot;</span>";
        }

        if ($rowOfTable['recip'] == $user)
        {
            echo "[<a href='messages.php?view=$viewedAccount"."&erase=".$rowOfTable['id']."'>erase</a>]";
        }
        echo "<br>";
        }  
    }

    if(!$numberOfRowsInTable) echo "<br><span class='info'>There are no messages to view</span><br><br>";

    echo "<br><a data-role='button' href='messages.php?view=$viewedAccount'>Refresh Messages</a>";


?>

        </div><br>
    </body>
</html>