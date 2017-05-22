<?php

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
        $db     = Database::getDB();
        $fields = array('Email');
        $conds  = array('Rights = ?', 's', array('admin'));
        $res    = $db->select('users', $fields, $conds);

        if (count($res) == 0) {
            return false;
        }

        $admin_mail   = $res[0]['Email'];
        $mail_topic   = 'Neuer Kommentar zu "'.$title.'"';

        $mail_content = '<h1>'.$title.'</h1>';
        $mail_content .= '<p>'.$content.'</p>';
        $mail_content .= '<p>von: '.$user.'</p>';

        $mail_body    = '<html>';
        $mail_body    .= '<head><title>Neuer Kommentar</title>';
        $mail_body    .= '</head>';
        $mail_body    .= '<body>';
        $mail_body    .= $mail_content;
        $mail_body    .= '</body></html>';

        $mail_header  = 'MIME-Version: 1.0'."\n";
        $mail_header  .= 'Content-Type: text/html; charset=utf-8'."\n";
        $mail_header  .= 'From: beuster{se} Kommentare <info@beusterse.de>'."\n";
        $mail_header  .= 'Reply-To: beuster{se} Kommentare <info@beusterse.de>'."\n";
        $mail_header  .= 'X-Mailer: PHP/'.phpversion().'\r\n';

        // return
        if (!Utilities::isDevServer()) {
            return mail($admin_mail, $mail_topic, $mail_body, $mail_header);
        }
        return false;
    }

?>