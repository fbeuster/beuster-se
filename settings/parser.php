<?php

/**
 * Text parser.
 * \file settings/parser.php
 */

/**
 * Parser access.
 * 
 * This parser access is used to support old code. In fact it just call the
 * desired ArticleParser.
 * 
 * @param String $str the text to parse
 * @param String $type which parser used?
 * @param bool $mob are we on mobile?
 * @param int $l the length, used for preview; default = 750
 * @return String
 */
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

/**
 * Abstract class to parse articles.
 * \class ArticleParser
 * \author Felix Beuster
 * 
 * Implementation of most parsing functions
 */
abstract class ArticleParser {

    /** the parsing content */
    public $str = '';

    /** indicator for mobile */
    public $mobile = false;

    /** storing source from cites */
    public $citeSources = array();

    /** estimated length, used in shorten() */
    public $estimatedLength = 0;
    
    /**
     * constructor
     */
    public function __construct() {
    }
    
    /**
     * Cut spaces.
     * 
     * This simply cut off the spaces vom the string by peforming trim().
     */
    public function trimStr() {
        $this->str = trim($this->str);
    }
    
    /**
     * HTML special characters.
     * 
     * Encoding special HTML characters
     */
    public function htmlSpecial() {
        $this->str = htmlspecialchars($this->str);
        $this->str = stripslashes($this->str);   
    }
    
    /**
     * Parse code blocks.
     * 
     * Parsing code blocks.
     * This adds line numbers and different classes for each line.
     */
    public function parseCode() {
        while(strpos($this->str, '[code]') !== false) {
            $strCode = '';
            $posA = strpos($this->str, '[code]');
            $posE = strpos($this->str, '[/code]');
            
            $strCode = substr($this->str, $posA + 6, $posE - ($posA + 6));
            $strCodeAlt = substr($this->str, $posA, $posE - $posA + 7);

            $lines = array();
            $i = 1;
            foreach(preg_split("/((\r?\n)|(\r\n?))/", $strCode) as $line){
                $class = 'line';
                if($i%2==0)
                    $class .= ' even';
                else
                    $class .= ' odd';
                $line = highlight_string($line, true);
                $line = preg_replace('+<span style="color: #([a-f0-9]{6})">(.*?)</span>+Uis', '\2', $line);
                $line = preg_replace('+<code>(.*?)</code>+Uis', '\1', $line);
                $line = preg_replace('#&amp;#Uis', '&', $line);
                $line = preg_replace('#\n#Uis', '', $line);
                $lines[] = '<code class="'.$class.'"><span class="no">'.$i.'</span>'.$line.'</code>';
                $i++;
            }
            $strCode = implode($lines);
            
            $this->str = str_replace($strCodeAlt, '<div class="code">'.$strCode.'</div>', $this->str);
        }
    }
    
    /**
     * Shrink down whitespace.
     * 
     * Removing multiple spaces an replace two line break by a paragraph change
     */
    public function removeEmptySpace() {
        $this->str = preg_replace('/(\r\n){2}/', '[/p][p]', $this->str);
        $this->str = preg_replace('/(\s{2})\s+/', '\1', $this->str);
    }
    
    /**
     * Remove empty tags.
     * 
     * tags without content are not needed in HTML later
     * 
     * \todo check if tags are missing
     */
    public function removeEmptyTags() {
        $this->str = preg_replace('#\[b\]\[/b\]#', '', $this->str);
        $this->str = preg_replace('#\[u\]\[/u\]#', '', $this->str);
        $this->str = preg_replace('#\[i\]\[/i\]#', '', $this->str);
        $this->str = preg_replace('#\[del\]\[/del\]#', '', $this->str);
        $this->str = preg_replace('#\[ins\]\[/ins\]#', '', $this->str);
        $this->str = preg_replace('#\[h2\]\[/h2\]#', '', $this->str);            
        $this->str = preg_replace('#\[h3\]\[/h3\]#', '', $this->str);              
    }
    
    /**
     * Format text.
     * 
     * Translate tags for bold, italic, underline, delete and insert into HTML tags.
     */
    public function textFormats() {
        $this->str = preg_replace('#\[b\](.+?)\[/b\]#', '<b>$1</b>', $this->str);
        $this->str = preg_replace('#\[u\](.+?)\[/u\]#', '<u>$1</u>', $this->str);
        $this->str = preg_replace('#\[i\](.+?)\[/i\]#', '<i>$1</i>', $this->str);
        $this->str = preg_replace('#\[del\](.+?)\[/del\]#', '<del>$1</del>', $this->str);
        $this->str = preg_replace('#\[ins\](.+?)\[/ins\]#', '<ins>$1</ins>', $this->str);
        $this->str = preg_replace('=&amp;=is', '&', $this->str);
    }
    
