<?php

  abstract class AbstractValidator {

    private $errors = array();
    private $messages = array();

    public function addError($field_name, $message) {
      $this->errors[$field_name] = $message;
    }

    public function addMessage($message) {
      $this->messages[] = $message;
    }

    public function getErrors() {
      return $this->errors;
    }

    public function getMessages() {
      return $this->messages;
    }

    public function hasErrors() {
      return !empty($this->errors);
    }

    public function hasMessages() {
      return !empty($this->messages);
    }

    public abstract function isValid();
  }

?>