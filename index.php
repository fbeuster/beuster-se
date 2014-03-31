<?php
    ob_start();
    include('settings/core.php');
    include('user/local.php');
 
    global $sysAdrr;
 
    $sev     = $_SERVER['SERVER_NAME'];
    $sysAdrr = preg_replace('#(.+?)\.(.+?)\.(.+)#', '$2.$3', $sev);
    $agent   = $_SERVER['HTTP_USER_AGENT'];
    $ieOld   = strpos($agent, 'MSIE 5.5') || strpos($agent, 'MSIE 6.0') || strpos($agent, 'MSIE 7.0') || strpos($agent, 'MSIE 8.0');
    $ie9     = strpos($agent, 'MSIE 9.0');

    if($sev == $devServer) {
        $local = true;
    } else {
        $local = false;
    }
    if($local) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    } else {
        error_reporting(NULL);
    }
 
    $ret = 1337; // speichert den rückgabewert von include, Standardwert 1337
    // Laden Einstellungen
    include('settings/config.php');
    $mob = isMobile();
    $mob = false;
    include('settings/functions.php');
    include('settings/externals.php');
    include('settings/generators.php');
    include('settings/modules.php');
    include('settings/parser.php');

    // load classes
    include('classes/database.php');
    include('classes/user.php');
    include('classes/comment.php');
 
    $db = Database::getDB()->getCon();
    if($db->connect_errno){
        $ret = 'Konnte keine Verbindung zu Datenbank aufbauen, MySQL meldete: '.mysqli_connect_error();
    } else if(is_string($error = getUserID())){
        $ret = $error;
    } else {
        // Laden der Include-Datei
        if(isset($_GET['p'])) {
            if(isset($file[$_GET['p']][0])) {
                if(file_exists('includes/'.$file[$_GET['p']][0])) {
                    $ret = include 'includes/'.$file[$_GET['p']][0];
                } else {
                    $ret = "Include-Datei konnte nicht geladen werden: 'includes/".$file[$_GET['p']][0]."'";
                }
            } else {
                $ret = include 'includes/'.$file['blog'][0];
            }
        } else {
            $ret = include 'includes/'.$file['blog'][0];
        }
    } 
 
    setcookie('choco-cookie', 'i-love-it', strtotime("+1 day"));

    $pageType = getPageType($ret['data']);
    $currPage = getPage();

    if(!isset($beTheme) || $beTheme == '') {
        $beTheme = 'default';
    }
    $beThemeI = 'theme/'.$beTheme.'/';
    
    // Laden HTML-Kopf
    include($beThemeI.'htmlheader.php');
    if($analyse && $local) include('settings/analyse.php');
    include($beThemeI.'htmlwarning.php');
 
    // Laden der Template-Datei
    if (is_array($ret) && isset($ret['filename'], $ret['data']) &&
        is_string($ret['filename']) && is_array($ret['data'])) {
        // Gültige Include-Datei
        if (file_exists($file = $beThemeI.$ret['filename'])) {
            $data = $ret['data'];
            include $file;
        } else {
            $data['msg'] = 'Templatedatei "'.$file.'" ist nicht vorhanden.';
            include $beThemeI.'error.php';
        }
    } else if (is_string($ret)) {
        // Fehlermeldung
        $data['msg'] = $ret;
        include $beThemeI.'error.php';
    } else if (1 == $ret) {
        // return wurde vergessen
        $data['msg'] = 'In der Include-Datei wurde die return Anweisung vergessen.';
        include $beThemeI.'error.php';
    } else {
        // ein Ungültiger Return wert
        $data['msg'] = 'Die Include-Datei hat einen ungültigen Wert zurückgeliefert.';
        include $beThemeI.'error.php';
    }
    include($beThemeI.'htmlaside.php');
    // Laden HTML-Fuss
    include($beThemeI.'htmlfooter.php');
    ob_end_flush();
?>