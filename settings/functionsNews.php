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

    function getRandomArticle() {
        $db  = Database::getDB()->getCon();
        $ena = 1;
        $sql = "SELECT  ID
                FROM    news
                WHERE Datum < NOW() AND enable = ? AND ID >= (
                    SELECT FLOOR( MAX(ID) * RAND())
                    FROM news )
                ORDER BY ID
                LIMIT 1";

        if(!$stmt = $db->prepare($sql)) return null;

        $stmt->bind_param('i', $ena);

        if(!$stmt->execute()) return null;

        $stmt->bind_result($id);

        if(!$stmt->fetch()) return null;

        $stmt->close();

        return new Article($id);
    }

    function notifyAdmin($title, $content, $user) {
        $mailTopic = 'Neuer Kommentar zu "'.$title.'"';
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