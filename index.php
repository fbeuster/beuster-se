<?php

    /**
     * Entering point via web
     * \file index.php
     */

    ob_start();

    /**
     * autloading for classes
     *
     * @param String $class a class name
     */
    function __autoload($class) {
        include_once 'classes/'.$class.'.php';
    }

    $lix = Lixter::getLix();
    $lix->init();
    $lix->run();

    ob_end_flush();
?>