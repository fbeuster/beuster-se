<?php

  function getPage() {
    $curPage = getCurrentPage();
    if(isset($_GET['p']) && strtolower($_GET['p']) == 'portfolio') {
      return 'portfolio';
    }
    switch($curPage) {
      case 'blog':        return 'index';
      case 'entry':       return 'single';
      case 'page':        return 'page';
      case 'search':      return 'category';
      case 'category':    return 'category';
      default:            return 'index';
    }
  }

  function getCurrentPage() {
    if(!isset($_GET['p'])) {
      return 'blog';
    } else {
      if($_GET['p'] == 'blog' && isset($_GET['n'])) {
        return 'entry';
      } else if(getCatID($_GET['p']) && isTopCat($_GET['p'])) {
        return 'topCategory';
      } else if(getCatID($_GET['p'])) {
        return 'category';
      } else if($_GET['p'] == 'search') {
        return 'search';
      } else {
        return 'page';
      }
    }
  }

  function getPageOGImage() {
    $data = Lixter::getLix()->getPage()->getContent();
    if(isset($data['th_og'])) {
      return $data['th_og'];
    } else {
      return 'http://beusterse.de/images/prev.png';
    }
  }

  function getPageUrl() {
    $curPage = getCurrentPage();
    switch($curPage) {
      case 'blog':
        return 'http://'.$_SERVER['HTTP_HOST'];
      case 'entry':
        $id = $_GET['n'];
        $article = new Article($id);
        $title = $article->getTitle();
        $cat = getCatName(getNewsCat($id));
        return 'http://'.$_SERVER['HTTP_HOST'].getLink($cat, $id, $title);
      case 'topCategory':
        return 'http://'.$_SERVER['HTTP_HOST'];
      case 'category':
        return 'http://'.$_SERVER['HTTP_HOST'];
      default:
        return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.http_build_query ($_GET);
    }
  }

  function getPageDescription() {
    $db = Database::getDB()->getCon();
    $curPage = getCurrentPage();
    switch($curPage) {
      case 'blog':
        $i = getCatID('Blog');
        break;
      case 'entry':
        $i = 0;
        break;
      case 'topCategory':
        $i = getCatID($_GET['p']);
        break;
      case 'category':
        $i = getCatID($_GET['p']);
        break;
      default:
        $i = getCatID('Blog');
        break;
    }

    if($i !== 0) {
      $sql = "SELECT
                Beschreibung
              FROM
                newscat
              WHERE
                ID = ?";
      if(!$stmt = $db->prepare($sql))
        return $db->error;

      $stmt->bind_param('i', $i);

      if(!$stmt->execute())
        return $result->error;

      $stmt->bind_result($catDescr);

      if(!$stmt->fetch())
        return 'Es wurde keine solche Kategorie gefunden. <br /><a href="/blog">Zurück zum Blog</a>';

      $stmt->close();

      return $catDescr;
    } else {
      $id = $_GET['n'];
      $sql = "SELECT
                Inhalt
              FROM
                news
              WHERE
                ID = ?";
      if(!$stmt = $db->prepare($sql))
        return $db->error;

      $stmt->bind_param('i', $id);

      if(!$stmt->execute())
        return $result->error;

      $stmt->bind_result($cont);

      if(!$stmt->fetch())
        return 'Es wurde keine solche News gefunden. <br /><a href="/blog">Zurück zum Blog</a>';

      $stmt->close();

      return Parser::parse($cont, Parser::TYPE_DESC);
    }
  }
?>