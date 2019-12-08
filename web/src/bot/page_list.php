<?php
require_once('../includes/config.php');

if($_GET['backdoor'] !== $bot_backdoor_key)
{
    exit();
}

$db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);
$pages = $db->query("SELECT id, user_id FROM pages WHERE id != 1 AND processed = 0 ORDER BY created ASC");

$rows = array();
while ($page_row = $pages->fetch_assoc()) {
    $rows[] = $page_row;
}

echo(json_encode($rows));

$db->close();
