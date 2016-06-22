<?php
  $a = array();
  $user = User::newFromCookie();
  if ($user && $user->isAdmin()) {
    refreshCookies();
    $a['filename'] = 'snippetnew.php';
    $a['data'] = array();
    $err = 0;
    $db = Database::getDB();

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      $content = Parser::parse($_POST['content'], Parser::TYPE_NEW);
      $name = trim($_POST['name']);

      $eRet = array(  'content' => $content,
                      'name'    => $name);
      if('' == $name || '' == $content) {
        # empty name or content
        $err = 1;

      } else if(strlen($name) > 20) {
        $err = 2;

      } else if(Snippet::exists($name)) {
        # already exists
        $err = 3;

      } else {
        $now    = date("Y-m-d H:i:s", time());
        $fields = array('name', 'content_de', 'content_en',
                        'created', 'edited');
        $values = array('sssss', array( $name, $content, $content,
                                        $now, $now));
        $res    = $db->insert('snippets', $fields, $values);
      }

      if($err != 0) {
          $eRet['t'] = $err;
          $a['data']['fe'] = $eRet;
      } else {
          return showInfo('Das Snippet wurde hinzugefügt. <br /><a href="/admin" class="back">Zurück zur Administration</a>', 'admin');
      }
    }

    return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
  } else if($user){
    return showInfo('Sie haben hier keine Zugriffsrechte.', 'blog');
  } else {
    return showInfo('Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>', 'login');
  }
?>