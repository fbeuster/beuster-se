<?php
    function changetext2($str, $type, $mob, $l = 750) {
    
        // Leerzeich am Anfang/Ende weg
        $str = trim($str);
    
        if($type != 'neu') {    
            // Zeichen wie < in &lt; umwandeln
            $str = htmlspecialchars($str);
            $str = stripslashes($str);   
        }
        if($type == 'neu') {
            $str = preg_replace("=\\'=Uis", "&apos;", $str);
        }
        if($type != 'bea' && $type != 'neu') {
            //HTML Code
            while(strpos($str, '[code]') !== false) {
                $strCode = '';
                $posA = strpos($str, '[code]');
                $posE = strpos($str, '[/code]');
                $strCode = substr($str, $posA + 6, $posE - ($posA + 6));
                $strCodeAlt = substr($str, $posA, $posE - $posA + 7);
                $strCode = highlight_string($strCode, true);
                $strCode = preg_replace('#&amp;#Uis', '&', $strCode);
                $strCode = preg_replace('+<span style="color: #000000">+Uis', '', $strCode);
                $strCode = preg_replace('#</span>#Uis', '', $strCode);
                $strCode = preg_replace('#\n#Uis', '', $strCode);
                $strCode = preg_replace('#\"#', '"', $strCode);
                $str = str_replace($strCodeAlt, $strCode, $str);
            }
    
            // überschüssige Leerzeichen und Zeilenumbrüche löschen 
            $str = preg_replace('/(\r\n){2}/', '</p><p>', $str);
            $str = preg_replace('/(\s{2})\s+/', '\1', $str);
    
            if($type == 'inhalt' || $type == 'vorschau' || $type == 'cmtInhalt' || $type == 'descr') {
                if($type == 'descr') {
                    $str = preg_replace('=\[b\](.*)\[/b\]=Uis', '\1', $str);
                    $str = preg_replace('=\[i\](.*)\[/i\]=Uis', '\1', $str);
                    $str = preg_replace('=\[u\](.*)\[/u\]=Uis', '\1', $str);
                    $str = preg_replace('=\[del\](.*)\[/del\]=Uis', '\1', $str);
                    $str = preg_replace('=\[ins\](.*)\[/ins\]=Uis', '\1', $str);
                    $str = preg_replace('=\[h2\](.*)\[/h2\]=Uis', '\1', $str);
                    $str = preg_replace('=\[h3\](.*)\[/h3\]=Uis', '\1', $str);
                    $str = preg_replace('#\[bquote=(.*)\](.*)\[/bquote\]#Uis', '&quot;\2&quot;', $str);
                    $str = preg_replace('=\[quote\](.*)\[/quote\]=Uis', '&quot;\1&quot;', $str);
                    $str = preg_replace('=\[cite\](.*)\[/cite\]=Uis', '&quot;\1&quot;', $str);
                    $str = preg_replace('#\[cite=(.*)\](.*)\[/cite\]#Uis', '&quot;\2&quot;', $str);
                    $str = preg_replace('#\[url\](.*)\[/url\]#Uis', '\1', $str);
                    $str = preg_replace('#\[url=(.*)\](.*)\[/url\]#Uis', '\2', $str);
                    $str = preg_replace('#\[ul\](.*)\[/ul\]#Uis', ' \1', $str);
                    $str = preg_replace('#\[ol\](.*)\[/ol\]#Uis', ' \1', $str);
                    $str = preg_replace('#\[li\](.*)\[/li\]#Uis', ' \1', $str); 
                    $str = preg_replace('#\[yt\](.*)\[/yt\]#Ui', 'YouTube-Video', $str);
                    $str = preg_replace('#\[play\](.*)\[/play\]#Ui', 'YouTube-Playlist', $str);
                    $str = preg_replace('=\[/p\](.*)\[p\]=Ui', '<b>$1</b>', $str);
                    //$str = preg_replace('=\[/p\](.*)\[p\]=Ui', ' ', $str);
                    $str = preg_replace('=\[p\]=Ui', ' ', $str);
                    $str = preg_replace('=\[/p\]=Ui', ' ', $str);
                    $str = preg_replace('#</p><br />#', ' ', $str);
                } else {
                    // Textformatierung (b, i, u) umwandeln
                    $str = preg_replace('=\[b\](.*)\[/b\]=Uis', '<b>\1</b> ', $str);
                    $str = preg_replace('=\[i\](.*)\[/i\]=Uis', '<i>\1</i> ', $str);
                    $str = preg_replace('=\[u\](.*)\[/u\]=Uis', '<u>\1</u> ', $str);
                    $str = preg_replace('=\[del\](.*)\[/del\]=Uis', '<del>\1</del> ', $str);
                    $str = preg_replace('=\[ins\](.*)\[/ins\]=Uis', '<ins>\1</ins> ', $str);
                    $str = preg_replace('=\[h2\](.*)\[/h2\]=Uis', '<h2>\1</h2> ', $str);
                    $str = preg_replace('=\[h3\](.*)\[/h3\]=Uis', '<h3>\1</h3> ', $str);
                    $citeSources = array();
                    $i = 0;
                    while($i >= 0 && $i < strlen($str)) {
                        if(strpos($str, '[cite=', $i) !== false) {
                            $i = strpos($str, '[cite=', $i) + 6;
                            $citeSources[] = substr($str, $i, strpos($str, ']', $i) - $i);
                            $i++;
                        } else if(strpos($str, '[bquote=', $i) !== false) {
                            $i = strpos($str, '[bquote=', $i) + 8;
                            $citeSources[] = substr($str, $i, strpos($str, ']', $i) - $i);
                            $i++;
                        } else {
                            $i = strlen($str);
                        }
                    }
                    if($type == 'vorschau') {
                        $str = preg_replace('#\[bquote=(.*)\](.*)\[/bquote\]#Uis', '<cite title="\1">\2</cite>', $str);
                    }
                    if($type == 'inhalt') {
                        $str = preg_replace('#\[bquote=(.*)\](.*)\[/bquote\]#Uis', '</p><blockquote cite="\1">\2<br><span class="source">Quelle: \1</span></blockquote><p>', $str);
                    }
                    $str = preg_replace('=\[quote\](.*)\[/quote\]=Uis', '&quot;\1&quot;', $str);
                    $str = preg_replace('=\[cite\](.*)\[/cite\]=Uis', '<cite>\1</cite>', $str);
                    $str = preg_replace('#\[cite=(.*)\](.*)\[/cite\]#Uis', '<cite title="\1">\2</cite>', $str);
                    $str = preg_replace("=&amp;=is", "&", $str);
        
                    // Links umwandeln
                    $str = preg_replace('#\[url\](.*)\[/url\]#Uis', '<a href="\1">\1</a> ', $str);
                    $str = preg_replace('#\[url=(.*)\](.*)\[/url\]#Uis', '<a href="\1">\2</a> ', $str);
                
                    if($type == 'vorschau'){
                        // Aufzäungen umwandeln
                        $str = preg_replace('#\[ul\](.*)\[/ul\]#Uis', ' <ul class="innewsvor">\1 </ul>', $str);
                        $str = preg_replace('#\[ol\](.*)\[/ol\]#Uis', ' <ol class="innewsvor">\1 </ol>', $str);
                        $str = preg_replace('#\[li\](.*)\[/li\]#Uis', '  <li>\1</li>', $str);
                    } else if($type != 'cmtInhalt'){
                        $str = preg_replace('#\[ul\](.*)\[/ul\]#Uis', ' <ul class="innews">\1 </ul>', $str);
                        $str = preg_replace('#\[ol\](.*)\[/ol\]#Uis', ' <ol class="innews">\1 </ol>', $str);
                        $str = preg_replace('#\[li\](.*)\[/li\]#Uis', '  <li>\1</li>', $str);
                    } else {
                        $str = preg_replace('#\[ul\](.*)\[/ul\]#Uis', ' \1', $str);
                        $str = preg_replace('#\[ol\](.*)\[/ol\]#Uis', ' \1', $str);
                        $str = preg_replace('#\[li\](.*)\[/li\]#Uis', ' \1', $str);      
                    }     
                    // searchmarks umwandeln
                    $str = preg_replace('#\[mark\](.*)\[/mark\]#Uis', '<mark>\1</mark>', $str);
            
                    // Zeilenumbrüche in HTML-Zeilenumbrü
                    $str = nl2br($str);
                    $str = preg_replace('#</li><br />#', '</li>', $str);
                    $str = preg_replace('#<ul class="innews"><br />#', '<ul class="innews">', $str);
                    $str = preg_replace('#<ul class="innewsvor"><br />#', '<ul class="innewsvor">', $str);
                    $str = preg_replace('#</ul><br />#', '</ul>', $str);
                    $str = preg_replace('#<ol class="innews"><br />#', '<ol class="innews">', $str);
                    $str = preg_replace('#<ol class="innewsvor"><br />#', '<ol class="innewsvor">', $str);
                    $str = preg_replace('#</ol><br />#', '</ol>', $str);
                    $codePos = 0;
                    $anz = substr_count($str, '<code>');
                    $anzC = 0;
                    while($anz < $anzC) {
                        $posA = strpos($str, '<code>', $codePos);
                        $posE = strpos($str, '</code>', $codePos);     
                        $strCode = substr($str, $posA, $posE - $posA - 7);
                        $strCode = preg_replace('#<br />#Uis', '', $strCode);
                        $str = preg_replace('#\<code\>(.*)\</code\>#Uis', $strCode, $str);
                        $codePos = $posE;
                        $anzC++;
                    }
                    // Absätze umwandeln
                    if($type == 'vorschau') {
                        $str = preg_replace('=\[/p\]<h(2|3)>(.*?)</h(2|3)>\[p\]=Ui', '<b>$2<b>', $str);
                        //$str = preg_replace('=\[/p\](.*)\[p\]=Ui', ' ', $str);
                        $str = preg_replace('=\[p\]=Ui', ' ', $str);
                        $str = preg_replace('=\[/p\]=Ui', ' ', $str);
                        $str = preg_replace('#</p><br />#', ' ', $str);
                    } else {
                        $str = preg_replace('=\[/p\]<h(2|3)>(.*?)</h(2|3)>\[p\]=Ui', '</p><h$1>$2</h$1><p>', $str);
                        //$str = preg_replace('=\[/p\](.*)\[p\]=Ui', '[/p][p]', $str);
                        $str = preg_replace('=\[p\]=Ui', '<p>', $str);
                        $str = preg_replace('=\[/p\]=Ui', '</p>', $str);
                        $str = preg_replace('#</p><br />#', '</p>', $str);
                        if($type != 'cmtInhalt') {
                            $str = preg_replace('#\[yt\](.*)\[/yt\]#Ui', '<iframe id="vid" width="780" height="426" src="http://www.youtube.com/embed/\1?wmode=transparent&color=white&theme=light" frameborder="0" allowfullscreen></iframe><a href="http://www.youtube.com/watch?v=\1" class="m">Video ansehen</a>', $str);
                            $str = preg_replace('#\[play\](.*)\[/play\]#Ui', '<iframe id="vid" width="780" height="426" src="http://www.youtube.com/embed/\1?wmode=transparent&color=white&theme=light" frameborder="0" allowfullscreen></iframe><span class="m">Eingebettete Playlist, derzeit nicht auf deinem Gerät verfügbar.</span>', $str);
                        }
                    }
                    // Smilies ersetzen
                    if($type != 'vorschau') {
                        $str = str_replace(':)', '<img class="sm" src="/images/smsmile.gif" alt="Keep smiling!">', $str); //52
                        $str = str_replace(':D', '<img class="sm" src="/images/smlaugh.gif" alt="Laughing">', $str);
                        $str = str_replace(':(', '<img class="sm" src="/images/smsad.gif" alt="I\'m sad.">', $str);
                        $str = str_replace(';)', '<img class="sm" src="/images/smone.gif" alt="You know...">', $str);
                    }
                    if($type == 'cmtInhalt') {
                        $str = preg_replace('#\[yt\](.*)\[/yt\]#Ui', '<a href="http://youtu.be/\1">\1</a> ', $str);
                    }
                    if($type == 'vorschau') {
                        $str = preg_replace('#\[yt\](.*)\[/yt\]#Ui', '<a href="###link###">Hier klicken um das Video zu sehen.</a> ', $str);
                        $str = preg_replace('#\[play\](.*)\[/play\]#Ui', '<a href="###link###">Hier klicken um das Video zu sehen.</a> ', $str);
                        $str = preg_replace('#<code>(.*)</code>#Uis','<a href="###link###">Hier klicken um den Code zu sehen.</a> ', $str);
                    }
                    if(!$mob) {
                        $str = preg_replace('#<code>(.*)</code>#Uis', '</p><pre class="code"><code>\1</code></pre><p>', $str);
                    } else {
                        $str = preg_replace('#<code>(.*)</code>#Uis', '<textarea class="code" cols="35" rows="15">\1</textarea>', $str);
                    }
                    if(count($citeSources) > 0 && $type == 'inhalt') {
                        $str .= '</p><p class="citeList"><u>Quellen:</u><ol>';
                        foreach($citeSources as $source) {
                            if(urlExists($source)) {
                                $str .= '<li><a href="'.$source.'">'.$source.'</a></li>';
                            } else {
                                $str .= '<li>'.$source.'</li>';
                            }
                        }
                    }
                }
                if(($type == 'vorschau' or $type == 'descr') and strlen($str) > $l) {
                    $toClose = '';
                    $toClosePos = 0;
                    $toCloseEndPos = 0;
                    $i = 0;
                    $res = '';
                    while($toCloseEndPos < $l && $i !== false) {
                        $toCloseOldPos = $toCloseEndPos;
                        $toCloseEndPos = $toClosePos;
                        $i = strpos($str, '<', $i);
                        if($i != strpos($str, '</', $i)) {
                            if($toClose == '') {
                                $toClose = substr($str, $i + 1, (strpos($str, '>', $i) - $i));
                                $toClose = substr($toClose, 0, strpos($toClose, ' '));
                                if($toClose == 'br' || $toClose == 'p') $toClose = '';
                                if($toClose != '') {
                                    $toClosePos = strpos($str, $toClose, $i) - 1;
                                }
                            }
                        } else {
                            if($toClose == substr($str, $i + 2, strpos($str, '>', $i) - $i - 2)) {
                                $toClose = '';
                                $toCloseEndPos = strpos($str, '>', $i) + 1;
                            }
                        }
                        if($i > $l)
                            $i = false;
                        else
                            $i++;
                    }
                    if($i === false || $toClosePos == 0) {
                        $i = $l;
                    } else {
                        $smallest = min(abs($l - $toCloseOldPos),abs($l - $toClosePos),abs($l - $toCloseEndPos));
                        switch($smallest) {
                            case abs($l - $toClosePos): $i = $toClosePos;break;
                            case abs($l - $toCloseEndPos): $i = $toCloseEndPos;break;
                            case abs($l - $toCloseOldPos): $i = $toCloseOldPos;break;
                        }
                    }
                    $str = substr($str, 0, $i);
                    /* wenn $l in einem Wort */
                    if(substr($str, -1) != '>')
                        $str = substr($str, 0, strrpos($str, ' '));
                    if($type == 'vorschau' )
                        $str .= ' <a href="###link###"> weiter...</a>';
                    else
                        $str .= '... Mehr im Blog!';
                } 
            }
        }
        return $str;
    }
?>