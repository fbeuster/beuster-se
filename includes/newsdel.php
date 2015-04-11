<?php
    $a = array();
    if (getUserID() and hasUserRights('admin')) {
        refreshCookies();
        $a['filename'] = 'newsdel.php';
        $a['data'] = array();
        $db = Database::getDB()->getCon();
      
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if (isset($_POST['formactiondel'])) {

                // unlink image files
                $sql = 'SELECT
                            Pfad
                        FROM
                            pics
                        WHERE
                            NewsID = ?';
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('i', $_POST['newsid2']);
                if(!$stmt->execute()) {return $stmt->error;}
                $stmt->bind_result($path);
                $pics = array();
                while($stmt->fetch()) {
                    $pics[] = $path;
                }
                $pics2 = $pics;
                foreach($pics2 as $pi) {
                    $pic[] = str_replace('.', '_', $pic).'.jpg';
                }
                foreach($pics as $pic) {
                    if(file_exists($pic)) {
                        unlink($pic);
                    }
                }    
                $stmt->close();

                // remove images from db
                $sql = 'DELETE FROM
                            pics
                        WHERE
                            NewsID = ?';
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('i', $_POST['newsid2']);
                if(!$stmt->execute()) {return $stmt->error;}
                $stmt->close();

                // remove newscatcross
                $sql = 'DELETE FROM
                            newscatcross
                        WHERE
                            NewsID = ?';
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('i', $_POST['newsid2']);
                if(!$stmt->execute()) {return $stmt->error;}
                $stmt->close();

                // remove tags from db
                $sql = 'DELETE FROM
                            tags
                        WHERE
                            news_id = ?';
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('i', $_POST['newsid2']);
                if(!$stmt->execute()) {return $stmt->error;}
                $stmt->close();

                // remove news
                $sql = 'DELETE FROM
                            news
                        WHERE
                            ID = ?';
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('i', $_POST['newsid2']);
                if(!$stmt->execute()) {return $stmt->error;}
                $stmt->close();

                return showInfo('Der Blogeintrag wurde gelöscht. <br /><a href="/admin" class="back">Zurück zur Administration</a>', 'admin');
                
            } else if(isset($_POST['formactionchoose'])) {
                // get news details
                $sql = 'SELECT
                            ID,
                            Titel,
                            Inhalt
                        FROM
                            news
                        WHERE
                            ID = ?';
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('i', trim($_POST['newsid']));
                if(!$stmt->execute()) {return $stmt->error;}
                $stmt->bind_result($newsid, $newstitel, $newsinhalt);
                if(!$stmt->fetch()) {return 'Es wurde keine News mit dieser ID gefunden. <br /><a href="/newsdel" class="back">Zurück zum Löschdialog</a>';}
                $a['data']['newsbea'] = array(
                                            'newsidbea'     => $newsid,
                                            'newsinhalt'    => changetext($newsinhalt, 'bea', $mob),
                                            'newstitel'     => changetext($newstitel, 'bea', $mob));
                $stmt->close();
            }
        }

        // get all news for select field
        $sql = "SELECT
                    ID,
                    Titel,
                    DATE_FORMAT(Datum, '".DATE_STYLE."') AS Changedatum
                FROM
                    news
                ORDER BY
                    Datum DESC";
        if(!$stmt = $db->query($sql)) {return $db->error;}
        $news = array();       
        while($row = $stmt->fetch_assoc()) {
            $news[$row['ID']] = array('newsid'=>$row['ID'], 'newsdatum'=>$row['Changedatum'], 'newstitel'=>$row['Titel']);
        }
        $stmt->close();

        $a['data']['news'] = $news;
        $a['data']['admin_news'] = true;
      
        return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
    } else if(getUserID()){
        return 'Sie haben hier keine Zugriffsrechte.';
    } else {
        return 'Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>';
    }
?>