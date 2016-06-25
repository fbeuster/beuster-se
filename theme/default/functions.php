<?php

  function genPager($anzPages, $currPage, $dest) {
    $ret    = '';
    $pager  = new Pager(true, $anzPages, $currPage, $dest);
    $pager->setNextPrevText('&nbsp;', '&nbsp;');

    $list         = $pager->getList();
    $last_element = count($list) - 1;

    $ret .= '<br class="clear">'."\r";
    $ret .= '<div id="pager">'."\r";
    $ret .= ' <a href="'.$list[0][1].'" class="pArrows" id="pagerleft" title="Zurück">'.$list[0][0].'</a>'."\r";
    $ret .= ' <div id="number"';
    if($anzPages < 5) {
      $ret .= ' style="text-align:center;"';
    }
    $ret .= '>'."\r";
    $ret .= '  <ul class="pnr" id="pnr" style="width:'.($anzPages*27-2).'px">'."\r";

    foreach($list as $i => $item) {
      if ($i == 0) continue;
      if ($i == $last_element) continue;


      $link = '   <li';
      if($item[2]) {
        $link .= ' class="themecolor"';
      }
      if($i == 1) {
        $link .= ' style="display: block;"';
      }
      $link .= '><a href="'.$item[1].'"';
      if($i == 1 && $i == $anzPages) {
        $link .= ' style="border: 0;"';
      } else if($i == 1) {
        $link .= ' style="border-left: 0;"';
      } else if($i == $anzPages) {
        $link .= ' style="border-right: 0;"';
      }
      $link .= '>'.$item[0].'</a></li>'."\r";
      $ret .= $link;
    }

    $ret .= '  </ul>'."\r";
    $ret .= ' </div>'."\r";
    $ret .= ' <a href="'.$list[$last_element][1].'" class="pArrows" id="pagerright" title="Weiter">'.$list[$last_element][0].'</a>'."\r";
    $ret .= '</div>'."\r";
    return $ret;
  }

?>
