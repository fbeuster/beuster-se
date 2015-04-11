<?php
    $a = array();
    if (getUserID() and hasUserRights('admin')) {
        refreshCookies();
        $a['filename'] = 'stats.php';
        $a['data'] = array();
        $db = Database::getDB()->getCon();

        // get top 10 article stats
        $top = array();
        $sql = "SELECT
                    ID,
                    Titel,
                    Hits,
                    Datum,
                    TO_DAYS(NOW()) - TO_DAYS(Datum) AS TimeUp
                FROM            
                    news
                WHERE
                    enable = 1 AND
                    Datum < NOW()
                GROUP BY
                    ID
                ORDER BY                
                    Hits DESC,
                    Datum DESC
                LIMIT
                    0, 10";
        if(!$stmt = $db->prepare($sql)) {
            return $db->error;
        }
        if(!$stmt->execute()) {
            return $result->error;
        }
        $stmt->bind_result($id, $newstitel, $hits, $date, $timeUp);
        while($stmt->fetch()) {
            $top[] = array(
                        'Titel'     => changetext($newstitel, 'titel', $mob),
                        'Link'      => '',
                        'id'        => $id,
                        'date'      => date("d.m.Y H:i", strtotime($date)),
                        'hits'      => $hits,
                        'hitsPerDay'=> number_format($hits / ($timeUp<1?1:$timeUp), 2, '.', ','));
        }
        $stmt->close();
        foreach($top as $k => $v) {
            $top[$k]['Link'] = getLink(getCatName(getNewsCat($v['id'])), $v['id'], $v['Titel']);
        }
        $a['data']['top'] = $top;
        // get last 10 article stats
        $last = array();
        $sql = "SELECT
                    ID,
                    Titel,
                    Hits,
                    Datum,
                    TO_DAYS(NOW()) - TO_DAYS(Datum) AS TimeUp
                FROM            
                    news
                WHERE
                    enable = 1 AND
                    Datum < NOW()
                GROUP BY
                    ID
                ORDER BY
                    Datum DESC,
                    Hits DESC
                LIMIT
                    0, 10";
        if(!$stmt = $db->prepare($sql)) {
            return $db->error;
        }
        if(!$stmt->execute()) {
            return $result->error;
        }
        $stmt->bind_result($id, $newstitel, $hits, $date, $timeUp);
        while($stmt->fetch()) {
            $last[] = array(
                        'Titel'     => changetext($newstitel, 'titel', $mob),
                        'Link'      => '',
                        'id'        => $id,
                        'date'      => date("d.m.Y H:i", strtotime($date)),
                        'hits'      => $hits,
                        'hitsPerDay'=> number_format($hits / ($timeUp<1?1:$timeUp), 2, '.', ','));
        }
        $stmt->close();
        foreach($last as $k => $v) {
            $last[$k]['Link'] = getLink(getCatName(getNewsCat($v['id'])), $v['id'], $v['Titel']);
        }
        $a['data']['last'] = $last;
        $sql = "SELECT
                    Name,
                    downloads
                FROM
                    files";
        if(!$stmt = $db->prepare($sql)) {
            return $db->error;
        }
        if(!$stmt->execute()) {
            return $stmt->error;
        }
        $stmt->bind_result($name, $downloads);
        $down = array();
        while($stmt->fetch()) {
                $down[] = array(
                            'name' => $name,
                            'down' => $downloads);
        }
        $a['data']['down'] = $down;
        $stmt->close();
        return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
    } else if(getUserID()){
        return 'Sie haben hier keine Zugriffsrechte.';
    } else {
        return 'Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>';
    }
?>