    /**
     * Format headlines.
     * 
     * Translate tags for headlines into HTML tags
     */
    public function headlines() {
        $this->str = preg_replace('#\[h2\](.+?)\[/h2\]#', '<h2>$1</h2>', $this->str);            
        $this->str = preg_replace('#\[h3\](.+?)\[/h3\]#', '<h3>$1</h3>', $this->str);         
    }
    
    /**
     * Remove headlines.
     * 
     * Removes headlines and deletes new extra space
     */
    public function removeHeadlines() {
        $this->str = preg_replace('#\[h2\](.+?)\[/h2\]#', '<b>$1</b>', $this->str);            
        $this->str = preg_replace('#\[h3\](.+?)\[/h3\]#', '<b>$1</b>', $this->str);
        $this->str = preg_replace('/\r\n/', ' ', $this->str);
    }
    
    /**
     * Remove text formats.
     * 
     * Removing text formats such as bold, underline, italic, delete, insert as
     * well as quote, cite and urls. Content of the elements is untouched.
     */
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
    
    /**
     * Collect sources.
     * 
     * Sources from quotes and cites are stored in member $citeSources
     */
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
    
    /**
     * Formate citations.
     * 
     * Translate tags for cite and blockquote into HTML
     */
    public function cites() {
        $this->str = preg_replace('=\[quote\](.*)\[/quote\]=Uis', '&quot;$1&quot;', $this->str);
        $this->str = preg_replace('=\[cite\](.*)\[/cite\]=Uis', '<cite>$1</cite>', $this->str);
        $this->str = preg_replace('#\[cite=(.*)\](.*)\[/cite\]#Uis', '<cite title="$1">$2</cite>', $this->str);
    }
    
