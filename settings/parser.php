<?php
    function changetext($str, $type, $mob, $l = 750) {
        switch($type) {
            case 'bea':
                $parsed = new EditParser($str);
                break;
            case 'neu':
                $parsed = new NewParser($str);
                break;
            case 'descr':
                $parsed = new DescriptionParser($str);
                break;
            case 'inhalt':
                $parsed = new ContentParser($str, $mob);
                break;
            case 'cmtInhalt':
                $parsed = new CommentParser($str, $mob);
                break;
            case 'vorschau':
                $parsed = new PreviewParser($str, $mob, $l);
                break;
            default:
                $parsed = new PreviewParser($str, $mob, $l);
                break;
        }
        return $parsed->parse();
    }
    
    //  ArticleParser
    //      contains all parsing stuff
    abstract class ArticleParser {
        public $str = '';
        public $mobile = false;
        public $citeSources = array();
        public $estimatedLength = 0;
        
        public function __construct() {
        }
        
        public function trimStr() {
            $this->str = trim($this->str);
        }
        
        public function htmlSpecial() {
            $this->str = htmlspecialchars($this->str);
            $this->str = stripslashes($this->str);   
        }
        
        public function codeFirstPass() {
            while(strpos($this->str, '[code]') !== false) {
                $strCode = '';
                $posA = strpos($this->str, '[code]');
                $posE = strpos($this->str, '[/code]');
                
                $strCode = substr($this->str, $posA + 6, $posE - ($posA + 6));
                $strCodeAlt = substr($this->str, $posA, $posE - $posA + 7);
                
                $strCode = highlight_string($strCode, true);
                
                $strCode = preg_replace('#&amp;#Uis', '&', $strCode);
                $strCode = preg_replace('+<span style="color: #000000">+Uis', '', $strCode);
                $strCode = preg_replace('#</span>#Uis', '', $strCode);
                $strCode = preg_replace('#\n#Uis', '', $strCode);
                $strCode = preg_replace('#\"#', '"', $strCode);
                
                #pre($strCodeAlt);
                #pre(htmlspecialchars($strCode));
                $this->str = str_replace($strCodeAlt, $strCode, $this->str);
            }
        }
        
        public function codeSecondPass() {
            $codePos = 0;
            $anz = substr_count($this->str, '<code>');
            $anzC = 0;
            while($anz > $anzC) {
                $posA = strpos($this->str, '<code>', $codePos);
                $posE = strpos($this->str, '</code>', $codePos);
                $strCode = substr($this->str, $posA + 6, $posE - $posA - 6);
                $strCodeOld = $strCode;

                /*$strCode = str_replace('<img class="sm" src="/images/smsmile.gif" alt="Keep smiling!">', ':)', $strCode);
                $strCode = str_replace('<img class="sm" src="/images/smlaugh.gif" alt="Laughing">', ':D', $strCode);
                $strCode = str_replace('<img class="sm" src="/images/smsad.gif" alt="I\'m sad.">', ':(', $strCode);
                $strCode = str_replace('<img class="sm" src="/images/smone.gif" alt="You know...">', ';)', $strCode);*/

                $strCode = preg_replace('#<br />#Uis', '', $strCode);
                $strCode = preg_replace('#^\r(.*)#', '$1', $strCode);

                $this->str = str_replace($strCodeOld, $strCode, $this->str);
                $codePos = $posA + strlen($strCode) + 7;
                $anzC++;
            }
            if($this->mobile) {
                $this->str = preg_replace('#<code>(.*)</code>#Uis', '<textarea class="code" cols="35" rows="15">\1</textarea>', $this->str);
            } else {
                $this->str = preg_replace('#<code>(.*)</code>#Uis', '</p><pre class="code"><code>\1</code></pre><p>', $this->str);                
            }
        }
        
        public function removeEmptySpace() {
            $this->str = preg_replace('/(\r\n){2}/', '[/p][p]', $this->str);
            $this->str = preg_replace('/(\s{2})\s+/', '\1', $this->str);
        }
        
        public function removeEmptyTags() {
            $this->str = preg_replace('#\[b\]\[/b\]#', '', $this->str);
            $this->str = preg_replace('#\[u\]\[/u\]#', '', $this->str);
            $this->str = preg_replace('#\[i\]\[/i\]#', '', $this->str);
            $this->str = preg_replace('#\[del\]\[/del\]#', '', $this->str);
            $this->str = preg_replace('#\[ins\]\[/ins\]#', '', $this->str);
            $this->str = preg_replace('#\[h2\]\[/h2\]#', '', $this->str);            
            $this->str = preg_replace('#\[h3\]\[/h3\]#', '', $this->str);              
        }
        
        public function textFormats() {
            $this->str = preg_replace('#\[b\](.+?)\[/b\]#', '<b>$1</b>', $this->str);
            $this->str = preg_replace('#\[u\](.+?)\[/u\]#', '<u>$1</u>', $this->str);
            $this->str = preg_replace('#\[i\](.+?)\[/i\]#', '<i>$1</i>', $this->str);
            $this->str = preg_replace('#\[del\](.+?)\[/del\]#', '<del>$1</del>', $this->str);
            $this->str = preg_replace('#\[ins\](.+?)\[/ins\]#', '<ins>$1</ins>', $this->str);
            $this->str = preg_replace('=&amp;=is', '&', $this->str);
        }
        
        public function headlines() {
            $this->str = preg_replace('#\[h2\](.+?)\[/h2\]#', '<h2>$1</h2>', $this->str);            
            $this->str = preg_replace('#\[h3\](.+?)\[/h3\]#', '<h3>$1</h3>', $this->str);         
        }
        
        public function removeHeadlines() {
            $this->str = preg_replace('#\[h2\](.+?)\[/h2\]#', '<b>$1</b>', $this->str);            
            $this->str = preg_replace('#\[h3\](.+?)\[/h3\]#', '<b>$1</b>', $this->str);
            $this->str = preg_replace('/\r\n/', ' ', $this->str);
        }
        
        public function removeFormats() {
            $this->str = preg_replace('#\[b\](.+?)\[/b\]#', '$1', $this->str);
            $this->str = preg_replace('#\[u\](.+?)\[/u\]#', '$1', $this->str);
            $this->str = preg_replace('#\[i\](.+?)\[/i\]#', '$1', $this->str);
            $this->str = preg_replace('#\[del\](.+?)\[/del\]#', '$1', $this->str);
            $this->str = preg_replace('#\[ins\](.+?)\[/ins\]#', '$1', $this->str);
            $this->str = preg_replace('=&amp;=is', '&', $this->str);
            $this->str = preg_replace('=\[quote\](.*)\[/quote\]=Uis', '&quot;$1&quot;', $this->str);
            $this->str = preg_replace('=\[cite\](.*)\[/cite\]=Uis', '&quot;$1&quot;', $this->str);
            $this->str = preg_replace('#\[cite=(.*)\](.*)\[/cite\]#Uis', '&quot;$2&quot;', $this->str);
            $this->str = preg_replace('#\[url\](.*)\[/url\]#Uis', '$1 ', $this->str);
            $this->str = preg_replace('#\[url=(.*)\](.*)\[/url\]#Uis', '$2 ', $this->str);           
        }
        
        public function collectQuotes() {
            $i = 0;
            while($i >= 0 && $i < strlen($this->str)) {
                if(strpos($this->str, '[cite=', $i) !== false) {
                    $i = strpos($this->str, '[cite=', $i) + 6;
                    $this->citeSources[] = substr($this->str, $i, strpos($this->str, ']', $i) - $i);
                    $i++;
                } else if(strpos($this->str, '[bquote=', $i) !== false) {
                    $i = strpos($this->str, '[bquote=', $i) + 8;
                    $this->citeSources[] = substr($this->str, $i, strpos($this->str, ']', $i) - $i);
                    $i++;
                } else {
                    $i = strlen($this->str);
                }
            }            
        }
        
        public function cites() {
            $this->str = preg_replace('=\[quote\](.*)\[/quote\]=Uis', '&quot;$1&quot;', $this->str);
            $this->str = preg_replace('=\[cite\](.*)\[/cite\]=Uis', '<cite>$1</cite>', $this->str);
            $this->str = preg_replace('#\[cite=(.*)\](.*)\[/cite\]#Uis', '<cite title="$1">$2</cite>', $this->str);
        }
        
        public function links() {
            $this->str = preg_replace('#\[url\](.*)\[/url\]#Uis', '<a href="$1">$1</a>', $this->str);
            #$this->str = preg_replace('#\[url=(.*)\](.*)\[/url\]#Uis', '<a href="$1">$2</a>', $this->str);
            $urlPos = 0;
            $anz = substr_count($this->str, '[url=');
            $anzUrl = 0;
            while($anz > $anzUrl) {
                $posA = strpos($this->str, '[url=', $urlPos);
                $posE = strpos($this->str, '[/url]', $urlPos);   
                $strUrl = substr($this->str, $posA, $posE - $posA + 6);
                $strUrlOld = $strUrl;
                $strUrl = preg_replace('#\[url=(.*?(\[.*?\]).*?)\](.*?)\[/url\]#Uis', '<a href="$1">$3</a>', $strUrl);
                $strUrl = preg_replace('#\[url=(.*)\](.*?)\[/url\]#Uis', '<a href="$1">$2</a>', $strUrl);
                $this->str = str_replace($strUrlOld, $strUrl, $this->str);
                $codePos = $posE + 5;
                $anzUrl++;
            }
        }
        
        public function breakLines() {
            $this->str = nl2br($this->str);
            $this->str = preg_replace('#<p><br />#', '<p>', $this->str);
            $this->str = preg_replace('#</p><br />#', '</p>', $this->str);
            $this->str = preg_replace('#</h2><br />#', '</h2>', $this->str);
            $this->str = preg_replace('#</h3><br />#', '</h3>', $this->str);
            $this->str = preg_replace('#</(li|ul|ol)><br />#', '</$1>', $this->str);
            $this->str = preg_replace('#<(u|o)l class="innews((vor)??)"><br />#', '<$1l class="innews$2">', $this->str);
        }
        
        public function removeBreakLines() {
            $this->str = preg_replace('#<br />#', ' ', $this->str);
            $this->str = preg_replace('#<br/>#', ' ', $this->str);
            $this->str = preg_replace('#<br>#', ' ', $this->str);
        }
        
        public function clearParagraphs() {
            $this->str = preg_replace('=\[/p\]<h2>(.*?)</h2>\[p\]=Ui', '<b>$1</b>', $this->str);
            $this->str = preg_replace('=\[/p\]<h3>(.*?)</h3>\[p\]=Ui', '<b>$1</b>', $this->str);
            $this->str = preg_replace('=\[p\]=Ui', ' ', $this->str);
            $this->str = preg_replace('=\[/p\]=Ui', ' ', $this->str);
        }
        
        public function paragraphs() {
            $this->str = preg_replace('=\[/p\]<h2>(.*?)</h2>\[p\]=Ui', '</p><h2>$1</h2><p>', $this->str);
            $this->str = preg_replace('=\[/p\]<h3>(.*?)</h3>\[p\]=Ui', '</p><h3>$1</h3><p>', $this->str);
            $this->str = preg_replace('=\[p\]=Ui', '<p>', $this->str);
            $this->str = preg_replace('=\[/p\]=Ui', '</p>'."\r\n", $this->str);
            $this->str = preg_replace('#<p><br />#', '<p>', $this->str);
            $this->str = preg_replace('#</p><br />#', '</p>'."\r\n", $this->str);
            $this->str = preg_replace('#<br />(\r\n)*?</p>#', '</p>'."\r\n", $this->str);
            $this->str = preg_replace('#</(u|o)l></p>#', '</$1l>', $this->str);
            $this->str = preg_replace('=<p><h2>(.*?)</h2>=Ui', '<h2>$1</h2><p>', $this->str);
            $this->str = preg_replace('=<p><h3>(.*?)</h3>=Ui', '<h3>$1</h3><p>', $this->str);
            $this->str = preg_replace('=<p></p>=Ui', '', $this->str);
        }
        
        public function illustrateSmiles() {
            /*$this->str = str_replace(':)', '<img class="sm" src="/images/smsmile.gif" alt="Keep smiling!">', $this->str);
            $this->str = str_replace(':D', '<img class="sm" src="/images/smlaugh.gif" alt="Laughing">', $this->str);
            $this->str = str_replace(':(', '<img class="sm" src="/images/smsad.gif" alt="I\'m sad.">', $this->str);
            $this->str = str_replace(';)', '<img class="sm" src="/images/smone.gif" alt="You know...">', $this->str);*/
        }
        
        public function appendQuoteSources() {
            if(count($this->citeSources) > 0) {
                $this->str .= '</p><p class="citeList"><u>Quellen:</u><ol>';
                foreach($this->citeSources as $source) {
                    if(urlExists($source)) {
                        $this->str .= '<li><a href="'.$source.'">'.$source.'</a></li>';
                    } else {
                        $this->str .= '<li>'.$source.'</li>';
                    }
                }
            }
        }

        public function affiliateImage() {
            $this->str = preg_replace('#\[affi=(.*)\]#Uis', '<img src="$1" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />', $this->str);
        }

        public function removeAffiliateImage() {
            $this->str = preg_replace('#\[affi=(.*)\]#Uis', '', $this->str);
        }
        
        public function shorten($message) {
            if(strlen($this->str) > $this->estimatedLength) {
                $toClose = '';
                $toClosePos = 0;
                $toCloseEndPos = 0;
                $i = 0;
                $res = '';
                while($toCloseEndPos < $this->estimatedLength && $i !== false) {
                    $toCloseOldPos = $toCloseEndPos;
                    $toCloseEndPos = $toClosePos;
                    $i = strpos($this->str, '<', $i);
                    if($i != strpos($this->str, '</', $i)) {
                        if($toClose == '') {
                            $toClose = substr($this->str, $i + 1, (strpos($this->str, '>', $i) - $i));
                            if(strpos($toClose, ' ')) {
                                $toClose = substr($toClose, 0, strpos($toClose, ' '));
                            } else {
                                $toClose = substr($toClose, 0, strpos($toClose, '>'));
                            }
                            if($toClose == 'br' || $toClose == 'p') $toClose = '';
                            if($toClose != '') {
                                $toClosePos = strpos($this->str, $toClose, $i) - 1;
                            }
                        }
                    } else {
                        if($toClose == substr($this->str, $i + 2, strpos($this->str, '>', $i) - $i - 2)) {
                            $toClose = '';
                            $toCloseEndPos = strpos($this->str, '>', $i) + 1;
                            $i = $toCloseEndPos;
                        }
                    }
                    if($i > $this->estimatedLength)
                        $i = false;
                    else
                        $i++;
                }
                
                if($toCloseEndPos < $this->estimatedLength && ($i === false || $toClosePos == 0)) {
                    $i = $this->estimatedLength;
                } else {
                    $smallest = min(abs($this->estimatedLength - $toCloseOldPos), abs($this->estimatedLength - $toClosePos), abs($this->estimatedLength - $toCloseEndPos));
                    switch($smallest) {
                        case abs($this->estimatedLength - $toClosePos):
                            $i = $toClosePos;
                            break;
                        case abs($this->estimatedLength - $toCloseEndPos):
                            $i = $toCloseEndPos;
                            break;
                        case abs($this->estimatedLength - $toCloseOldPos):
                            $i = $toCloseOldPos;
                            break;
                    }
                }
                $this->str = substr($this->str, 0, $i);
                /* wenn $l in einem Wort */
                if(substr($this->str, -1) != '>')
                    $this->str = substr($this->str, 0, strrpos($this->str, ' '));
                $this->str = $this->str.$message.$res;
            }
        }
        
        public function hideArticleImages() {
            $this->str = preg_replace('=\[img([0-9]*)\]=Ui', '', $this->str);
        }
        
        abstract function blockquotes();
        abstract function lists();
        abstract function parse();
        abstract function searchMarks();
        abstract function embedVideo();
    }
    
    //  PreviewParser
    //      parsing routine for preview (as seen in feed)
    class PreviewParser extends ArticleParser {        
        public function __construct($input, $mobile, $length) {
            parent::__construct();
            $this->str = $input;
            $this->mobile = $mobile;
            $this->estimatedLength = $length;
        }
        public function parse() {
            parent::trimStr();
            parent::htmlSpecial();
            parent::removeEmptyTags();
            parent::removeHeadlines();
            $this->code();
            parent::removeEmptySpace();
            parent::textFormats();
            $this->blockquotes();
            parent::cites();
            parent::links();
            $this->lists();
            parent::breakLines();
            parent::clearParagraphs();
            $this->embedVideo();
            parent::hideArticleImages();
            parent::removeAffiliateImage();
            parent::shorten(' <a href="###link###"> weiter...</a>');
            return $this->str;
        }
        
        public function blockquotes() {
            $this->str = preg_replace('#\[bquote=(.*)\](.*)\[/bquote\]#Uis', '<cite title="$1">$2</cite>', $this->str);
        }
        
        public function code() {
            $this->str = preg_replace('#\[code\](.*)\[/code\]#Uis','<a href="###link###">Hier klicken um den Code zu sehen.</a> ', $this->str);
            $this->str = preg_replace('#<code>(.*)</code>#Uis','<a href="###link###">Hier klicken um den Code zu sehen.</a> ', $this->str);
        }
        
        public function lists() {
            $this->str = preg_replace('#\[ul\](.*)\[/ul\]#Uis', ' $1 ', $this->str);
            $this->str = preg_replace('#\[ol\](.*)\[/ol\]#Uis', ' $1 ', $this->str);
            $this->str = preg_replace('#\[li\](.*)\[/li\]#Uis', ' $1', $this->str);
        }
        
        public function searchmarks() {
            $this->str = preg_replace('#\[mark\](.*)\[/mark\]#Uis', '<mark>$1</mark>', $this->str);
        }
        
        public function embedVideo() {
            $this->str = preg_replace('#\[yt\](.*)\[/yt\]#Ui', '<a href="###link###">Hier klicken um das Video zu sehen.</a>', $this->str);
            $this->str = preg_replace('#\[play\](.*)\[/play\]#Ui', '<a href="###link###">Hier klicken um das Video zu sehen.</a>', $this->str);
        }
    }
    
    //  DescriptionParser
    //      parsing routine for description, used in meta tags
    class DescriptionParser extends ArticleParser {
        public function __construct($input) {
            parent::__construct();
            $this->str = $input;
            $this->estimatedLength = 150;
        }
        public function parse() {
            parent::trimStr();
            parent::htmlSpecial();
            parent::removeEmptyTags();
            $this->code();
            parent::removeEmptySpace();
            parent::removeFormats();
            parent::removeHeadlines();
            $this->blockquotes();
            $this->lists();
            parent::breakLines();
            parent::removeBreaklines();
            parent::clearParagraphs();
            $this->embedVideo();
            parent::hideArticleImages();
            parent::removeAffiliateImage();
            parent::shorten('... Mehr im Blog!');
            return $this->str;
        }
        
        public function blockquotes() {
            $this->str = preg_replace('#\[bquote=(.*)\](.*)\[/bquote\]#Uis', '&quot;$2&quot;', $this->str);
        }
        
        public function code() {
            $this->str = preg_replace('#<code>(.*)</code>#Uis',' ', $this->str);
        }
        
        public function lists() {
            $this->str = preg_replace('#\[ul\](.*)\[/ul\]#Uis', ' $1 ', $this->str);
            $this->str = preg_replace('#\[ol\](.*)\[/ol\]#Uis', ' $1 ', $this->str);
            $this->str = preg_replace('#\[li\](.*)\[/li\]#Uis', ' $1 ', $this->str);
        }
        
        public function searchmarks() {
            $this->str = preg_replace('#\[mark\](.*)\[/mark\]#Uis', '$1', $this->str);
        }
        
        public function embedVideo() {
            $this->str = preg_replace('#\[yt\](.*)\[/yt\]#Ui', '', $this->str);
            $this->str = preg_replace('#\[play\](.*)\[/play\]#Ui', '', $this->str);
        }
    }
    
    //  EditParser
    //      parsing routine for the edit view in admin panel
    class EditParser extends ArticleParser {
        public function __construct($input) {
            parent::__construct();
            $this->str = $input;
        }
        public function parse() {
            parent::trimStr();
            return $this->str;
        }
        
        public function blockquotes() {}        
        public function lists() {}
        public function searchmarks() {}        
        public function embedVideo() {}
    }    
    
    //  NewParser
    //      parsing routine for the new view in admin panel
    class NewParser extends ArticleParser {
        public function __construct($input) {
            parent::__construct();
            $this->str = $input;
        }
        public function parse() {
            parent::trimStr();
            return $this->str;
        }
        
        public function blockquotes() {}
        public function lists() {}        
        public function searchMarks() {}        
        public function embedVideo() {}
    }
    
    //  ContentParser
    //      parsing routine for main article view
    class ContentParser extends ArticleParser {
        public function __construct($input, $mobile) {
            parent::__construct();
            $this->str = $input;
            $this->mobile = $mobile;
        }
        public function parse() {
            parent::trimStr();
            parent::htmlSpecial();
            parent::removeEmptyTags();
            parent::codeFirstPass();
            parent::removeEmptySpace();
            parent::textFormats();
            parent::headlines();
            $this->blockquotes();
            parent::cites();
            parent::links();
            $this->lists();
            parent::breakLines();
            parent::illustrateSmiles();
            parent::codeSecondPass();
            $this->embedVideo();
            parent::paragraphs();
            parent::appendQuoteSources();
            parent::affiliateImage();
            return $this->str;
        }
        
        public function blockquotes() {
            parent::collectQuotes();
            $this->str = preg_replace('#\[bquote=(.*)\](.*)\[/bquote\]#Uis', '</p><blockquote cite="$1">$2<br><span class="source">Quelle: $1</span></blockquote><p>', $this->str);
        }
        
        public function lists() {
            $this->str = preg_replace('#\[ul\](.*)\[/ul\]#Uis', '</p><ul class="innews">$1</ul><p>', $this->str);
            $this->str = preg_replace('#\[ol\](.*)\[/ol\]#Uis', '</p><ol class="innews">$1</ol><p>', $this->str);
            $this->str = preg_replace('#\[li\](.*)\[/li\]#Uis', '<li>$1</li>', $this->str);
        }
        
        public function searchMarks() {}
        
        public function embedVideo() {
            $this->str = preg_replace('#\[yt\](.*?)\[/yt\]#Ui', '<iframe class="embeddedVideo video" width="560" height="315"  src="https://www.youtube.com/embed/$1?wmode=transparent" frameborder="0" wmode="Opaque" allowfullscreen></iframe><p class="embeddedVideo link">Dein Browser ist zu klein, für den eingebetteten Player. Du kannst das Video aber <a href="http://www.youtube.com/watch?v=$1">hier auf YouTube</a> ansehen.</p>', $this->str);
            $this->str = preg_replace('#\[play\](.*?)\[/play\]#Ui', '<iframe class="embeddedVideo video" width="560" height="315"  src="https://www.youtube.com/embed/$1?wmode=transparent" frameborder="0" wmode="Opaque" allowfullscreen></iframe><p class="embeddedVideo link">Dein Browser ist zu klein, für den eingebetteten Player. Du kannst das Video aber <a href="http://www.youtube.com/playlist?list=$1">hier auf YouTube</a> ansehen.</p></p>', $this->str);
        }
    }
    
    //  CommentParser
    //      parsing routine for all comments
    class CommentParser extends ArticleParser {
        public function __construct($input, $mobile) {
            parent::__construct();
            $this->str = $input;
        }
        public function parse() {
            parent::trimStr();
            parent::htmlSpecial();
            parent::removeEmptyTags();
            $this->code();
            parent::removeEmptySpace();
            parent::textFormats();
            $this->blockquotes();
            parent::cites();
            parent::links();
            $this->lists();
            parent::breakLines();
            parent::paragraphs();
            $this->embedVideo();
            parent::illustrateSmiles();
            parent::removeAffiliateImage();
            return $this->str;
        }
        
        public function blockquotes() {
            $this->str = preg_replace('#\[bquote=(.*)\](.*)\[/bquote\]#Uis', '&quot;$2&quot;', $this->str);
        }
        
        public function code() {
            $this->str = preg_replace('#<code>(.*)</code>#Uis',' ', $this->str);
        }
        
        public function lists() {
            $this->str = preg_replace('#\[ul\](.*)\[/ul\]#Uis', ' $1 ', $this->str);
            $this->str = preg_replace('#\[ol\](.*)\[/ol\]#Uis', ' $1 ', $this->str);
            $this->str = preg_replace('#\[li\](.*)\[/li\]#Uis', ' $1 ', $this->str);
        }
        
        public function searchMarks() {}
        
        public function embedVideo() {
            $this->str = preg_replace('#\[yt\](.*)\[/yt\]#Ui', '<a href="http://youtu.be/$1">Video ansehen</a> ', $this->str);
        }
    }
?>