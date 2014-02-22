<?php
 $a = array();
 $a['filename'] = 'search.tpl';
 $a['data'] = array();

 $searchStr = '';
 if(isset($_POST['s'])){$searchStr = trim($_POST['s']);}
 if(isset($_GET['s'])){$searchStr = trim($_GET['s']);}
 
 if('' == $searchStr){
  $a ['data']['error'] = 'Du hast keinen Suchbegriff eingegeben.';
  $a['data']['str'] = $searchStr;
 } else if(strlen($searchStr) < 3 && '' !== $searchStr){
  $a ['data']['error'] = 'Der Suchbegriff muss mindestens 3 Zeichen lang sein.';
  $a['data']['str'] = $searchStr;
 } else if('' !== $searchStr && strlen($searchStr) > 2){
  $searchStr = $db->real_escape_string(stripslashes(htmlspecialchars($searchStr)));
  $searchStrLow = mb_strtolower($searchStr, 'UTF-8');
  $sql = 'SELECT
            news.ID,
            news.Titel,
            news.Inhalt
          FROM
            news
          JOIN
            newscatcross ON
            news.ID = newscatcross.NewsID
          JOIN
            newscat ON
            newscatcross.Cat = newscat.ID
          WHERE
            NOT newscat.Typ = 3';
  if(!$stmt = $db->prepare($sql)) {return showInfo('Fehler #S1, bitte Admin kontaktieren', 'blog');}
  if(!$stmt->execute()){return showInfo('Fehler #S2, bitte Admin kontaktieren', 'blog');}
  $stmt->bind_result($id2, $titel2, $inhalt2);
  $a['data']['res'] = array();
  while($stmt->fetch())
  {
   if(strpos(mb_strtolower($titel2, 'UTF-8'), $searchStrLow) !== false){
    $hit = substr_count(mb_strtolower($titel2, 'UTF-8'), $searchStrLow);
    if(strpos(mb_strtolower($inhalt2, 'UTF-8'), $searchStrLow) !== false){
     $hit += substr_count(mb_strtolower($inhalt2, 'UTF-8'), $searchStrLow);
    }
    $res = array('id' => $id2, 'hit' => $hit);
    $a['data']['res'][$id2] = $hit;
   } else if(strpos(mb_strtolower($inhalt2, 'UTF-8'), $searchStrLow) !== false){
    $hit = substr_count(mb_strtolower($inhalt2, 'UTF-8'), $searchStrLow);
    $res = array('id' => $id2, 'hit' => $hit);
    $a['data']['res'][$id2] = $hit;
   }
  }
  arsort($a['data']['res']);
  $stmt->close();
  
  $results = array();
  foreach($a['data']['res'] as $key => $val){
   $sql = 'SELECT
                news.ID,
                news.Titel,
                news.Inhalt,
                DATE_FORMAT(news.Datum, "'.DATE_STYLE.'") AS Changedatum,
                COUNT(kommentare.ID) AS cmtAnz,
                news.Autor,
                newscatcross.Cat
           FROM
                news
           LEFT JOIN
                kommentare ON
                kommentare.NewsID = news.ID
           JOIN
                newscatcross ON
                news.ID = newscatcross.NewsID
           WHERE
                news.ID = ?';
   if(!$stmt = $db->prepare($sql)) {return $db->error;}
   $stmt->bind_param('i', $key);
   if(!$stmt->execute()){return $db->error;}
   $stmt->bind_result($id, $titel, $inhalt, $datum, $cmtAnz, $autor, $cat);
   if($stmt->fetch()){
    $results[] = array(
                'id'    => $id,
                'tit'   => $titel,
                'inh'   => changetext(searchmark($inhalt, $searchStr, true), 'vorschau', $id),
                'cat'   => $cat,
                'dat'   => $datum,
                'cmt'   => $cmtAnz,
                'aut'   => $autor,
                'hit'   => $val);
   }
   $stmt->close();
  }
  foreach($results as $k => $v) {
   $results[$k]['cat'] = getCatName($db, $v['cat']);
  }
  $a['data']['result'] = $results;
  if(isset($a['data']['result'])){
   $anzRes = count($a['data']['result']);
   $pageNbr = ceil($anzRes / 5);
   $a['data']['pageNbr'] = $pageNbr;
   $a['data']['anzRes'] = $anzRes;
   if(isset($_GET['page'])){
    if(($page = $_GET['page']) > $pageNbr) {
     $page = $pagenbr;
    }
   } else {
    $page = 1;
   }
   $a['data']['result'] = array_slice($a['data']['result'], $page*5-5, 5);
   $a['data']['page'] = $page;
  }
  $a['data']['str'] = $searchStrLow;
 }
 return $a;
?>