    /**
     * Link formatting.
     * 
     * Translate url tags into HTML
     */
    public function links() {
        $this->str = preg_replace('#\[url\](.*)\[/url\]#Uis', '<a href="$1">$1</a>', $this->str);
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
    
    /**
     * Remove breaklines.
     * 
     * Removes breaklines after certain tags which are not needed.
     */
    public function breakLines() {
        $this->str = nl2br($this->str);
        $this->str = preg_replace('#<p><br />#', '<p>', $this->str);
        $this->str = preg_replace('#</p><br />#', '</p>', $this->str);
        $this->str = preg_replace('#</h2><br />#', '</h2>', $this->str);
        $this->str = preg_replace('#</h3><br />#', '</h3>', $this->str);
        $this->str = preg_replace('#</(li|ul|ol)><br />#', '</$1>', $this->str);
        $this->str = preg_replace('#<(u|o)l class="innews((vor)??)"><br />#', '<$1l class="innews$2">', $this->str);
    }
    
    /**
     * Remove breaklines.
     * 
     * Removes general breaklines.
     */
    public function removeBreakLines() {
        $this->str = preg_replace('#<br />#', ' ', $this->str);
        $this->str = preg_replace('#<br/>#', ' ', $this->str);
        $this->str = preg_replace('#<br>#', ' ', $this->str);
    }
    
    /**
     * Remove paragraphs.
     * 
     * Clear paragraphs and headlines by removing and translating.
     */
    public function clearParagraphs() {
        $this->str = preg_replace('=\[/p\]<h2>(.*?)</h2>\[p\]=Ui', '<b>$1</b>', $this->str);
        $this->str = preg_replace('=\[/p\]<h3>(.*?)</h3>\[p\]=Ui', '<b>$1</b>', $this->str);
        $this->str = preg_replace('=\[p\]=Ui', ' ', $this->str);
        $this->str = preg_replace('=\[/p\]=Ui', ' ', $this->str);
    }
    
    /**
     * Translate paragraphs.
     * 
     * This handles different cases of paragraphs and translate them into HTML
     */
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
    
    /**
     * Smile!.
     * 
     * Translate textual emojis into graphical ones.
     * 
     * \todo reactivate them, but not in [code]
     */
    public function illustrateSmiles() {
        /*$this->str = str_replace(':)', '<img class="sm" src="/images/smsmile.gif" alt="Keep smiling!">', $this->str);
        $this->str = str_replace(':D', '<img class="sm" src="/images/smlaugh.gif" alt="Laughing">', $this->str);
        $this->str = str_replace(':(', '<img class="sm" src="/images/smsad.gif" alt="I\'m sad.">', $this->str);
        $this->str = str_replace(';)', '<img class="sm" src="/images/smone.gif" alt="You know...">', $this->str);*/
    }
    
    /**
     * Append quote sources.
     * 
     * Appending sources from variable ArticleParser#$citeSources generated in
     * ArticleParser::collectQuotes
     */
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

    /**
     * Generate an affiliate link.
     * 
     * Generates an affiliate link and tracking image for Amazon partner
     * programm.
     * 
     * \todo extract personal tracking data
     * \todo check DB for old codes
     */
    public function affiliateImage() {
        // old style 
        $this->str = preg_replace('#\[affi=(.*)\]#Uis', '<img src="$1" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />', $this->str);

        // new style
        preg_match_all('/\[asin=(.+?)\](.+?)\[\/asin\]/', $this->str, $asins, PREG_PATTERN_ORDER);
        foreach($asins[1] as $k => $asin) {
            $pattern = $asins[0][$k];
            $text = $asins[2][$k].' *';
            
            $href1 = 'http://www.amazon.de/gp/product/';
            $href2 = '/ref=as_li_ss_tl?ie=UTF8&camp=1638&creative=19454&creativeASIN=';
            $href3 = '&linkCode=as2&tag=beustersede-21';
            $href = $href1.$asin.$href2.$asin.$href3;
            $asinL = '<a href="'.$href.'">'.$text.'</a>';

            $src = 'http://ir-de.amazon-adsystem.com/e/ir?t=beustersede-21&l=as2&o=3&a='.$asin;
            $asinI = '<img src="'.$src.'" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />';
            
            $this->str = str_replace($pattern, $asinL.$asinI, $this->str);
        }
    }

    /**
     * Remove affiliate link.
     * 
     * Removing affiliate links from the text.
     * 
     * \todo remove old after cleaning ArticleParser::affiliateImage
     */
    public function removeAffiliateImage() {
        // old style
        $this->str = preg_replace('#\[affi=(.*)\]#Uis', '', $this->str);

        // new style
        $this->str = preg_replace('#\[asin=.*\](.*)\[/asin\]#Uis', '$1', $this->str);
    }
    
    /**
     * Shorten parsing string.
     * 
     * In some cases it is usefull to cut the string at some point. This cutting
     * point is set via ArticleParser#$estimatedLength
     * 
     * @param String $message A text to append to the shortend string.
     */
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
                $smallest = min(
                    abs($this->estimatedLength - $toCloseOldPos),
                    abs($this->estimatedLength - $toClosePos),
                    abs($this->estimatedLength - $toCloseEndPos));
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
    
    /**
     * Remove images.
     * 
     * Just remove all images from ArticleParser#$str
     */
    public function hideArticleImages() {
        $this->str = preg_replace('=\[img([0-9]*)\]=Ui', '', $this->str);
    }
    
    abstract function blockquotes();    /**< abstract, implementation in sub class */.
    abstract function lists();  /**< abstract, implementation in sub class */.
    abstract function parse();  /**< abstract, implementation in sub class */.
    /**
     * abstract, implementation in sub class.
     * \todo make it non abstract
     */
    abstract function searchMarks();
    abstract function embedVideo(); /**< abstract, implementation in sub class */.
}

/**
 * Class to parse article previews.
 * \class PreviewParser
 * \author Felix Beuster
 * 
 * specific implementations of some functions for previews
 */
class PreviewParser extends ArticleParser {  

    /**
     * constructor
     * 
     * @param String $input Text to be parsed
     * @param bool $mobile mobile view?
     * @param int $length length to cut off
     */
    public function __construct($input, $mobile, $length) {
        parent::__construct();
        $this->str = $input;
        $this->mobile = $mobile;
        $this->estimatedLength = $length;
    }

    /**
     * Do parsing.
     * 
     * Runs the parsing by calling necessary functions from ArticleParser,
     * returns the preview text.
     * 
     * @return String
     */
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
    
    /**
     * Format blockquotes.
     * 
     * Translate blockquote to HTML, for previews <code>cite</code> is more
     * useful.
     */
    public function blockquotes() {
        $this->str = preg_replace('#\[bquote=(.*)\](.*)\[/bquote\]#Uis', '<cite title="$1">$2</cite>', $this->str);
    }
    
    /**
     * Format code.
     * 
     * For previews code shouldn't be shown, generate a article link instead.
     */
    public function code() {
        $this->str = preg_replace('#\[code\](.*)\[/code\]#Uis','<a href="###link###">Hier klicken um den Code zu sehen.</a> ', $this->str);
        $this->str = preg_replace('#<code>(.*)</code>#Uis','<a href="###link###">Hier klicken um den Code zu sehen.</a> ', $this->str);
    }
    
