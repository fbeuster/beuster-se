<?php

    function getNewsTags($db, $newsId, $returnString) {
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
 
    function getNewsIDsTitlesContentCat($db) {
        $sql = "SELECT
                    news.ID,
                    news.Titel,
                    news.Inhalt,
                    newscatcross.Cat
                FROM
                    news
                LEFT JOIN
                    newscatcross ON
                    news.ID = newscatcross.NewsID";
        if(!$stmt = $db->prepare($sql)){return $db->error;}
        if(!$stmt->execute()) {return $result->error;}
        $stmt->bind_result($id, $t, $c, $cat);
        $news = array();
        while($stmt->fetch()) {
            $news[] = array($id, $t, $c, $cat);
        }
        $stmt->close();
        return $news;  
    }
 
    function getNewsTitle($db, $id = -1) {
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
        return changetext($title, 'titel', true);  
    }

    function isNewsVisible($db, $id) {
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
 
    function getAnzNews($db) {
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
 
    function newsExists($db, $id){
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
  
    function increaseHitNumber($db, $id) {
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
 
    function getNewsTitel($db, $id) {
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
 
    function getHits($db, $id) {
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
 
    function getNewsID($db, $id, $cat) {
        $cat = getCatID($db, $cat);
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
 
    function getNewsCat($db, $id) {
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
 
    function getNewsCatID($db, $id) {
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
    
    function getMaxNewsUpTime($db) {
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
    function getNewsUpTime($db, $newsid) {
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

    function getArticleAttribute($db, $article, $attribute) {
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

?>