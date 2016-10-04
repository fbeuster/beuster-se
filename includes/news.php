﻿<?php

    $a = array();
    $ena = 1;
    $news = array();
    $archive = false;

    $a['filename'] = 'news.php';
    $a['data'] = array();

    if(isset($_GET['c'])) {
        if(is_numeric($_GET['c'])) {
            $cat = new Category($_GET['c']);
        } else {
            $cat = Category::newFromName($_GET['c']);
        }
    } else if(isset($_GET['p'])){
        $cat = Category::newFromName($_GET['p']);
    } else {
        $cat = new Category(1);
    }
    if(isset($_GET['n'])) {
        $id = $_GET['n'];
    } else {
        $id = -1;
    }

    if($id !== -1) {

        // single article

        include('newsone.php');
        if($a['data']['ret'] != '') return $a['data']['ret'];
        $a['data']['articles'] = $articles;

    } else {

        // Kategorienews

        if($cat->isPortfolio()) {

            $a['filename'] = 'portfolio.php';

            # request portfolio sets
            $fields = array('ID');
            $conds = array('Typ = ? AND ParentId != ?', 'ii', array(3, 1));
            $options = 'ORDER BY Cat ASC';
            $db = Database::getDB();
            $res = $db->select('newscat', $fields, $conds, $options);

            # fill portfolio sets
            $portSets = array();
            foreach ($res as $set) {
                $portSets[] = new PortfolioSet($set['ID']);
            }

            $a['data']['portSets'] = $portSets;
            $a['data']['ret'] = '';
        }
    }
    return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
?>