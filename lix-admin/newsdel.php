<?php
    $a = array();
    $user = User::newFromCookie();
    if ($user && $user->isAdmin()) {
        refreshCookies();
        $a['filename'] = 'newsdel.php';
        $a['data'] = array();
        $db = Database::getDB();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if (isset($_POST['formactiondel'])) {

                # unlink image files
                $fields = array('file_name');
                $conds  = array('article_id = ?', 'i', array($_POST['newsid2']));
                $images = $db->select('images', $fields, $conds);

                foreach ($images as $image) {
                    Image::delete($image['file_name']);
                }

                # remove images from db
                $conds  = array('article_id = ?', 'i', array($_POST['newsid2']));
                $res    = $db->delete('images', $conds);

                # remove newscatcross
                $conds  = array('NewsID = ?', 'i', array($_POST['newsid2']));
                $res    = $db->delete('newscatcross', $conds);

                # remove tags from db
                $conds  = array('news_id = ?', 'i', array($_POST['newsid2']));
                $res    = $db->delete('tags', $conds);

                # remove news
                $conds  = array('ID = ?', 'i', array($_POST['newsid2']));
                $res    = $db->delete('news', $conds);

                return showInfo('Der Blogeintrag wurde gelöscht. <br /><a href="/admin" class="back">Zurück zur Administration</a>', 'admin');

            } else if(isset($_POST['formactionchoose'])) {
                # get news details
                $fields = array('ID', 'Titel', 'Inhalt');
                $conds  = array('ID = ?', 'i', array(trim($_POST['newsid'])));
                $images = $db->select('news', $fields, $conds);

                if (count($images)) {
                    $a['data']['newsedit'] = array(
                                                'newsidbea'     => $images[0]['ID'],
                                                'newsinhalt'    => Parser::parse(   $images[0]['Inhalt'],
                                                                                    Parser::TYPE_EDIT),
                                                'newstitel'     => Parser::parse(   $images[0]['Titel'],
                                                                                    Parser::TYPE_EDIT));
                }
            }
        }

        // get all news for select field
        $fields   = array('ID', 'Titel',
                          "DATE_FORMAT(Datum, '".DATE_STYLE."') AS date_formatted");
        $options  = 'ORDER BY Datum DESC';
        $articles = $db->select('news', $fields, null, $options);
        $news     = array();

        foreach ($articles as $article) {
            $news[$article['ID']] = array(  'newsid' => $article['ID'],
                                            'newsdatum' => $article['date_formatted'],
                                            'newstitel' => $article['Titel']);
        }

        $a['data']['news'] = $news;
        $a['data']['admin_news'] = true;

        return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
    } else if($user){
        return showInfo('Sie haben hier keine Zugriffsrechte.', 'blog');
    } else {
        return showInfo('Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>', 'login');
    }
?>