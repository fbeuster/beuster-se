<?php

/**
 * Text parser.
 * \file settings/parser.php
 *
 *
 * Future article syntax:
 *
 * article ::= tag*
 *
 * tag ::= block_tag | format_tag | list_tag
 *
 * block_tag      ::= open_block_tag (format_tag | text) close_block_tag
 * open_block_tag ::= '[' block_tag_name ']'
 * open_block_tag ::= '[' '/' block_tag_name ']'
 * block_tag_name ::= 'h2' | 'h3' | 'p'
 *
 * format_tag ::= simple_format_tag | extended_format_tag
 *
 * simple_format_tag       ::= open_simple_format_tag (format_tag | text) close_simple_format_tag
 * open_simple_format_tag  ::= '[' simple_format_tag_name ']'
 * close_simple_format_tag ::= '[' '/' simple_format_tag_name ']'
 * simple_format_tag_name  ::= 'b' | 'code' | 'del' | 'i' | 'ins' | 'mark' | 'play' | 'quote' | 'u' | 'url' | 'yt'
 *
 * extended_format_tag       ::= open_extended_format_tag (format_tag | text) close_extended_format_tag
 * open_extended_format_tag  ::= '[' extended_format_tag_name '=' parameter ']'
 * close_extended_format_tag ::= '[' '/' extended_format_tag_name ']'
 * extended_format_tag_name  ::=  'asin' | 'bquote' | 'cite' | 'url'
 * parameter                 ::= '^]' '^]'*
 *
 * list_tag       ::= open_list_tag (list_item_tag list_item_tag*) close_list_tag
 * open_list_tag  ::= '[' ('ul' | 'ol') ']'
 * list_item_tag  ::= '[' 'li' ']' (format_tag | list_tag | text) '[' '/' 'li' ']'
 * close_list_tag ::= '[' '/' ('ul' | 'ol') ']'
 *
 * text ::=  '^[' '^['*
 */

/**
* Parser class
* \class Parser
* \author Felix Beuster
*
* Static access to parser classes
*/
class Parser {
    const DEFAULT_PREVIEW_LENGTH = 750;

    const TYPE_COMMENT  = 1;
    const TYPE_CONTENT  = 2;
    const TYPE_DESC     = 3;
    const TYPE_EDIT     = 4;
    const TYPE_NEW      = 5;
    const TYPE_PREVIEW  = 6;

