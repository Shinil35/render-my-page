<?php
require_once('config.php');

function gen_uuid4() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function insert_page($uid, $name, $content)
{
    $db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);

    if($reg_stmt = $db->prepare("INSERT INTO pages (user_id, name, content) VALUES (?, ?, ?)"))
    {
        $reg_stmt->bind_param("iss", $uid, $name, $content);
        $reg_stmt->execute();

        if($reg_stmt->affected_rows != 1)
        {
            $db->close();
            return false;
        }

        $db->close();

        return true;
    }

    $db->close();
    return false;
}

function get_page($id)
{
    $db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);

    if($page_stmt = $db->prepare("SELECT * FROM pages WHERE id = ?"))
    {
        $page_stmt->bind_param("i", $id);
        $page_stmt->execute();

        $page_result = $page_stmt->get_result();

        if($page_result->num_rows === 0)
        {
            return null;
        }

        $page_row = $page_result->fetch_assoc();

        $db->close();
        return $page_row;
    }

    $db->close();
    return null;
}