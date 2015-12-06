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
    <title><?php echo I18n::t("setup.title"); ?></title>
    <link rel="stylesheet" href="assets/main.css" type="text/css">
    <script src="assets/main.js" type="text/javascript"></script>
  </head>
  <body>
    <div class="main_wrapper">
      <h1><?php echo I18n::t("setup.title"); ?></h1>
      <?php require_once $setup->getStepFile(); ?>
    </div>
  </body>
</html>