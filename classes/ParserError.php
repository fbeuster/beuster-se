<?php

  class ParserError {
    const TYPE_TAG_IN_TAG = 'errors.parser.token.tag_in_tag';

    private $index;
    private $raw_string;
    private $type;

    public function __construct($index, $raw_string, $type) {
      $this->index      = $index;
      $this->raw_string = $raw_string;
      $this->type       = $type;
    }
  }

?>