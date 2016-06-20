<?php

  class Snippet {

    private $content;
    private $loaded;
    private $name;

    public function __construct($name) {
      $this->name = $name;

      $this->load();
    }

    public function getContent($lang) {
      if (array_key_exists($lang, $this->content)) {
        return $this->content[$lang];

      } else {
        return $this->content['en'];
      }
    }

    public function getContentParsed($lang) {
      return Parser::parse($this->getContent($lang), Parser::TYPE_CONTENT);
    }

    public function isLoaded() {
      return $this->loaded;
    }

    private function load() {
      $db = Database::getDB();

      $fields = array('content_de', 'content_en');
      $conds  = array('name = ?', 's', array($this->name));
      $res    = $db->select('snippets', $fields, $conds);

      if(count($res) != 1) {
        $this->loaded = false;

      } else {
        $this->content = array( 'de' => $res[0]['content_de'],
                                'en' => $res[0]['content_en']);
        $this->loaded = true;
      }
    }
  }

?>