    /**
     * Format lists.
     * 
     * Flat list for preview text.
     */
    public function lists() {
        $this->str = preg_replace('#\[ul\](.*)\[/ul\]#Uis', ' $1 ', $this->str);
        $this->str = preg_replace('#\[ol\](.*)\[/ol\]#Uis', ' $1 ', $this->str);
        $this->str = preg_replace('#\[li\](.*)\[/li\]#Uis', ' $1', $this->str);
    }
    
    /**
     * Format search marks.
     * 
     * Adding mark tags for search queries
     */
    public function searchmarks() {
        $this->str = preg_replace('#\[mark\](.*)\[/mark\]#Uis', '<mark>$1</mark>', $this->str);
    }
    
    /**
     * Replace embedded video.
     * 
     * In oreview texts an embedded video is better replaces by a link.
     */
    public function embedVideo() {
        $this->str = preg_replace('#\[yt\](.*)\[/yt\]#Ui', '<a href="###link###">Hier klicken um das Video zu sehen.</a>', $this->str);
        $this->str = preg_replace('#\[play\](.*)\[/play\]#Ui', '<a href="###link###">Hier klicken um das Video zu sehen.</a>', $this->str);
    }
}

/**
 * Class to parse article description.
 * \class DescriptionParser
 * \author Felix Beuster
 * 
 * specific implementations of some functions for description
 */
class DescriptionParser extends ArticleParser {

    /**
     * constructor
     * 
     * @param string $input The text which need to be paresd
     */
    public function __construct($input) {
        parent::__construct();
        $this->str = $input;
        $this->estimatedLength = 150;
    }

    /**
     * Do parsing.
     * 
     * Runs the parsing by calling necessary functions from ArticleParser,
     * returns the description text.
     * 
     * @return String
     */
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

/**
 * Class to parse edit views.
 * \class EditParser
 * \author Felix Beuster
 * 
 * specific implementations of some functions for edit views
 */
class EditParser extends ArticleParser {

    /**
     * constructor
     * 
     * @param string $input The text which need to be paresd
     */
    public function __construct($input) {
        parent::__construct();
        $this->str = $input;
    }

    /**
     * Do parsing.
     * 
     * Runs the parsing by calling necessary functions from ArticleParser,
     * returns the raw text, just trimmed.
     * 
     * @return String
     */
    public function parse() {
        parent::trimStr();
        return $this->str;
    }
    
    public function blockquotes() {}        
    public function lists() {}
    public function searchmarks() {}        
    public function embedVideo() {}
}

/**
 * Class to parse new views.
 * \class NewParser
 * \author Felix Beuster
 * 
 * specific implementations of some functions for new views
 */
class NewParser extends ArticleParser {

    /**
     * constructor
     * 
     * @param string $input The text which need to be paresd
     */
    public function __construct($input) {
        parent::__construct();
        $this->str = $input;
    }

    /**
     * Do parsing.
     * 
     * Runs the parsing by calling necessary functions from ArticleParser,
     * returns the raw text, just trimmed.
     * 
     * @return String
     */
    public function parse() {
        parent::trimStr();
        return $this->str;
    }
    
    public function blockquotes() {}
    public function lists() {}        
    public function searchMarks() {}        
    public function embedVideo() {}
}

/**
 * Class to parse article main view.
 * \class ContentParser
 * \author Felix Beuster
 * 
 * specific implementations of some functions for main view
 */
class ContentParser extends ArticleParser {

    /**
     * constructor
     * 
     * @param string $input The text which need to be paresd
     * @param bool $mobile Are we on a mobile device?
     */
    public function __construct($input, $mobile) {
        parent::__construct();
        $this->str = $input;
        $this->mobile = $mobile;
    }

    /**
     * Do parsing.
     * 
     * Runs the parsing by calling necessary functions from ArticleParser,
     * returns the content text.
     * 
     * @return String
     */
    public function parse() {
        parent::trimStr();
        parent::htmlSpecial();
        parent::removeEmptyTags();
        parent::parseCode();
        parent::removeEmptySpace();
        parent::textFormats();
        parent::headlines();
        $this->blockquotes();
        parent::cites();
        parent::links();
        $this->lists();
        parent::breakLines();
        parent::illustrateSmiles();
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

/**
 * Class to parse comments.
 * \class CommentParser
 * \author Felix Beuster
 * 
 * specific implementations of some functions for comments
 */
class CommentParser extends ArticleParser {

    /**
     * constructor
     * 
     * @param string $input The text which need to be paresd
     * @param bool $mobile Are we on a mobile device?
     */
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