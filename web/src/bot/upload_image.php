<?php
require_once('../includes/config.php');

if($_GET['backdoor'] !== $bot_backdoor_key)
{
    exit();
}

$page_id = $_POST['page_id'];
$image = $_POST['image'];
$ok = false;

$db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);
if($img_stmt = $db->prepare("UPDATE pages SET processed=1, image=? WHERE id=?"))
{
    $img_stmt->bind_param("si", $image, $page_id);
    $img_stmt->execute();

    if($img_stmt->affected_rows === 1)
    {
        $ok = true;
    }
}
$db->close();

echo(json_encode($ok));