<?php

  class Matcher {
    public static function isNewLine($char) {
      return $char === "\n";
    }

    public static function isTagEnd($char) {
      return $char === ']';
    }

    public static function isTagStart($char) {
      return $char === '[';
    }
  }

?>
