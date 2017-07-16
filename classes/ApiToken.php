<?php

  class ApiToken {

    public function __construct($set_cookie = false) {
      do {
        $this->generateTokenString();
      } while (self::exists($this->token_string));

      $this->saveToken($set_cookie);
    }

    public static function delete($token_string) {
      $conds  = array('token = ?', 's', array($token_string));
      $res    = Database::getDB()->delete('api_tokens', $conds);

      setcookie('api_token', null, -1, '/');
      unset($_COOKIE['api_token']);
    }

    public static function exists($token_string) {
      $fields = array('date');
      $conds  = array('token = ?', 's', array($token_string));
      $res    = Database::getDB()->select('api_tokens', $fields, $conds);

      return count($res) == 1;
    }

    private function generateTokenString() {
      $random     = rand();
      $timestamp  = time();
      $this->token_string = hash('sha512', $timestamp . $random);
    }

    public function getString() {
      return $this->token_string;
    }

    public static function isValid($token_string) {
      $db = Database::getDB();
      $fields = array('date');
      $conds  = array('token = ? AND TIMESTAMPDIFF(SECOND, date, NOW()) < 86400', 's', array($token_string));
      $res    = $db->select('api_tokens', $fields, $conds);

      return count($res) == 1;
    }

    private function saveToken($set_cookie) {
      $fields = array('token');
      $values = array('s', array($this->token_string));
      $id     = Database::getDB()->insert('api_tokens', $fields, $values);

      if ($set_cookie) {
        setcookie('api_token', $this->token_string, strtotime("+1 day"), '/');

        $_COOKIE['api_token'] = $this->token_string;
      }
    }
  }

?>
