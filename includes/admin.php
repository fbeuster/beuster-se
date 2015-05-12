<?php
    $a = array();
    $user = User::newFromCookie();
    if ($user && $user->isAdmin()) {
        refreshCookies();
        $a['filename'] = 'admin.php';
        $a['data'] = array();
        return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
    } else if($user){
        return showInfo('Sie haben hier keine Zugriffsrechte.', 'blog');
    } else {
        $a['filename'] = 'login.php';
        $a['data'] = array();
        return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
    }
?>