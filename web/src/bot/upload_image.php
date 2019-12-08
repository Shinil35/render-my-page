<?php
require_once('../includes/config.php');

if($_GET['backdoor'] !== $bot_backdoor_key)
{
    exit();
}

