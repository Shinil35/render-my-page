<?php
require_once('config.php');

function get_real_user_ip()
{
    if($GLOBALS['cloudflare_enabled'] && isset($_SERVER["CF-Connecting-IP"]))
        return $_SERVER["CF-Connecting-IP"];
    else
        return $_SERVER["REMOTE_ADDR"];
}

function get_flag_for_user($username) {
    // Genera una flag nel formato seguente basandosi sull'username:
    // flag{n3v3r_t4k3_scr33n_ph0t0s_4g4in-[0-9a-f]{6}}
    
    $start = "flag{";
    $mid = "n3v3r_t4k3_scr33n_ph0t0s_4g4in-";
    $end = "}";
    
    $salt = "r4nd0m_1s_g00d_";
    $rand = substr(hash('sha256', $salt.$username), 0, 6);

    $flag = $start.$mid.$rand.$end;

    // Salva la flag sul DB, per tracciare eventuali collisioni
    $db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);
    if($reg_stmt = $db->prepare("INSERT INTO flags (username, flag, ip) VALUES (?, ?, ?)"))
    {
        $reg_stmt->bind_param("sss", $username, $flag, get_real_user_ip());
        $reg_stmt->execute();
    }
    $db->close();

    return $flag;
}