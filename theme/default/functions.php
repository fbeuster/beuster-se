<?php

  function genPager($anzPages, $currPage, $dest) {
    $pagesToSee = 5;
    $ret = '';

    $i = 1;
    $j = $anzPages;
    $t = 1;

    $ret .= '<br class="clear">'."\r";
    $ret .= '<!-- Pager ANFANG -->'."\r";
    $ret .= '<div id="pager">'."\r";
    $ret .= ' <a href="';
    if($currPage > 1) {
      $ret .= $dest.($currPage - 1);
    } else {
      $ret .= '#';
    }
    $ret .= '" class="pArrows" id="pagerleft" title="Zurück">&nbsp;</a>'."\r";
    $ret .= ' <div id="number"';
    if($j < 5) {
      $ret .= ' style="text-align:center;"';
    }
    $ret .= '>'."\r";
    $ret .= '  <ul class="pnr" id="pnr" style="width:'.($j*27-2).'px">'."\r";
    for($i; $i <= $j; $i++) {
      $link = '   <li';
      if($i == $currPage) {
        $link .= ' class="themecolor"';
      }
      if($i == $t) {
        $link .= ' style="display: block;"';
      }
      $link .= '><a href="';
      if($i == $currPage) {
        $link .= '#';
      } else {
        $link .= $dest.$i;
      }
      $link .= '"';
      if($i == $t && $i == $j) {
        $link .= ' style="border: 0;"';
      } else if($i == $t) {
        $link .= ' style="border-left: 0;"';
      } else if($i == $j) {
        $link .= ' style="border-right: 0;"';
      }
      $link .= '>'.$i.'</a></li>'."\r";
      $ret .= $link;
    }
    $ret .= '  </ul>'."\r";
    $ret .= ' </div>'."\r";
    $ret .= ' <a href="';
    if($currPage < $anzPages && $anzPages > 5) {
      $ret .= $dest.($currPage + 1);
    } else {
      $ret .= '#';
    }
    $ret .= '" class="pArrows" id="pagerright" title="Weiter">&nbsp;</a>'."\r";
    $ret .= '</div>'."\r";
    return $ret;
  }

?>
