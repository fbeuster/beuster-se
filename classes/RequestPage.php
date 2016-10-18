<?php

abstract class RequestPage extends Page {

  const METHOD_GET  = 1;
  const METHOD_POST = 2;

  protected $expected_method;
  protected $method;

  public function getRequestMethod() {
    return $this->method;
  }

  abstract protected function handleRequest();

  public function hasValidRequest() {
    return $this->method === $this->expected_method;
  }

  protected function setExpectedRequestMethod($method) {
    $this->expected_method = $method;
  }

  protected function setRequestMethod($method) {
    if (is_numeric($method)) {
      $this->method = $method;

    } else {
      switch ($method) {
        case 'GET':
          $this->method = self::METHOD_GET;
          break;

        case 'POST':
          $this->method = self::METHOD_POST;
          break;

        default:
          $this->method = null;
          break;
      }
    }
  }
}

?>