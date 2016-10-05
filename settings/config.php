<?php
$noGA = array('admin', 'admindown', 'admincat', 'admincmtenable',
              'login', 'logout',
              'newsedit', 'newsnew', 'newsdel', 'newsoverview',
              'snippetnew', 'snippetedit', 'snippetdelete',
              'stats', 'userdata');

 // Dateien
 $file = array(
  'blog'            => array("news.php",            "Blog"),
  'downloads'       => array("downloads.php",       "Downloadbereich"),
  'about'           => array("about.php",           "Über und Feedback"),
  'impressum'       => array("impressum.php",       "Impressum"),
  'login'           => array("login.php",           "Login"),
  'logout'          => array("logout.php",          "Logout"),
  'admin'           => array("admin.php",           "AdminCP"),
  'aboutAuthor'     => array("aboutMod.php",        "Über den Autor"),
  'newsnew'         => array("newsnew.php",         "AdminCP - Neue News verfassen"),
  'newsedit'        => array("newsedit.php",         "AdminCP - News überarbeiten"),
  'newsdel'         => array("newsdel.php",         "AdminCP - News löschen"),
  'newsoverview'    => array("newsoverview.php",    "AdminCP - Newsübersicht"),
  'stats'           => array("stats.php",           "AdminCP - Statistiken"),
  'admindown'       => array("admindown.php",       "AdminCP - Download hinzufügen"),
  'admindownbea'    => array("admindownbea.php",    "AdminCP - Download bearbeiten"),
  'admincat'        => array("admincat.php",        "AdminCP - Kategorien"),
  'admincmtenable'  => array("admincmtenable.php",  "AdminCP - Kommentare"),
  'snippetnew'      => array("snippetnew.php",      "AdminCP - Snippet erstellen"),
  'snippetedit'     => array("snippetedit.php",     "AdminCP - Snippet bearbeiten"),
  'snippetdelete'   => array("snippetdel.php",      "AdminCP - Snippet löschen"),
  'snippetoverview' => array("snippetoverview.php", "AdminCP - Snippetübersicht"),
  'userdata'        => array("userdata.php",        "Deine Daten bearbeiten"),
  'error'           => array("error.php",           "Fehler!"),
  'search'          => array("search.php",          "Suchergebnisse"));

 // Konstanten

 define('DATE_STYLE', '%d.%m.%Y');
 define('INVALID_FORM', 'Benutzen Sie nur Formulare von dieser Homepage.');
 define('EMPTY_FORM', 'Bitte füllen Sie das Formular vollständig aus.');
 define('NOT_LOGGED_IN', 'Sie müssen eingeloggt sein um diese Funktion nutzen zu können.');

?>