<?php

  class Matcher {
    public static function isNewLine($char) {
      return $char === "\n" || $char === "\r";
    }

    # TODO: rename
    public static function isTagEnd($char) {
      return $char === ']';
    }

    # TODO: rename
    public static function isTagStart($char) {
      return $char === '[';
    }
  }

?>
