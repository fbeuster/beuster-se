<?php
    include('functionsNews.php');
    include('functionsUser.php');
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

    function getLink($cat, $id, $title){
        $r = '/'.$id.'/'.lowerCat($cat).'/'.replaceUml(buildLinkTitle($title));
        return $r;
    }

    function linkGrab($id) {
        $db = Database::getDB()->getCon();
        if($id <= 0) return '#';
        $ena = 1;
        $sql = "SELECT
                    Titel
                FROM
                    news
                WHERE
                    enable = ? AND
                    ID = ?";
        if(!$result = $db->prepare($sql)) {
            echo $db->error;
            return '#';
        }
        $result->bind_param('ii', $ena, $id);
        if(!$result->execute()) {
            echo $result->error;
            return '#';
        }
        $result->bind_result($title);
        if(!$result->fetch()) return '#';
        $result->close();
        return substr(getLink(getCatName(getNewsCat($id)), $id, $title), 1);
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

    /*** search and ranking ***/

    function sortCompare($a, $b) {
        if($a['score'] == $b['score'])
            return 0;
        return ($a['score'] > $b['score']) ? -1 : 1;
    }

    function rank($entry, $searchStr, $caseSens) {
        $searchStrLen = strlen($searchStr);
        $cnt = preg_replace('#\.#Uis', ' ', $entry);
        $cnt = preg_replace('#:#Uis', ' ', $cnt);
        $cnt = preg_replace('#,#Uis', ' ', $cnt);
        $cnt = preg_replace('#;#Uis', ' ', $cnt);
        $cnt = explode(' ', $cnt);
        foreach($cnt as $k => $v) {
            $cnt[$k] = trim(preg_replace('#\[(.*)\]#Uis', '', $v));
            if($cnt[$k] == '') {
                // leere Einträge löschen
                unset($cnt[$k]);
            } else if(abs(strlen($cnt[$k]) - $searchStrLen) > 2) {
                // Einträge löschen wenn Diff. zwischen Wort und Suchwort > 2
                unset($cnt[$k]);
            }
        }
        $cnt = array_merge($cnt);
        $unsets = array();
        foreach($cnt as $k => $v) {
            if($k == 0) {
                $p = 0;
            } else {
                $p = $cnt[$k-1][2];
            }
            $cnt[$k] = array(
                            $v,
                            distance($v, $searchStr, $caseSens),
                            strpos($entry, $v, $p));
            if($cnt[$k][1] > 2)
                $unsets[] = $k;
        }
        foreach($unsets as $k) {
            unset($cnt[$k]);
        }
        return count($cnt);
    }

    function distance($a, $b, $case) {
        if($case)
            return lewenshteinDistance($a,$b);
        else
            return lewenshteinDistance(strtolower($a),strtolower($b));
    }

    function lewenshteinDistance($strA, $strB) {
        $lenStrA = strlen($strA);
        $lenStrB = strlen($strB);
        if($lenStrA == 0) {
            $arrA[] = 'em';
            $lenStrA++;
        } else {
            $arrA = str_split($strA);
            if($arrA[0] != '') {
                array_unshift($arrA, 'em');
                $lenStrA++;
            }
        }
        if($lenStrB == 0) {
            $arrB[] = 'em';
            $lenStrB++;
        } else {
            $arrB = str_split($strB);
            if($arrB[0] != '') {
                array_unshift($arrB, 'em');
                $lenStrB++;
            }
        }
        $distance = array();
        for($i = 0; $i < $lenStrA; $i++) {
            $distance[$i] = array();
            for($j = 0; $j < $lenStrB; $j++) {
                $findMin = array();
                if($arrA[$i] == $arrB[$j]) {
                    if($i == 0 && $j == 0) {
                        $findMin[] = 0;
                    } else {
                        $findMin[] = $distance[$i-1][$j-1];
                    }
                }
                if($arrA[$i] != $arrB[$j]) {
                    if($i == 0) {
                        $findMin[] = $distance[$i][$j-1] + 1;
                    } else if($j == 0) {
                        $findMin[] = $distance[$i-1][$j] + 1;
                    } else {
                        $findMin[] = $distance[$i-1][$j-1] + 1;
                    }
                }
                if($i > $j) {
                    $findMin[] = $distance[$i-1][$j] + 1;
                }
                if($j > $i) {
                    $findMin[] = $distance[$i][$j-1] + 1;
                }
                $distance[$i][$j] = min($findMin);
            }
        }
        return $distance[$lenStrA-1][$lenStrB-1];
    }

    function searchmark($txt, $str, $bbcode = false) {
        $str = mb_strtolower($str, 'UTF-8');
        $i = 0;
        $j = 0;
        $j = strlen($str);
        while ($i !== false){
            $i = strpos(mb_strtolower($txt, 'UTF-8'), $str, $i);
            if($i !== false && $i <= strlen($txt) - $j){
                $t1 = substr($txt, 0, $i);
                if($bbcode){$t2 = '[mark]';} else {$t2 = '<mark>';}
                $t3 = substr($txt, $i, $j);
                if($bbcode){$t4 = '[/mark]';} else {$t4 = '</mark>';}
                $t5 = substr($txt, $i + $j);
                $txt = $t1.$t2.$t3.$t4.$t5;
                $i += 13 + $j;
            }
        }
        return $txt;
    }

    /*** other ***/

    function getArticleLinks($sql, $n) {
        $db  = Database::getDB()->getCon();
        $res = array();

        if(!$stmt = $db->prepare($sql)) {
            return array($db->error);
        }

        $stmt->bind_param('i', $n);

        if(!$stmt->execute()) {
            return array($stmt->error);
        }

        $stmt->bind_result($id, $db_title);

        $articles = array();
        while($stmt->fetch()) {
            $articles[$id] = $db_title;
        }
        $stmt->close();

        foreach ($articles as $id => $db_title) {
            $title  = Parser::parse($db_title, Parser::TYPE_PREVIEW);
            $link   = getLink(getCatName(getNewsCat($id)), $id, $title);
            $res[]  = '<a href="'.$link.'" title="'.$title.'">'.shortenTitle($title, 25).'</a>';
        }

        return $res;
    }

    function getTopArticles($n = 5) {
        $sql = "SELECT   ID, Titel
                FROM     news
                WHERE    enable = 1 AND Datum < NOW()
                GROUP BY ID
                ORDER BY Hits DESC, Datum DESC
                LIMIT    0, ?";
        return getArticleLinks($sql, $n);
    }

    function getlastArticles($n) {
        $sql = "SELECT   ID, Titel
                FROM     news
                WHERE    enable = 1 AND Datum < NOW()
                GROUP BY ID
                ORDER BY Datum DESC
                LIMIT    0, ?";
        return getArticleLinks($sql, $n);
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

    function getAnz($dateSQL = "Datum < NOW()") {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    COUNT(*) as Anzahl
                FROM
                    news
                WHERE
                    enable = 1 AND
                    ".$dateSQL;
        if(!$anz = $db->prepare($sql)) {return $db->error;}
        if(!$anz->execute()) {return $result->error;}
        $anz->bind_result($a);
        if(!$anz->fetch()) {return 'Es wurden keine News gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $anz->close();
        return $a;
    }

    function getAnzDev($dateSQL = "Datum < NOW()") {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    COUNT(news.ID) as Anzahl
                FROM
                    news
                LEFT JOIN
                    newscatcross
                    ON news.ID = newscatcross.NewsID
                WHERE
                    ".$dateSQL." AND
                    newscatcross.Cat != 12";
        if(!$anz = $db->prepare($sql)) {return $db->error;}
        if(!$anz->execute()) {return $result->error;}
        $anz->bind_result($a);
        if(!$anz->fetch()) {return 'Es wurden keine News gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $anz->close();
        return $a;
    }

    function getCmt($id) {
        $db = Database::getDB();
        $cond = array('NewsID = ?', 'i', array($id));
        $res = $db->select('kommentare', array('COUNT(ID) AS n'), $cond);
        if($res != null)
            return $res[0]['n'];
        return 0;
    }

    function getNewsPicNumber($id) {
        $db = Database::getDB();
        $cond = array('NewsID = ?', 'i', array($id));
        $res = $db->select('pics', array('COUNT(ID) AS n'), $cond);
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

    function getProjState($state) {
        switch($state) {
            case 0: return 0;
            case 1: return '1in Bearbeitung';
            case 2: return '2nicht vordergründig';
            case 3: return '3pausiert';
            case 4: return '4beendet';
            default: return 0;
        }
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