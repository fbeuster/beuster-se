<?php
 if (!getUserID($db)) {return NOT_LOGGED_IN;}
 $ret = array();
 $ret['data'] = array();
 setcookie('UserID', '', strtotime('-1 day'));
 setcookie('Password', '', strtotime('-1 day'));
 unset($_COOKIE['UserID']);
 unset($_COOKIE['Password']);
 return showInfo('Sie sind nun ausgeloggt.', 'news');
?>