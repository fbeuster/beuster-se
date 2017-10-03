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

    function shortenTitle($title, $l = 20) {
        if(strlen($title) > $l) {
            $title = substr($title, 0, $l - 1).'...';
        }
        return $title;
    }

    function makeAbsolutePath($path, $append = '', $stay_local = false) {
        $protocol = Lixter::getLix()->getProtocol();

        if($stay_local || Utilities::getRemoteAddress() === null)
            return $protocol.'://'.Utilities::getSystemAddress().'/'.$path.$append;
        else
            return $protocol.'://'.Utilities::getRemoteAddress().'/'.$path.$append;
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
            return Lixter::getLix()->getProtocol().'://'.$url;
        }
        return $url;
    }
?>
