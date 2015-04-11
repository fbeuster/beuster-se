<?php
 echo '<pre>';
 //echo phpversion();
 echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.http_build_query ($_GET).'<br>';
 //print_r(explode('/', substr($_SERVER['REQUEST_URI'], 1, strlen($_SERVER['REQUEST_URI']) - 1)));
 //print_r($_SERVER);
 //print_r($a['data']);
 //echo date("Y-m-d H:i:s", time());
 echo '</pre>';
?>