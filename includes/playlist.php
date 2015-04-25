<?php
    $url = array();
    $urls = array();

    // set feed URL
    $feedURL = 'http://gdata.youtube.com/feeds/api/playlists/'.$playlistID;
    //$feedURL = 'http://gdata.youtube.com/feeds/api/users/waterwebdesign/playlists';

    $i = 0;
    do {
        $again = false;
        // read feed into SimpleXML object
        $sxml = simplexml_load_file($feedURL);
        // iterate over entries in feed
        foreach ($sxml->entry as $entry) {
            $i++;
            // get nodes in media: namespace for media information
            $media = $entry->children('http://search.yahoo.com/mrss/');
            // get video player URL
            $attrs = $media->group->player->attributes();
            $watch = $attrs['url'];
            // get video thumbnail
            $attrs = $media->group->thumbnail[0]->attributes();
            $thumbnail = $attrs['url'];
            // get <yt:duration> node for video length
            $yt = $media->children('http://gdata.youtube.com/schemas/2007');
            $attrs = $yt->duration->attributes();
            $length = $attrs['seconds'];
            // get <yt:stats> node for viewer statistics
            $yt = $entry->children('http://gdata.youtube.com/schemas/2007');
            $attrs = $yt->statistics->attributes();
            #$viewCount = $attrs['viewCount'];
            $titleYt = $media->group->title;
            $vidID = $watch;
            $vidID = preg_replace('#http://www.youtube.com/watch\?v=#Us', '', $vidID);
            $vidID = preg_replace('#&feature=youtube_gdata_player#Us', '', $vidID);
            $pfad = 'images/tmp/'.$playlistID.'-'.$vidID.'.jpg';
            if(!file_exists($pfad)) {
                $imagequelle = imagecreatefromjpeg($thumbnail);
                $image = imagecreatetruecolor(480, 270);
                imagecopy($image, $imagequelle, 0, 0, 0, 45, 480, 270);
                imagedestroy($imagequelle);
                imagejpeg($image, $pfad);
                imagedestroy($image);
            }
            $art = linkGrab(getVideoArticle($vidID));
            $url[] = array('url' => $vidID, 'title' => $titleYt, 'dur' => $length, 'thumb' => $pfad, 'art' => $art);
        }
        foreach($sxml->link as $link) {
            $linkAttr = $link->attributes();
            if($linkAttr['rel'] == 'next') {
                $again = true;
                $feedURL = $linkAttr['href'];
            }
        }
    } while($again);

    $ytID = getYouTubeIDFromArticle($id);

    if($url[count($url)-1]['url'] == $ytID && count($url) >= 3) {
        $a['data']['videos'][] = $url[count($url)-2];
        $a['data']['videos'][] = $url[count($url)-3];
    } else if($url[0]['url'] == $ytID && count($url) >= 3) {
        $a['data']['videos'][] = $url[1];
        $a['data']['videos'][] = $url[2];
    } else {
        for($i = 1; $i < count($url) - 1; $i++) {
            if($url[$i]['url'] == $ytID) {
                $a['data']['videos'][] = $url[$i-1];
                $a['data']['videos'][] = $url[$i+1];
            }
        }
    }
?>