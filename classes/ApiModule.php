<?php

  interface ApiModule {
    public function init($data);
    public function requiresAdmin();
    public function run();
  }

?>