    /**
     * Parser access.
     *
     * @param String $string the text to parse
     * @param String $parser_type which parser used?
     * @param int $length the length, used for preview; default = 750
     * @return String
     */
    public static function parse($string, $parser_type,
                            $length = Parser::DEFAULT_PREVIEW_LENGTH) {
        switch($parser_type) {
            case self::TYPE_COMMENT:
                $parsed = new CommentParser($string);
                break;
            case self::TYPE_CONTENT:
                $parsed = new ContentParser($string);
                break;
            case self::TYPE_EDIT:
                $parsed = new EditParser($string);
                break;
            case self::TYPE_DESC:
                $parsed = new DescriptionParser($string);
                break;
            case self::TYPE_NEW:
                $parsed = new NewParser($string);
                break;
            case self::TYPE_PREVIEW:
                $parsed = new PreviewParser($string, $length);
                break;
            default:
                $parsed = new PreviewParser($string, $length);
                break;
        }
        return $parsed->parse();
    }
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
        $this->str = preg_replace('#\[mark\](.+?)\[/mark\]#', '<mark>$1</mark>', $this->str);
        $this->str = preg_replace('#\[address\](.+?)\[/address\]#Uis', '<address>$1</address>', $this->str);
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
        $this->str = preg_replace('#\[mark\](.+?)\[/mark\]#', '$1', $this->str);
        $this->str = preg_replace('#\[address\](.+?)\[/address\]#', '$1', $this->str);
        $this->str = preg_replace('=&amp;=is', '&', $this->str);
        $this->str = preg_replace('=\[quote\](.*)\[/quote\]=Uis', '&quot;$1&quot;', $this->str);
        $this->str = preg_replace('=\[cite\](.*)\[/cite\]=Uis', '&quot;$1&quot;', $this->str);
        $this->str = preg_replace('#\[cite=(.*)\](.*)\[/cite\]#Uis', '&quot;$2&quot;', $this->str);
        $this->str = preg_replace('#\[url\](.*)\[/url\]#Uis', '$1 ', $this->str);
        $this->str = preg_replace('#\[url=(.*)\](.*)\[/url\]#Uis', '$2 ', $this->str);
    }

    /**
     * Format lists
     *
     * Formatting (nested) ordered and unordered list.
     *
     * \todo Very naive implementation, needs improvement in future parse re-do
     */
    public function lists() {
        $this->str = preg_replace('#\[(/)?(u|o)l\]#Uis', '<$1$2l>', $this->str);
        $this->str = preg_replace('#\[(/)?li\]#Uis', '<$1li>', $this->str);
    }

    /**
     * Remove list formats
     *
     * Removes all list formatting.
     */
    public function removeLists() {
        $this->str = preg_replace('#\[ul\](.*)\[/ul\]#Uis', ' $1 ', $this->str);
        $this->str = preg_replace('#\[ol\](.*)\[/ol\]#Uis', ' $1 ', $this->str);
        $this->str = preg_replace('#\[li\](.*)\[/li\]#Uis', ' $1', $this->str);
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
        $this->str = preg_replace('#<(u|o)l><br />#', '<$1l>', $this->str);
        $this->str = preg_replace('#</(li|ul|ol)><br />#', '</$1>', $this->str);
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
        $this->str = preg_replace('=<p><h2>(.*?)</h2>=Ui', '<h2>$1</h2><p>', $this->str);
        $this->str = preg_replace('=<p><h3>(.*?)</h3>=Ui', '<h3>$1</h3><p>', $this->str);
        $this->str = preg_replace('#<p>\s*<ol>([\w\W]*?)</ol>\s*</p>#', '<ol>$1</ol>', $this->str);
        $this->str = preg_replace('#<p>\s*<ul>([\w\W]*?)</ul>\s*</p>#', '<ul>$1</ul>', $this->str);
        $this->str = preg_replace('#<p>\s*<ol>([\w\W]*?)</ol>\s*$#', '<ol>$1</ol>', $this->str);
        $this->str = preg_replace('#<p>\s*<ul>([\w\W]*?)</ul>\s*$#', '<ul>$1</ul>', $this->str);
        $this->str = preg_replace('#<p>\s*<div([\w\W]*?)</div>\s*</p>#', '<div$1</div>', $this->str);
        $this->str = preg_replace('=<p>\s*</p>=Ui', '', $this->str);
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
        # affiliate tag
        $tag = Config::getConfig()->get('amazon_tag');

        # old style
        $this->str = preg_replace('#\[affi=(.*)\]#Uis', '<img src="$1" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />', $this->str);

        # new style
        preg_match_all('/\[asin=(.+?)\](.+?)\[\/asin\]/', $this->str, $asins, PREG_PATTERN_ORDER);
        foreach($asins[1] as $k => $asin) {
            $pattern    = $asins[0][$k];
            $text       = $asins[2][$k];

            if ($tag === null || $tag === '') {
                $href       = 'http://www.amazon.de/gp/product/'.$asin;
                $replace    = '<a href="'.$href.'">'.$text.'</a>';

            } else {
                $href       = 'http://www.amazon.de/gp/product/'.$asin;
                $href       .= '/ref=as_li_ss_tl?ie=UTF8&camp=1638&creative=19454&creativeASIN=';
                $href       .= $asin.'&linkCode=as2&tag='.$tag;
                $replace    = '<a href="'.$href.'">'.$text.' *</a>';

                $src        = 'http://ir-de.amazon-adsystem.com/e/ir?t='.$tag.'&l=as2&o=3&a='.$asin;
                $replace    .= '<img src="'.$src.'" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />';
            }

            $this->str = str_replace($pattern, $replace, $this->str);
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

    abstract function blockquotes();    /**< abstract, implementation in sub class */
    abstract function parse();  /**< abstract, implementation in sub class */
    abstract function embedVideo(); /**< abstract, implementation in sub class */
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
     * @param int $length length to cut off
     */
    public function __construct($input, $length) {
        parent::__construct();
        $this->str = $input;
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
        parent::removeLists();
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
        parent::removeLists();
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
     */
    public function __construct($input) {
        parent::__construct();
        $this->str = $input;
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
        parent::lists();
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

    public function embedVideo() {
        $pre_wrap   = '<div class="video"><div class="wrapper">';
        $post_wrap  = '</div></div>';

        $iframe = '<iframe class="embeddedVideo video" width="560" height="315"  src="https://www.youtube.com/embed/$1?wmode=transparent" frameborder="0" wmode="Opaque" allowfullscreen></iframe>';

        $p_note = '<p class="embeddedVideo link">Dein Browser ist zu klein, für den eingebetteten Player. Du kannst das Video aber <a href="http://www.youtube.com/playlist?list=$1">hier auf YouTube</a> ansehen.</p>';
        $v_note = '<p class="embeddedVideo link">Dein Browser ist zu klein, für den eingebetteten Player. Du kannst das Video aber <a href="http://www.youtube.com/watch?v=$1">hier auf YouTube</a> ansehen.</p>';

        $this->str = preg_replace('#\[yt\](.*?)\[/yt\]#Ui', $pre_wrap.$iframe.$v_note.$post_wrap, $this->str);
        $this->str = preg_replace('#\[play\](.*?)\[/play\]#Ui', $pre_wrap.$iframe.$p_note.$post_wrap, $this->str);
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
     */
    public function __construct($input) {
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
        parent::removeLists();
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

    public function embedVideo() {
        $this->str = preg_replace('#\[yt\](.*)\[/yt\]#Ui', '<a href="http://youtu.be/$1">Video ansehen</a> ', $this->str);
    }
}
?>