<?php
	$a = array();
	$a['filename'] = 'youtube.php'; 
	$a['data'] = array();

	$feedURL = 'http://gdata.youtube.com/feeds/api/videos/JsD6uEZsIsU';
	$feedURL = 'http://gdata.youtube.com/feeds/api/videos/ddddddddddd';
	$sxml = simplexml_load_file($feedURL);
	if($sxml) $a['data']['yt'] = 1;


	return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
?>