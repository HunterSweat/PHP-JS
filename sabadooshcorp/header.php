<?php

    session_start();

    echo <<<_INIT

        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <meta name='viewport' content='width=divice-width', initial-scale="1">
                <link rel="stylesheet" href="styles.css">
                <link rel="stylesheet" href="./prettify.css" />
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"
                        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
                        crossorigin="anonymous">
                </script>
                <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
                <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
                <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
          

    _INIT;

    require_once './functions.php';

    $userstr = "Welcome Guest";

    if (isset($_SESSION['user']) && (isset($_SESSION['ip']) == ($_SERVER['REMOTE_ADDR']))){
        $user = $_SESSION['user'];
        $loggedIn = TRUE;
        $userstr = "Logged in as $user";
    }
    else $loggedIn = FALSE;
    
    echo <<<_MAIN

            <title>SabadooshCORP: $userstr</title>
        </head>
        <body>
            <div data-role="page">
                <div data-role="header">
                    <div id="logo" class="center">SabadooshCORP</div>
                    <div class="username">$userstr</div>
                </div>
                <div data-role="content">

    _MAIN;

    if ($loggedIn){

        echo <<<_LOGGEDIN

            <div class="center">
                <a data-role="button" data-inline="true" data-icon="home" data-transition="slide" href="index.php">Home</a>
                <a data-role="button" data-inline="true" data-icon="user" data-transition="slide" href="members.php">Members</a>
                <a data-role="button" data-inline="true" data-icon="heart" data-transition="slide" href="friends.php">Friends</a>
                <a data-role="button" data-inline="true" data-icon="mail" data-transition="slide" href="messages.php">Messages</a>
                <a data-role="button" data-inline="true" data-icon="edit" data-transition="slide" href="profile.php">Edit Profile</a>
                <a data-role="button" data-inline="true" data-icon="cloud" data-transition="slide" href="codebase.php">Codebase</a>
                <a data-role="button" data-inline="true" data-icon="carat-r" data-transition="slide" href="logout.php">Logout</a>   
            </div>
    

        _LOGGEDIN;

    }

    else {

        echo <<<_GUEST
            <div class="center">
                <a data-role="button" data-inline="true" data-icon="home" data-transition="slide" href="index.php">Home</a>
                <a data-role='button' data-inline='true' data-icon='plus' data-transition='slide' href='signup.php'>Sign Up</a>
                <a data-role="button" data-inline="true" data-icon="check" data-transition="slide" href="login.php">Log In</a>
            </div>
            <p class="info">(You must be logged in to use this app)</p>
                  
            

    _GUEST;
    }

?>