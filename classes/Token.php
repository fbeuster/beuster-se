<?php

  class Token {
    const TAG     = 0;
    const NEWLINE = 1;
    const CONTENT = 2;

    private $text;
    private $type;

    public function __construct($text, $type) {
      $this->text = $text;
      $this->type = $type;
    }

    /**
     * getter for text
     * @return String
     */
    public function getText() { return $this->text; }

    /**
     * getter for type
     * @return int
     */
    public function getType() { return $this->type; }
  }

?>
