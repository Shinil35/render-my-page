<?php
require_once('config.php');

// Security session settings
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);

session_start();

function user_is_logged()
{
    $logged = (isset($_SESSION['logged']) && $_SESSION['logged'] == true);

	return $logged;
}

function username_exists($username)
{
    $db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);

    if($user_stmt = $db->prepare("SELECT id FROM users WHERE username = ?"))
    {
        $user_stmt->bind_param("s", $username);
        $user_stmt->execute();

        $user_result = $user_stmt->get_result();

        if($user_result->num_rows === 0 || $user_result->num_rows > 1)
        {
            $db->close();

            return false;
        }

        $db->close();

        return true;
    }

    $db->close();
    return null;
}

function register($username, $password)
{
    $db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);

    if($reg_stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)"))
    {
        $reg_stmt->bind_param("ss", $username, password_hash($password, PASSWORD_DEFAULT));
        $reg_stmt->execute();

        if($reg_stmt->affected_rows != 1)
        {
            $db->close();
            return false;
        }

        $db->close();

        return login($username, $password);
    }

    $db->close();
    return false;
}

function validate_username($username)
{
    return ctype_alnum($username) && strlen($username) <= 32;
}

function login($username, $password)
{
    // Check username existance
    $db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);

    if($login_stmt = $db->prepare("SELECT * FROM users WHERE username = ?")) {
        $login_stmt->bind_param("s", $username);
        $login_stmt->execute();

        $login_result = $login_stmt->get_result();

        // Check if we have 1 and only 1 row
        if ($login_result->num_rows === 0 || $login_result->num_rows > 1)
        {
	        $db->close();
            return false;
        }

        // Check user password
        $user_row = $login_result->fetch_assoc();

        $user_password_hash = $user_row['password'];

        if (!password_verify($password, $user_password_hash))
        {
            $db->close();

            return false;
        }

        // Login successful: setting up user session
        $_SESSION['logged'] = true;
        $_SESSION['userID'] = $user_row['id'];

        $db->close();
        return true;
    }

    $db->close();
    return false;
}

function logout()
{
    session_destroy();
}

function get_user_data()
{
    if(!user_is_logged())
        return null;

    $db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);

    if($user_stmt = $db->prepare("SELECT * FROM users WHERE id = ?"))
    {
        $user_stmt->bind_param("i", $_SESSION['userID']);
        $user_stmt->execute();

        $user_result = $user_stmt->get_result();

        if($user_result->num_rows === 0 || $user_result->num_rows > 1)
        {
            $db->close();
            logout(); // Corrupted data in the session, user doesn't exist.
            return null;
        }

        $user_row = $user_result->fetch_assoc();

        $db->close();

        return $user_row;
    }

    $db->close();
    return null;
}

function get_name_by_id($user_id)
{
    $db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);

    if($user_stmt = $db->prepare("SELECT username FROM users WHERE id = ?"))
    {
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();

        $user_result = $user_stmt->get_result();

        if($user_result->num_rows === 0 || $user_result->num_rows > 1)
        {
            $db->close();

            return null;
        }

        $user_row = $user_result->fetch_assoc();

        $db->close();

        return $user_row["username"];
    }

    $db->close();
    return null;
}

// Session ID must be regenerated when
//  - User logged in
//  - User logged out
//  - Certain period has passed
// my_session_regenerate_id();
