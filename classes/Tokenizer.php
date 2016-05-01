<?php

  class Tokenizer {
    private $raw_string;
    private $tokens;

    private $current_error;
    private $current_index;
    private $current_length;
    private $current_phrase;
    private $current_type;

    public function __construct($raw_string) {
      $this->raw_string = $raw_string;
      $this->tokens     = new SplDoublyLinkedList();

      $this->current_error  = -1;
      $this->current_index  = 0;
      $this->current_length = strlen($this->raw_string);
      $this->current_phrase = '';
      $this->current_type   = Token::CONTENT;
    }

    public function getError() {
      return new ParserError($this->current_index, $this->raw_string, $this->current_error);
    }

    /**
     * getter for tokens
     * @return SplDoublyLinkedList
     */
    public function getTokens() { return $this->tokens; }

    private function hasPhrase() {
      return $this->current_phrase !== '';
    }

    public function run() {
      while ($this->current_index < $this->current_length) {
        $char = substr($this->raw_string, $this->current_index, 1);

        switch ($char) {
          case Matcher::isNewLine($char) :
            if ($this->current_type == Token::TAG) {
              $this->current_phrase .= $char;

            } else {
              if ($this->hasPhrase()) {
                $this->tokens->push( new Token($this->current_phrase, $this->current_type) );
              }
              $this->tokens->push( new Token($char, Token::NEWLINE) );

              $this->current_phrase = '';
              $this->current_type   = Token::CONTENT;
            }

            break;

          case Matcher::isTagStart($char) :
            if ($this->current_type == Token::TAG) {
              $this->current_error = ParserError::TYPE_TAG_IN_TAG;
              return false;

            } else  {
              if ($this->hasPhrase()) {
                $this->tokens->push( new Token($this->current_phrase, $this->current_type) );
              }

              $this->current_phrase = $char;
              $this->current_type   = Token::TAG;
            }
            break;

          case Matcher::isTagEnd($char) :
            $this->tokens->push( new Token($this->current_phrase . $char, $this->current_type) );

            $this->current_phrase = '';
            $this->current_type   = Token::CONTENT;

            break;

          default :
            $this->current_phrase .= $char;
            break;
        }

        $this->current_index++;
      }

      # adding last phrase
      if ($this->hasPhrase()) {
        $this->tokens->push( new Token($this->current_phrase, $this->current_type) );
      }

      return true;
    }
  }

?>
