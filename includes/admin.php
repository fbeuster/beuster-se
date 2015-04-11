<?php
    $a = array();
    if (getUserID() and hasUserRights('admin')) {
        refreshCookies();
        $a['filename'] = 'admin.php';
        $a['data'] = array();
        return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
    } else if(getUserID()){
        return 'Sie haben hier keine Zugriffsrechte.';
    } else {
        $a['filename'] = 'login.php';
        $a['data'] = array();
        return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
    }
?>