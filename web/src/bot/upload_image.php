<?php
require_once('../includes/config.php');

if($_GET['backdoor'] !== $bot_backdoor_key)
{
    exit();
}

$page_id = $_POST['page_id'];
$image = $_POST['image'];

$db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);
if($img_stmt = $db->prepare("UPDATE pages SET processed=1, image=? WHERE id=? AND user_id != 1"))
{
    $img_stmt->bind_param("si", $image, $page_id);
    $img_stmt->execute();
}
$db->close();

echo(json_encode(true));