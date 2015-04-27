<?php

    function getNewsTags($newsId, $returnString) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    tag
                FROM
                    tags
                WHERE
                    news_id = ?
                ORDER BY
                    ID ASC";
        $stmt = $db->prepare($sql);
        if($stmt === false) {
            if($returnString) {
                return '';
            } else {
                return array();
            }
        }
        $stmt->bind_param('i', $newsId);
        if(!$stmt->execute()) {
            if($returnString) {
                return '';
            } else {
                return array();
            }
        }
        $tags = array();
        $stmt->bind_result($tag);
        while($stmt->fetch()) {
            $tags[] = $tag;
        }
        $stmt->close();
        if($returnString) {
            return implode(',', $tags);
        } else {
            return $tags;
        }
    }

    function getNewsTitle($id = -1) {
        $db = Database::getDB()->getCon();
        if($id == -1) {
            $id = $_GET['n'];
        }
        $sql = "SELECT
                    Titel
                FROM
                    news
                WHERE
                    ID = ?";
        if(!$stmt = $db->prepare($sql)){return $db->error;}
        $stmt->bind_param('i', $id);
        if(!$stmt->execute()) {return $result->error;}
        $stmt->bind_result($title);
        if(!$stmt->fetch()) {return 'Es wurde keine solche News gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $stmt->close();
        return changetext($title, 'titel');
    }

    function isNewsVisible($id) {
        $db = Database::getDB()->getCon();
        $ena = 1;
        $sql = 'SELECT
                    ID
                FROM
                    news
                WHERE
                    ID = ? AND
                    enable = ? AND
                    Datum < NOW()';
        $stmt = $db->prepare($sql);
        if (!$stmt) {return $db->error;}
        $stmt->bind_param('ii', $id, $ena);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $stmt->bind_result($ênable);
        if (!$stmt->fetch()) {
            $stmt->close();
            return false;
        }
        $stmt->close();
        return true;
    }

    function getAnzNews() {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    Count(ID) As Newszahl
                FROM
                    news";
        if(!$stmt = $db->query($sql)) {return $db->error;}
        if($stmt->num_rows) {
            while($row = $stmt->fetch_assoc()) {
                $anzahl = $row['Newszahl'];
            }
        }
        $stmt->close();
        return $anzahl;
    }

    function newsExists($id){
        $db = Database::getDB()->getCon();
        $sql = 'SELECT
                    ID
                FROM
                    news
                WHERE
                    ID = ?';
        $stmt = $db->prepare($sql);
        if (!$stmt) {return false;}
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $stmt->bind_result($resID);
        if (!$stmt->fetch()) {
            $stmt->close();
            return false;
        }
        $stmt->close();
        return true;
    }

    function increaseHitNumber($id) {
        $db = Database::getDB()->getCon();
        $sql = 'UPDATE
                    news
                SET
                    Hits = Hits + 1
                WHERE
                    ID = ?';
        if(!$stmt = $db->prepare($sql)) {return $db->error;}
        $stmt->bind_param('i', $id);
        if(!$stmt->execute()) {return $stmt->error;}
        $stmt->close();
    }

    function getNewsTitel($id) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    Titel
                FROM
                    news
                WHERE
                    ID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i', $id);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($tit);
        if(!$result->fetch()){return $result->error;}
        $result->close();
        return $tit;
    }

    function getHits($id) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    Hits
                FROM
                    news
                WHERE
                    ID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i', $id);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($a);
        if(!$result->fetch()) {return 'Es wurde keine News mit dieser ID gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $result->close();
        return $a;
    }

    function getNewsID($id, $cat) {
        $db = Database::getDB()->getCon();
        $cat = getCatID($cat);
        $sql = "SELECT
                    NewsID
                FROM
                    newscatcross
                WHERE
                    CatID = ? AND
                    Cat = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('ii', $id, $cat);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($a);
        if(!$result->fetch()) {return 'Es wurde keine News mit dieser ID gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $result->close();
        return $a;
    }

    function getNewsCat($id) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    Cat
                FROM
                    newscatcross
                WHERE
                    NewsID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i', $id);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($a);
        if(!$result->fetch()) {return 'Es wurde keine News mit dieser ID gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $result->close();
        return $a;
    }

    function getNewsCatID($id) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    CatID
                FROM
                    newscatcross
                WHERE
                    NewsID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i', $id);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($a);
        if(!$result->fetch()) {return 'Es wurde keine News zu dieser ID gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $result->close();
        return $a;
    }

    function getMaxNewsUpTime() {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    MIN(UNIX_TIMESTAMP(Datum))
                FROM
                    news
                WHERE
                    Datum < NOW()";
        if(!$result = $db->prepare($sql)) {$return = $db->error;}
        if(!$result->execute()) {$return = $result->error;}
        $result->bind_result($newsUpTime);
        if(!($result->fetch())) {
            $result->close();
            return 0;
        } else {
            $result->close();
            return $newsUpTime;
        }
    }
    function getNewsUpTime($newsid) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    UNIX_TIMESTAMP(Datum)
                FROM
                    news
                WHERE
                    ID = ? AND
                    Datum < NOW()";
        if(!$result = $db->prepare($sql)) {$return = $db->error;}
        $result->bind_param('i', $newsid);
        if(!$result->execute()) {$return = $result->error;}
        $result->bind_result($newsUpTime);
        if(!($result->fetch())) {
            $result->close();
            return 0;
        } else {
            $result->close();
            return $newsUpTime;
        }
    }

    function getArticleAttribute($article, $attribute) {
        $db = Database::getDB()->getCon();
        switch ($attribute) {
            case 'enable':
                $sql = "SELECT
                            enable
                        FROM
                            news
                        WHERE
                            ID = ?";
                if(!$result = $db->prepare($sql))
                    return -1;
                $result->bind_param('i', $article);
                if(!$result->execute())
                    return -1;
                $result->bind_result($attributeValue);
                if(!($result->fetch())) {
                    $result->close();
                    return -1;
                }
                $result->close();
                return $attributeValue;
            default:
                return -1;
        }

    }

    function notifyAdmin($title, $content, $user) {
        $mailTopic = 'Neuer Kommentar zu "'.$titel.'"';
        $mailContent = '<html>';
        $mailContent .= '<head><title>Neuer Kommentar</title>';
        $mailContent .= '</head>';
        $mailContent .= '<body>';
        $mailRealContent = '<h1>'.$title.'</h1>';
        $mailRealContent .= '<p>'.$content.'</p>';
        $mailRealContent .= '<p>von: '.$user.'</p>';
        $mailContent .= $mailRealContent;
        $mailContent .= '</body></html>';
        $mailHeader = 'MIME-Version: 1.0'."\n";
        $mailHeader .= 'Content-Type: text/html; charset=utf-8'."\n";
        $mailHeader .= 'From: beuster{se} Kommentare <info@beusterse.de>'."\n";
        $mailHeader .= 'Reply-To: beuster{se} Kommentare <info@beusterse.de>'."\n";
        $mailHeader .= 'X-Mailer: PHP/'.phpversion().'\r\n';

        // return
        if(!Utilities::isDevServer()) {
            return mail(adminMail(), $mailTopic, $mailContent, $mailHeader);
        }
        return false;
    }

?>