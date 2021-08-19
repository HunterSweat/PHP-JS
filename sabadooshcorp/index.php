<?php

    session_start();
    require_once './header.php';

    echo "<div class='center'>Welcome to SabadooshCORP, ";

    echo $loggedIn == TRUE ? (" $user, you are logged in") : (" please sign up or log in");
        
    echo <<<_END

        </div><br>
        <div data-role='footer'>
            <h4>Trade mark SabadooshCORP&reg;</h4>


    _END;

?>