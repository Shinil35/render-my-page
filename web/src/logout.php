<?php

require_once('includes/login_manager.php');

if(!user_is_logged())
{
    header("Location: login.php");
    exit();
}

// Destroys current session data

logout();

header("Location: login.php?logout=1");