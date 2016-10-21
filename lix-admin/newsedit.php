<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    refreshCookies();

    $a['filename']  = 'newsedit.php';
    $a['data']      = array();

    $db     = Database::getDB()->getCon();
    $db2    = Database::getDB();
    $err    = 0;
    $neu    = 0;
    $neuPl  = 0;

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      if (isset($_POST['formactionchange'])) {

        $title      = Parser::parse($_POST['newstitel'], Parser::TYPE_NEW);
        $inhalt     = Parser::parse($_POST['newsinhalt'], Parser::TYPE_NEW);
        $tagStr     = trim($_POST['tags']);
        $cat        = $_POST['cat'];
        $catNeu     = trim($_POST['catneu']);
        $catPar     = $_POST['catPar'];
        $play       = $_POST['pl'];
        $playNeu    = trim($_POST['plneu']);
        $playNeuID  = trim($_POST['plneuid']);
        $newsID     = $_POST['newsid2'];

        if(isset($_POST['enable'])) {
          $ena = 0;

        } else {
          $ena = 1;
        }

        $oldEna = getArticleAttribute($newsID, 'enable');
        $eRet   = array(  'titel'  => $title,
                          'inhalt' => $inhalt,
                          'id'     => $newsID);

        if ('' == $title || '' == $inhalt || '' == $newsID) {
          $err = 1;

        } else if ($cat == 'error' && $catNeu == '' && $play == 'error' && $playNeu == '') {
          $err = 2;

        } else if ($play != 'error' && $playNeu != '') {
          $err = 3;

        } else if ($playNeuID == '' && $playNeu != '') {
          $err = 4;

        } else if ($cat != 'error' && $catNeu != '') {
          $err = 5;

        } else if ($catPar == 'error' && $catNeu != '') {
          $err = 6;

        } else if ($playNeu != '' && $catNeu != '') {
          $err = 7;

        } else if ($catNeu != '') {
          $cat = $catNeu;
          $neu = 1;

        } else if ($playNeu != '') {
          $cat = $playNeu;
          $neu = 1;
          $neuPl = 1;

        } else if ($cat == 'error' && $play != 'error') {
          $cat = $play;

        } else {
        }

        $tags = array();
        $tmp  = explode(',', $tagStr);

        foreach($tmp as $tag) {
          if (trim($tag) !== ''
            && !in_array( strtolower($tag),
                          array_map('strtolower', $tags))) {

            $tags[] = $db->real_escape_string($tag);
          }
        }

        $e = array();

        if (isset($_FILES['files'])) {
          $picsAnzOld = getNewsPicNumber($newsID);

          foreach ($_FILES['file']['name'] as $key => $value) {
            if ( $_FILES['file']['size'][$key] > 0
              && $_FILES['file']['size'][$key] < 5242880
              && isImage($_FILES['file']['type'][$key]) ) {

              $extension  = pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);
              $pfad       = 'images/blog/id'.$id.'date'.date('Ymd').'n'.($key + $picAnzOld).'.'.$extension;

              if (!file_exists($pfad)) {
                $thumb = (int) trim($_POST['thumb']);

                if (is_int($thumb) && '' != $thumb) {
                  if($thumb == $key + 1) {
                    $thumb = 1;
                  } else {
                    $thumb = 0;
                  }

                } else {
                  $thumb = 0;
                }

                move_uploaded_file($_FILES['file']['tmp_name'][$key], $pfad);

                $name = $_FILES['file']['name'][$key];

                $fields = array('NewsID', 'Name', 'Pfad', 'Thumb');
                $values = array('issi', array(  $newsID, $name,
                                                $pfad, $thumb));
                $res    = $db2->insert('pics');
              }

              # create thumbnail
              $pic            = array();
              $pathTemp       = pathinfo($pfad);
              $pic['pathNeu'] = $pathTemp['dirname'].'/th'.$pathTemp['filename'].'_'.$pathTemp['extension'].'.jpg';
              $pic['dim']     = getimagesize($pfad);
              $pic['dim']     = array(  'w' => $pic['dim'][0],
                                        'h' => $pic['dim'][1] );
              $pic['factor']  = max($pic['dim']['w'] / 285, $pic['dim']['h'] / 190);
              $pic['dimNeu']  = array( 'w' => round($pic['dim']['w'] / $pic['factor']),
                                        'h' => round($pic['dim']['h'] / $pic['factor']));
              $pic['t']       = getimagesize($pfad);
              $pic['t']       = $pic['t'][2];

              switch($pic['t']) {
                case "1":
                  $picOld = imagecreatefromgif($pfad);
                  break;
                case "2":
                  $picOld = imagecreatefromjpeg($pfad);
                  break;
                case "3":
                  $picOld = imagecreatefrompng($pfad);
                  break;
                default:
                  $picOld = imagecreatefromjpeg($pfad);
              }

              $picNeu = imagecreatetruecolor($pic['dimNeu']['w'],$pic['dimNeu']['h']);
              imagecopyresampled( $picNeu, $picOld,
                                  0, 0,
                                  0, 0,
                                  $pic['dimNeu']['w'], $pic['dimNeu']['h'],
                                  $pic['dim']['w'], $pic['dim']['h'] );
              imagejpeg($picNeu, $pic['pathNeu'], 100);
              imagedestroy($picNeu);
              imagedestroy($picOld);

            } else if ($_FILES['file']['size'][$key] != 0) {
              $e[] = $_FILES['file']['name'][$key];

            } else {}
          }
        }

        if ($err == 0 && empty($e)) {
          $catidalt = getNewsCatID($newsID);
          $catalt   = getNewsCat($newsID);

          if ($neu) {
            /* neue Kategorie */
            $fields = array('Cat');
            $values = array('s', array($catNeu));
            $res    = $db2->insert('newscat');

            $fields = array('MAX(ID) as idn');
            $res    = $db2->select('newscat', $fields);

            if (count($res) > 0) {
              $cat = $res[0]['idn'];
            }

            $catID = 1;

            if ($neuPl) {
              $fields = array('ytID', 'CatID');
              $values = array('si', array( $playNeuID, $catID));
              $res    = $db2->insert('playlist');
            }

          } else {
            $cat = getCatID($cat);

            if ($catalt != $cat) {
              $fields = array('MAX(CatID) AS new');
              $conds  = array('Cat = ?', 'i', array($cat));
              $res    = $db2->select('newscatcross', $fields, $conds);

              if (count($res) > 0) {
                $catID = $res[0]['new'];

              } else {
                # category not found error
              }

              $catID = $catID + 1;

            } else {
              $catID = $catidalt;
            }
          }

          # update news entry
          $sql = "UPDATE
                    news
                  SET
                    Titel = ?,
                    Inhalt = ?,
                    enable = ?
                  WHERE
                    ID = ?";

          if (!$stmt = $db->prepare($sql)) {
            return $db->error;
          }

          $stmt->bind_param('ssii', $title, $inhalt, $ena, $newsID);

          if (!$stmt->execute()) {
            return $stmt->error;
          }

          $stmt->close();

          # update news x cat
          $sql = "UPDATE
                    newscatcross
                  SET
                    Cat = ?,
                    CatID = ?
                  WHERE
                    NewsID = ?";

          if (!$stmt = $db->prepare($sql)) {
            return $db->error;
          }

          $stmt->bind_param('iii', $cat, $catID, $newsID);

          if (!$stmt->execute()) {
            return $stmt->error;
          }

          $stmt->close();

          # delete old tags
          $db2->delete( 'tags',
                        array('news_id = ?', 'i', array($newsID)) );

          # insert new tags
          if (!empty($tags)) {
            $pre    = "(".$newsID.", '";
            $post   = "')";
            $glue   = $post.", ".$pre;
            $tagSql = $pre . implode($glue, $tags) . $post;

            $sql    = " INSERT INTO tags
                          (`news_id`, `tag`)
                        VALUES ".$tagSql."";

            if (!$stmt = $db->prepare($sql)) {
              return $db->error;
            }

            if (!$stmt->execute()) {
              return $stmt->error;
            }

            $stmt->close();
          }

          # update thumbnail
          if (isset($_POST['thumbOld'])) {
            $pidF = trim($_POST['thumbOld']);

            $fields = array('ID',);
            $conds  = array('NewsID = ? AND Thumb = 1', 'i', array($newsID));
            $res    = $db2->select('pics', $fields, $conds);

            if (count($res) > 0) {
              $th = $res[0]['ID'];

            } else {
              # no old thumbnail found error
              $th = null;
            }

            if ($th != $pidF) {
              $sql = 'UPDATE
                        pics
                      SET
                        Thumb = 1
                      WHERE
                        ID = ?';

              if (!$stmt = $db->prepare($sql)) {
                return $db->error;
              }

              $stmt->bind_param('i', $pidF);

              if (!$stmt->execute()) {
                return $stmt->error;
              }

              $stmt->close();

              $sql = 'UPDATE
                        pics
                      SET
                        Thumb = 0
                      WHERE
                        ID = ?';

              if (!$stmt = $db->prepare($sql)) {
                return $db->error;
              }

              $stmt->bind_param('i', $th);

              if (!$stmt->execute()) {
                return $stmt->error;
              }

              $stmt->close();
            }
          }

          # delete pictures
          if (!empty($_POST['del'])) {
            $del = $_POST['del'];

            foreach($del as $pf) {
              $fields = array('Pfad');
              $conds  = array('ID = ?', 'i', array($pf));
              $res    = $db2->select('pics', $fields, $conds);

              if (count($res) > 0) {
                $path = $res[0]['Pfad'];

              } else {
                # picture not found error
              }

              $path2 = str_replace('.', '_', $path).'.jpg';

              if (file_exists($path)) {
                unlink($path);
              }

              if (file_exists($path2)) {
                unlink($path2);
              }

              $db2->delete( 'pics',
                            array('ID = ?', 'i', array($pf)) );
            }
          }

          return showInfo(I18n::t('admin.article.edit.success'), 'newsedit');

        } else {
          $a['data']['err'] = $eRet;
          $a['data']['err']['type'] = analyseErrNewsEdit($err);
        }

      } else if (isset($_POST['formactionchoose'])) {
        $id = trim($_POST['newsid']);

        # get article for edit
        $fields = array(  'news.Titel', 'news.Inhalt', 'news.enable',
                          'newscat.Cat', 'newscat.ID' );
        $join   = 'LEFT JOIN newscatcross ON news.ID = newscatcross.NewsID
                  LEFT JOIN newscat ON newscat.ID = newscatcross.Cat';
        $cond   = array('news.ID = ?', 'i', array($id));
        $res    = $db2->select('news', $fields, $cond, null, null, $join);

        if (count($res) == 0) {
          return showInfo(I18n::t('admin.article.edit.not_found'), 'newsedit');

        } else {
          $newsedit = array(
            'newsidbea'   => $id,
            'newsinhalt'  => Parser::parse( $res[0]['Inhalt'],
                                            Parser::TYPE_EDIT),
            'newstitel'   => Parser::parse( $res[0]['Titel'],
                                            Parser::TYPE_EDIT),
            'newsena'     => $res[0]['enable'],
            'newstags'    => getNewsTags($id, true),
            'newscat'     => $res[0]['Cat'],
            'isPlaylist'  => isCatPlaylist($res[0]['ID']));

          $a['data']['newsedit'] = $newsedit;
        }

        $fields   = array('Pfad', 'Thumb', 'ID');
        $conds    = array('NewsID = ?', 'i', array($id));
        $options  = 'ORDER BY ID';
        $res      = $db2->select('pics', $fields, $conds, $options);

        $a['data']['Pfad'] = array();

        foreach ($res as $pic) {
          $a['data']['pfad'][] = array( 'pfad'  => $pic['Pfad'],
                                        'thumb' => $pic['Thumb'],
                                        'id'    => $pic['ID']);
        }
      }
    }

    $fields   = array('ID', 'Titel',
                      "DATE_FORMAT(Datum, '".DATE_STYLE."') AS date_formatted");
    $options  = 'ORDER BY Datum DESC';
    $res      = $db2->select('news', $fields, null, $options);
    $news     = array();

    foreach ($res as $article) {
      $news[$article['ID']] = array(
                                'newsid'    => $article['ID'],
                                'newsdatum' => $article['date_formatted'],
                                'newstitel' => $article['Titel']);
    }

    $a['data']['news']        = $news;
    $a['data']['pars']        = getTopCats();
    $a['data']['cats']        = getSubCats();
    $a['data']['cats'][]      = 'Blog';
    $a['data']['pls']         = getPlaylists();
    $a['data']['admin_news']  = true;

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }
?>