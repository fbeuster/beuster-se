<?php
  $a = array();
  $a['filename'] = 'search.php';
  $a['data'] = array();
  $db = Database::getDB()->getCon();

  $searchStr = '';
  if(isset($_POST['s'])){$searchStr = trim($_POST['s']);}
  if(isset($_GET['s'])){$searchStr = trim($_GET['s']);}

  if('' == $searchStr){
    $a['data']['error'] = 'Du hast keinen Suchbegriff eingegeben.';
    $a['data']['str']   = $searchStr;
  } else if(strlen($searchStr) < 3 && '' !== $searchStr){
    $a['data']['error'] = 'Der Suchbegriff muss mindestens 3 Zeichen lang sein.';
    $a['data']['str']   = $searchStr;
  } else if('' !== $searchStr && strlen($searchStr) > 2){
    $searchStr    = $db->real_escape_string(stripslashes(htmlspecialchars($searchStr)));
    $searchStrLow = mb_strtolower($searchStr, 'UTF-8');

    $news = array();
    $sql = "SELECT
              ID,
              Titel,
              Inhalt
            FROM
              news
            WHERE
              enable = ? AND
              Datum < NOW()";
    if(!$result = $db->prepare($sql)) {$return = $db->error;}
    $ena = 1;
    $result->bind_param('i', $ena);
    if(!$result->execute()) {$return = $result->error;}
    $result->bind_result($newsid, $newstitel, $newsinhalt);
    while($result->fetch()) {
      $news[] = array('id'      => $newsid,
                      'content' => changetext($newsinhalt, 'bea'),
                      'title'   => $newstitel,
                      'score'   => 0);
    }
    $result->close();
    $maxTime = getMaxNewsUpTime();
    
    foreach($news as $key => $entry) {

      /* Title-Score */
      $scoreTitle = 0;
      $scoreTitleSim = rank($entry['title'], $searchStr, false);
      $scoreTitleApr = substr_count(mb_strtolower($entry['title'], 'UTF-8'), $searchStrLow);
      $scoreTitleExa = substr_count($entry['title'], $searchStr);
      $scoreTitle += ($scoreTitleSim - $scoreTitleApr - $scoreTitleExa) * 5;
      $scoreTitle += ($scoreTitleApr - $scoreTitleExa) * 15;
      $scoreTitle += $scoreTitleExa * 25;

      /* Content-Score */
      $scoreContent = 0;
      $scoreContentSim = rank($entry['content'], $searchStr, false);
      $scoreContentApr = substr_count(mb_strtolower($entry['content'], 'UTF-8'), $searchStrLow);
      $scoreContentExa = substr_count($entry['content'], $searchStr);
      $scoreContent += ($scoreContentSim - $scoreContentApr - $scoreContentExa) * 3;
      $scoreContent += ($scoreContentApr - $scoreContentExa) * 10;
      $scoreContent += $scoreContentExa * 15;

      /* Zeitfaktor */
      $newsTime = getNewsUpTime($entry['id']);
      $timeFactor = $newsTime / $maxTime;

      /* Comment-Score */
      $cmtScore = 0;
      $comments = array();
      $sql = "SELECT
                Inhalt
              FROM
                kommentare
              WHERE
                NewsID = ?";
      if(!$result = $db->prepare($sql)) {$return = $db->error;}
      $result->bind_param('i', $entry['id']);
      if(!$result->execute()) {$return = $result->error;}
      $result->bind_result($commentContent);
      while($result->fetch()) {
        $comments[] = array('content' => changetext($commentContent, 'bea'),
                            'score'   => 0);
      }
      $result->close();
      $scoreComment = 0;

      foreach($comments as $keyC => $entryC) {
        $scoreCommentSim = rank($entryC['content'], $searchStr, Config::getConfig()->get('search.case_sensitive'));
        $scoreCommentApr = substr_count(mb_strtolower($entryC['content'], 'UTF-8'), $searchStrLow);
        $scoreCommentExa = substr_count($entryC['content'], $searchStr);
        $scoreComment += ($scoreCommentSim - $scoreCommentApr - $scoreCommentExa) * 3;
        $scoreComment += ($scoreCommentApr - $scoreCommentExa) * 10;
        $scoreComment += $scoreCommentExa * 15;
      }

      /* Popularity-Score */
      $pop = 0;
      $cmts = getCmt($entry['id']);
      $hits = getHits($entry['id']);
      $hitCoefficient = 100 - $hits * 100 / $newsTime;
      $pop = $cmts + $scoreComment;

      /* Sum up score */
      $rank = $scoreTitle + $scoreContent;
      if($rank > 0)
        $rank = ($rank + $pop) * $hitCoefficient;
      if($rank == 0) {
        unset($news[$key]);
      } else {
        $news[$key]['score'] = $rank * $timeFactor;
      }
    }

    usort($news, 'sortCompare');

    $results = array();
    foreach($news as $key => $val){
      $results[] = new SearchResult($val['id'], $searchStr);
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