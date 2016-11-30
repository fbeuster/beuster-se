<?php
    include('functionsNews.php');
    include('functionsPage.php');
    include('functionsCategory.php');

    /*** general ***/

    function addRssItem($feedURL, $titel, $text, $date, $id, $link) {
        $xml = new DOMDocument();
        $xml->load($feedURL);

        $cha = $xml->getElementsByTagName('channel')->item(0);

        $lbd = $cha->getElementsByTagName('lastBuildDate')->item(0);
        $cha->removeChild($lbd);
        $lbd = $xml->createElement('lastBuildDate', utf8_encode($date));
        $cha->appendChild($lbd);

        $itm = $xml->createElement('item');

        if(count($cha->getElementsByTagName('item')->item(0)) == 0) {
            $cha->appendChild($itm);
        } else {
            $cha->insertBefore($itm, $cha->getElementsByTagName('item')->item(0));
        }
        if(count($cha->getElementsByTagName('item')->item(20)) != 0) {
            $cha->removeChild($cha->getElementsByTagName('item')->item(20));
        }

        $dat = $xml->createElement('title', $titel);
        $itm->appendChild($dat);
        $dat = $xml->createElement('description', $text);
        $itm->appendChild($dat);
        $dat = $xml->createElement('link', $link);
        $itm->appendChild($dat);
        $dat = $xml->createElement('pubDate', utf8_encode($date));
        $itm->appendChild($dat);
        $dat = $xml->createElement('guid', htmlentities($id));
        $itm->appendChild($dat);

        $xml->save($feedURL);
    }

    function removeRssItem($feedURL, $date, $id) {

        $xml = new DOMDocument();
        $xml->load($feedURL);

        $cha = $xml->getElementsByTagName('channel')->item(0);

        $lbd = $cha->getElementsByTagName('lastBuildDate')->item(0);
        $cha->removeChild($lbd);
        $lbd = $xml->createElement('lastBuildDate', utf8_encode($date));
        $cha->appendChild($lbd);

        $items = $cha->getElementsByTagName('item');
        foreach ($items as $item) {
            if($item->getElementsByTagName('guid')->item(0) == $id)
                continue;
        }

        /*if(count(->item(0)) == 0) {
            $cha->appendChild($itm);
        } else {
            $cha->insertBefore($itm, $cha->getElementsByTagName('item')->item(0));
        }
        if(count($cha->getElementsByTagName('item')->item(20)) != 0) {
            $cha->removeChild($cha->getElementsByTagName('item')->item(20));
        }

        $xml->save($feedURL);*/
    }

    function analyseErrNewsEdit($err) {
        $ret = '';
        switch($err) {
            case 0: return 'Ist doch alles okay?';
            case 1: return 'Titel oder Inhalt sind leer.';
            case 2: return 'Du hast keine Kategorie und keine Playlist vergeben.';
            case 3: return 'Du kannst nicht eine alte Playlist wählen, und trotzdem eine neue angeben.';
            case 4: return 'Neue Playlist erkannt, aber ID fehlt.';
            case 5: return 'Du kannst nicht eine alte Kategorie wählen, und trotzdem eine neue angeben.';
            case 6: return 'Wähle einen Parent für die neue Kategorie.';
            case 7: return 'Du kannst nur neue Playlist ODER kategorie auswählen.';
        }
    }

    function getSize($s) {
        $i = 0;
        $t = array(' B',' kiB',' MiB',' GiB');
        while($s > 1024) {
            $s = $s / 1024;
            $i++;
        }
        $s = round($s, 2).$t[$i];
        return $s;
    }

    function showInfo($msg, $refr, $title = null) {
        if ($title === null ) {
            $title = I18n::t('page.info.title');
        }

      return array(
        'filename'  => 'static.php',
        'title'     => $title,
        'data'      => $msg,
        'refresh'   => $refr
      );
    }

    function refreshCookies($pass = ''){
        $UserID = $_COOKIE['UserID'];
        if($pass == '') {
            $Hash = $_COOKIE['Password'];
        } else {
            $Hash = $pass;
        }
        setcookie('UserID', $UserID, strtotime("+1 day"), '/');
        setcookie('Password', $Hash, strtotime("+1 day"), '/');
    }

    function buildLinkTitle($title){
        $removes = '#?|().,;:{}[]/';
        $strokes = array(' ', '---', '--');
        for($i = 0; $i < strlen($removes); $i++) {
            $title = str_replace($removes[$i], '', $title);
        }
        foreach($strokes as $char) {
            $title = str_replace($char, '-', $title);
        }
        return $title;
    }

    function shortenTitle($title, $l = 20) {
        if(strlen($title) > $l) {
            $title = substr($title, 0, $l - 1).'...';
        }
        return $title;
    }

    function makeAbsolutePath($path, $append = '', $stay_local = false) {
        if($stay_local || Utilities::getRemoteAddress() === null)
            return 'http://'.Utilities::getSystemAddress().'/'.$path.$append;
        else
            return 'http://'.Utilities::getRemoteAddress().'/'.$path.$append;
    }

    /*** validation ***/

    function isValidUserUrl($url) {
        if(preg_match('/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/', $url)) {
            return true;
        }
        return false;
    }

    function urlExists($adresse) {
        $file = @fopen ($adresse, "r");
        if($file) {
            return true;
            fclose($file);
        } else {
            return false;
        }
    }

    function checkStandForm($user, $cnt, $mail, $page, $time, $fmail, $fpage, $formType) {
        if (('' == $user) OR ('' == $cnt) OR ('' == $mail)) {
            return 1;
        }
        if( (!is_numeric($time))                 || /* time ist keine Zahl */
            (20 >  time() - $time)               || /* weniger als 20sek angezeigt */
            (20 * 3 * 60 * 16 <  time() - $time) || /* Formular älter als 16h */
            ($fmail != '')                       || /* versteckte E-Mail wurde ausgefüllt */
            ($fpage != ''))                         /* versteckte URL wurde ausgefüllt */
        {
            return 2;
        }
        if(!checkMail($mail)) {
            return 3;
        }
        if($formType == 'conmmentForm' && !checkContentLength($cnt)) {
            return 4;
        }
        return 0;
    }

    function checkMail($email) {
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;
        } else {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
                // local part length max for smtp
                $isValid = false;
            } else if ($domainLen < 1 || $domainLen > 255) {
                // domain part length max for smtp
                $isValid = false;
            } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
                // local part starts or ends with '.'
                $isValid = false;
            } else if (preg_match('/\\.\\./', $local)) {
                // local part has two consecutive dots
                $isValid = false;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                // character not valid in domain part
                $isValid = false;
            } else if (preg_match('/\\.\\./', $domain)) {
                // domain part has two consecutive dots
                $isValid = false;
            } else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
                // character not valid in local part unless
                // local part is quoted
                if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
                    $isValid = false;
                }
            }
            if (!$local && $isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
                // domain not found in DNS
                $isValid = false;
            }
        }
        return $isValid;
    }

    function checkContentLength($cnt) {
        if(strlen($cnt) < 1500) {
            return true;
        }
        return false;
    }

    function isImage($a) {
        if( $a == 'image/gif' ||
            $a == 'image/pjepg' ||
            $a == 'image/jpeg' ||
            $a == 'image/png')
            return true;
        else
            return false;
    }

    function checkReplyId($id) {
        $db = Database::getDB()->getCon();
        if(!is_numeric($id)) {
            return -1;
        }

        $sql = 'SELECT
                    ID
                FROM
                    kommentare
                WHERE
                    ID = ?';
        $stmt = $db->prepare($sql);
        if (!$stmt) {return -1;}
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) {
            $stmt->close();
            return -1;
        }
        $stmt->bind_result($resID);
        if (!$stmt->fetch()) {
            $stmt->close();
            return -1;
        }
        $stmt->close();

        return $id;
    }

    /*** other ***/

    function getArticleLinks($options, $n) {
        $db         = Database::getDB();
        $res        = array();
        $fields     = array('ID');
        $conds      = array('enable = ? AND Datum < NOW()', 'i', array(1));
        $limit      = array('LIMIT 0, ?', 'i', array($n));
        $articles   = $db->select('news', $fields, $conds, $options, $limit);

        foreach ($articles as $article) {
            $article    = new Article($article['ID']);
            $title      = $article->getTitle();
            $res[]      = '<a href="'.$article->getLink().'" title="'.$title.'">'.shortenTitle($title, 25).'</a>';
        }

        return $res;
    }

    function getTopArticles($n = 5) {
        $options = 'GROUP BY ID ORDER BY Hits DESC, Datum DESC';
        return getArticleLinks($options, $n);
    }

    function getlastArticles($n) {
        $options = 'GROUP BY ID ORDER BY Datum DESC';
        return getArticleLinks($options, $n);
    }

    function getOffset($a, $o, $s) {
        if($a == 0) {
            return 0;
        }
        $num_pages = ceil($a/$o);  // Anzahl der Pages berechnen.
        if(!$num_pages) {$num_pages = 1;} // Anzahl auf min. 1 setzen
        // Die Start-Page muss zwischen 1 und $num_pages liegen
        if($s > $num_pages) {$s = $num_pages;}
        $offset = ($s - 1) * $o; // offset für den Query bestimmen
        if($s > 1) {$s = 1;}
        return $offset;
    }

    function getPages($a, $o, $s) {
        if($a == 0) {
            return 0;
        }
        $num_pages = ceil($a/$o);  // Anzahl der Pages berechnen.
        if(!$num_pages) {$num_pages = 1;} // Anzahl auf min. 1 setzen
        $offset = ($s - 1) * $o; // offset für den Query bestimmen
        // Die Start-Page muss zwischen 1 und $num_pages liegen
        if($s > 1) {$s = 1;}
        if($s > $num_pages) {$s = $num_pages;}
        return $num_pages;
    }

    function getCmt($id) {
        $db = Database::getDB();
        $cond = array('NewsID = ?', 'i', array($id));
        $res = $db->select('kommentare', array('COUNT(ID) AS n'), $cond);
        if($res != null)
            return $res[0]['n'];
        return 0;
    }

    function lic($l) {
        $ls = array('by' => 'Creative Commons Namensnennung',
                    'sa' => 'Weitergabe unter gleichen Bedingungen',
                    'nd' => 'Keine Bearbeitung',
                    'nc' => 'Nicht kommerziell');
        $l = str_replace('by', $ls['by'], $l);
        $l = str_replace('sa', $ls['sa'], $l);
        $l = str_replace('nd', $ls['nd'], $l);
        $l = str_replace('nc', $ls['nc'], $l);
        $l .= ' 3.0 Unported Lizenz';
        return $l;
    }

    function isDoubleBB($cnt, $bb) {
        $bbCl = '[/'.substr($bb, 1);
        $op1 = strpos($cnt, $bb);
        $op2 = strpos($cnt, $bb, $op1 + 1);
        $cl1 = strpos($cnt, $bbCl);
        $cl2 = strpos($cnt, $bbCl, $cl1 + 1);
        if($op2 - $op1 == strlen($bb) && $cl2 - $cl1 == strlen($bbCl)) {
            return true;
        }
        return false;
    }

    function removeDoubleBB($cnt, $bb) {
        $bbCl = '[/'.substr($bb, 1);
        $op1 = strpos($cnt, $bb);
        $op2 = strpos($cnt, $bb, $op1 + 1);
        $cl1 = strpos($cnt, $bbCl);
        $cl2 = strpos($cnt, $bbCl, $cl1 + 1);
        if($op2 - $op1 == strlen($bb) && $cl2 - $cl1 == strlen($bbCl)) {
            $fst = substr($cnt, 0, $op1);
            $sec = substr($cnt, $op2, $cl2 - $op2);
            $trd = substr($cnt, $cl2 + strlen($bbCl));
            $cnt = $fst.$sec.$trd;
        }
        return $cnt;
    }

    function remDoubles($cnt, $bbs) {
        foreach($bbs as $bb) {
            while(isDoubleBB($cnt, $bb)) {
                $cnt = removeDoubleBB($cnt, $bb);
            }
        }
        return $cnt;
    }

    function replaceUml($ret) {
        $ret = str_replace('ä', 'ae', $ret);
        $ret = str_replace('ö', 'oe', $ret);
        $ret = str_replace('ü', 'ue', $ret);
        $ret = str_replace('Ä', 'Ae', $ret);
        $ret = str_replace('Ö', 'Oe', $ret);
        $ret = str_replace('Ü', 'Ue', $ret);
        $ret = str_replace('ß', 'ss', $ret);
        return $ret;
    }

    function getYouTubeIDFromArticle($id) {
        $db = Database::getDB()->getCon();
        $ret = array();
        $sql = "SELECT
                    Inhalt
                FROM
                    news
                WHERE
                    ID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i', $id);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($cnt);
        $r = array();
        if($result->fetch()) {
            preg_match('#\[yt\](.{11})\[/yt\]#', $cnt, $matches);
            return preg_replace('#\[yt\](.{11})\[/yt\]#', '$1', $matches[0]);
        }
        $result->close();
        return -1;
    }

    function rewriteUrl($url) {
        if(preg_match('#^(http(s)?://)(.*)#', $url) == 0) {
            return 'http://'.$url;
        }
        return $url;
    }

    function articlesInDate($year, $month = 0, $day = 0) {
        $db = Database::getDB()->getCon();
        $return = '';
        // sql
        if($month == 0 && $day == 0) {
            $sql = "SELECT
                        COUNT(Datum) AS number
                    FROM
                        news
                    WHERE
                        YEAR(Datum) = ? AND
                        Datum < NOW()";
            if(!$result = $db->prepare($sql)) {
                $return = $db->error;
            }
            $result->bind_param('i', $year);
        } else if($day == 0) {
            $sql = "SELECT
                        COUNT(Datum) AS number
                    FROM
                        news
                    WHERE
                        YEAR(Datum) = ? AND
                        MONTH(Datum) = ? AND
                        Datum < NOW()";
            if(!$result = $db->prepare($sql)) {
                $return = $db->error;
            }
            $result->bind_param('ii', $year, $month);
        } else {
            $sql = "SELECT
                        COUNT(Datum) AS number
                    FROM
                        news
                    WHERE
                        YEAR(Datum) = ? AND
                        MONTH(Datum) = ? AND
                        DAY(Datum) = ? AND
                        Datum < NOW()";
            if(!$result = $db->prepare($sql)) {
                $return = $db->error;
            }
            $result->bind_param('iii', $year, $month, $day);
        }
        if(!$result->execute()) {
            $return = $result->error;
        }
        $result->bind_result($number);
        if(!$result->fetch()) {
            $return = $result->error;
        }
        $result->close();
        if($return == '') {
            return $number;
        }
        return 0;
    }

    function makeMonthName($month) {
        $names = array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
        return $names[$month - 1];
    }
?>