<?php
$noGA = array('admin', 'admindown', 'admincat', 'admincmtenable',
              'login', 'logout',
              'newsbea', 'newsneu', 'newsdel', 'newsoverview',
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
  'newsneu'         => array("newsneu.php",         "AdminCP - Neue News verfassen"),
  'newsbea'         => array("newsbea.php",         "AdminCP - News überarbeiten"),
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
  'userdata'        => array("userdata.php",        "Deine Daten bearbeiten"),
  'error'           => array("error.php",           "Fehler!"),
  'search'          => array("search.php",          "Suchergebnisse"));

 // Variablen
 $bb = array('[b]...[/b]', '[i]...[/i]', '[u]...[/u]', '[/p][p] (NUR so)', '[url=...]...[/url]', '[quote]...[/quote]', ':) :D :( ;)');
 $bbCmt = array('[b]...[/b]', '[i]...[/i]', '[u]...[/u]', '[url=...]...[/url]', '[quote]...[/quote]', ':) :D :( ;)');

 // Konstanten

 define('LIMIT_NUM', 5);
 define('DATE_STYLE', '%d.%m.%Y');
 define('DATE_STYLEB', '%d.%m.%Y - %H:%i');

 define('INVALID_FORM', 'Benutzen Sie nur Formulare von dieser Homepage.');
 define('EMPTY_FORM', 'Bitte füllen Sie das Formular vollständig aus.');
 define('NOT_LOGGED_IN', 'Sie müssen eingeloggt sein um diese Funktion nutzen zu können.');
 define('NO_NEWS_CHOOSEN', 'Sie haben keine News zum Bearbeiten gewählt.');
 define('BADCAT', 'Sie haben keine Newskategorie gewählt.');

/* Fehlerliste
 *
 *  #NC1   news.php    Commentsinsert  $db->prepare()
 *  #NC2   news.php    Commentsinsert  $stmt->execute()
 *  #NC2   news.php    Thumbnail  $stmt->execute()
 *  #S1   search.php    Commentsinsert  $db->prepare()
 *  #S2   search.php    Commentsinsert  $stmt->execute()
 *
 */
?>