<?php

  include('settings/config.php');
  include('settings/functions.php');
  include('user/local.php');

  function __autoload($class) {
    include_once 'classes/'.$class.'.php';
  }

  $article_id = 16;
  $article    = new Article($article_id);

  // $raw_string = $article->getContent();
  $raw_string = "a[ h2 ]a";

  $tokenizer = new Tokenizer( $raw_string );
  $tokenizer_success = $tokenizer->run();

  display();

  function display() {
    global $raw_string, $tokenizer, $tokenizer_success;

    # raw text
    echo '<h2>Raw text</h2>';
    echo '<pre>'; print_r($raw_string); echo '</pre>';

    # token list
    echo '<h2>Token list</h2>';

    if ($tokenizer_success) {
      echo '<pre>'; print_r($tokenizer->getTokens()); echo '</pre>';
    } else {
      echo '<pre>'; print_r($tokenizer->getError()); echo '</pre>';
    }
  }

?>
