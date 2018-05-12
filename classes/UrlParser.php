<?php
    include('../settings/functions.php');
    include('../user/local.php');
    include('Database.php');
    include('Utilities.php');

    $parsingUrl = new UrlParser('/#id#/#cat#/#title#', '/#id#/#cat#');
    $parsedUrl  = $parsingUrl->doParse();

    # redirect
    echo 'Location: '.Utilities::getProtocol().'://'.Utilities::getSystemAddress().$parsedUrl;
    header('Location: '.Utilities::getProtocol().'://'.Utilities::getSystemAddress().$parsedUrl);

    /* This class parse in Url in my old format and transfer it
     * to the new one. Categories and titles are important,
     * all other parameter are ignored in .htaccess.
     * The new scheme for article urls and category urls are given
     * through the constructor.
     * (c) 2012-2016, Felix Beuster
     */
    class UrlParser {

        /* private attributes */

        private $title           = -1;
        private $cat             = -1;
        private $catId           = -1;
        private $articleId       = -1;

        private $parsedUrl       = '';
        private $requestUrl      = '';
        private $requestUrlParts = '';

        private $titleScheme     = '';
        private $categorySheme   = '';

        /* constructor */

        public function __construct($titleScheme, $catScheme) {
            $this->requestUrl     = $_SERVER['REQUEST_URI'];
            $this->titleScheme    = $titleScheme;
            $this->categoryScheme = $catScheme;
            $this->db = Database::getDB()->getCon();
        }

        /* public functions */

        public function doParse() {

            $this->requestUrl = substr($this->requestUrl, 1, strlen($this->requestUrl) - 1);
            $this->requestUrlParts = explode('/', $this->requestUrl);

            $this->cat = $this->requestUrlParts[0];

            if(count($this->requestUrlParts) == 3 ||
               (count($this->requestUrlParts) == 2 &&
                !preg_match('#^(page)#', $this->requestUrlParts[1]))) {

                $this->title = $this->requestUrlParts[1];
            }

            if($this->title !== -1) {
                $this->parseTitleUrl();
            } else {
                $this->parseCategoryUrl();
            }
            return $this->parsedUrl;
        }

        /* private functions */

        // get and set the title based on url
        private function parseTitleUrl() {
            $posId = strpos($this->title, '-') + 1;
            $this->title = substr($this->title,  $posId, strlen($this->title) - $posId);
            $this->title = $this->smoothenTitle($this->title);

            $sql = "SELECT
                        id,
                        title
                    FROM
                        articles";
            if(!$stmt = $this->db->prepare($sql)){return $this->db->error;}
            if(!$stmt->execute()) {return $result->error;}
            $stmt->bind_result($id, $title);
            while($stmt->fetch()) {
                if($this->title == $this->smoothenTitle($title)) {
                    $this->articleId = $id;
                    break;
                }
            }
            $stmt->close();
            if($this->articleId !== -1) {
                $this->parsedUrl = '/'.$this->articleId.'/'.$this->cat.'/'.$this->title;
            }
        }

        private function smoothenTitle($title) {
            $removes = '#?|().,;:{}[]/';
            $strokes = array(' ', '---', '--');

            for($i = 0; $i < strlen($removes); $i++) {
              $title = str_replace($removes[$i], '', $title);
            }

            foreach($strokes as $char) {
              $title = str_replace($char, '-', $title);
            }
            return LinkBuilder::replaceUmlaute($title);
        }

        // get and set the category based on url
        private function parseCategoryUrl() {
            $sql = "SELECT
                        id,
                        name
                    FROM
                        categories";
            if(!$stmt = $this->db->prepare($sql)){return $this->db->error;}
            if(!$stmt->execute()) {return $result->error;}
            $stmt->bind_result($id, $name);
            while($stmt->fetch()) {
                $name = lowerCat($name);
                if($this->cat == $name) {
                    $this->catId = $id;
                    break;
                }
            }
            $stmt->close();
            if($this->catId !== -1) {
                $this->parsedUrl = '/'.$this->catId.'/'.$this->cat;
            }
        }

        // get the category of the article
        private function getCategory() {
            return '';
        }

        // get the category id
        private function getCategoryID() {
            return 0;
        }
    }
?>