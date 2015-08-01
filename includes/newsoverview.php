<?php
	$a = array();
  $user = User::newFromCookie();
	if ($user && $user->isAdmin()) {
        $db = Database::getDB()->getCon();
		refreshCookies();
		$a['filename'] = 'newsoverview.php';
		$a['data'] = array();

		// get articles
        $news = array();
        $sql = "SELECT
                    ID,
                    Titel,
                    Hits,
                    Datum,
                    TO_DAYS(NOW()) - TO_DAYS(Datum) AS TimeUp,
                    enable
                FROM
                    news
                ORDER BY
                    Datum DESC";
        if(!$stmt = $db->prepare($sql)) {
            return $db->error;
        }
        if(!$stmt->execute()) {
            return $stmt->error;
        }
        $stmt->bind_result($id, $newstitel, $hits, $date, $timeUp, $enabled);
        while($stmt->fetch()) {
            $news[] = array(
                        'Titel'     => Parser::parse($newstitel, Parser::TYPE_PREVIEW),
                        'Link'      => '',
                        'id'        => $id,
                        'date'      => date("d.m.Y H:i", strtotime($date)),
                        'hits'      => $hits,
                        'hitsPerDay'=> number_format($hits / ($timeUp<1?1:$timeUp), 2, '.', ','),
                        'enabled'   => $enabled);
        }
        $stmt->close();
        foreach($news as $k => $v) {
            $news[$k]['Link'] = getLink(getCatName(getNewsCat($v['id'])), $v['id'], $v['Titel']);
        }

        // get comment amount
        $sql = "SELECT
                    COUNT(ID) AS cmtAmount
                FROM
                    kommentare";
        if(!$stmt = $db->prepare($sql)) {
            return $db->error;
        }
        if(!$stmt->execute()) {
            return $stmt->error;
        }
        $stmt->bind_result($cmtAmount);
        if(!$stmt->fetch()) {
            return $stmt->error;
        }
        $stmt->close();

        // get comment amount
        $sql = "SELECT
                    COUNT(news.ID) AS enaAmount
                FROM
                    news
                LEFT JOIN
                    newscatcross
                    ON news.ID = newscatcross.NewsID
                WHERE
                    enable = 0 AND
                    newscatcross.Cat != 12";
        if(!$stmt = $db->prepare($sql)) {
            return $db->error;
        }
        if(!$stmt->execute()) {
            return $stmt->error;
        }
        $stmt->bind_result($enaAmount);
        if(!$stmt->fetch()) {
            return $stmt->error;
        }
        $stmt->close();

		$a['data']['news'] = $news;
        $a['data']['cmtAmount'] = $cmtAmount;
        $a['data']['enaAmount'] = $enaAmount;
        $a['data']['admin_news'] = true;

		return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
	} else if($user){
        return showInfo('Sie haben hier keine Zugriffsrechte.', 'blog');
	} else {
        return showInfo('Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>', 'login');
	}
?>