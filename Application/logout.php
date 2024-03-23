<?php
    # Connect to DB with Config.php
    require 'Config.php';
    include("database.class.php");	//Include MySQL database class
    include("mysql.sessions.php");	//Include PHP MySQL sessions
    $session = new Session();	//Start a new PHP MySQL session
    
    // Unset the SESSION Variable && Redirect to to Login Page
    session_unset();
    // Destroy Entire Session
    session_destroy();

    //Redirect back to Login Page:
    header("Location: /")
?>