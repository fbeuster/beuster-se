<?php
 echo '<pre>';
 echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.http_build_query ($_GET);
 echo '</pre>';
?>