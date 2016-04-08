<?php

  class Tokenizer {
    private $tokens;

    private $current_index;
    private $current_length;
    private $current_phrase;
    private $current_type;

    public function __construct($article) {
      $this->article  = $article;
      $this->tokens   = new SplDoublyLinkedList();

      $this->current_index  = 0;
      $this->current_length = strlen($this->article->getContent());
      $this->current_phrase = '';
      $this->current_type   = Token::CONTENT;
    }

    /**
     * getter for tokens
     * @return SplDoublyLinkedList
     */
    public function getTokens() { return $this->tokens; }

    public function run() {
      while ($this->current_index < $this->current_length) {
        $char = substr($this->article->getContent(), $this->current_index, 1);

        switch ($char) {
          case Matcher::isNewLine($char) :
            $this->tokens->push( new Token($this->current_phrase, $this->current_type) );
            $this->tokens->push( new Token($char, Token::NEWLINE) );

            $this->current_phrase = '';
            $this->current_type   = Token::CONTENT;

            break;

          case Matcher::isTagStart($char) :
            $this->tokens->push( new Token($this->current_phrase, $this->current_type) );

            $this->current_phrase = $char;
            $this->current_type   = Token::TAG;

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
      $this->tokens->push( new Token($this->current_phrase, $this->current_type) );
    }
  }

?>
