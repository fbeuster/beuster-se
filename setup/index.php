<?php

  function __autoload($class) {
    include_once '../classes/'.$class.'.php';
  }

  $setup = Setup::getSetup();
  $setup->init();
  $setup->handleRequest();

?>

<!DOCTYPE html>
<html dir="ltr" lang="de-DE">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lixter Setup Page</title>
  </head>
  <body>
    <h1>Lixter setup process</h1>
    <?php require_once $setup->getStepFile(); ?>
  </body>
</html>