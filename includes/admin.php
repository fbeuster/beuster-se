<?php
    $a = array();
    if (getUserID($db) and hasUserRights($db, 'admin')) {
        refreshCookies();
        $a['filename'] = 'admin.tpl';
        $a['data'] = array();
        return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
    } else if(getUserID($db)){
        return 'Sie haben hier keine Zugriffsrechte.';
    } else {
        $a['filename'] = 'login.tpl';
        $a['data'] = array();
        return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
    }
?>