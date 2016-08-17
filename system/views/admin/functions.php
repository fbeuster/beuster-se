<?php

  function genPager($total_pages, $current_age, $destination) {
    $ret    = '';
    $pager  = new Pager(false, $total_pages, $current_age, $destination);

    $list         = $pager->getList();
    $last_element = count($list) - 1;

    $ret .= '<ul class="pager">'."\r";

    foreach($list as $i => $item) {
      if ($i == 0) {
        $li_class = 'prev';
      } else if ($i == $last_element) {
        $li_class = 'next';
      } else if($item[0] == Pager::FILLER_TEXT) {
        $li_class = 'filler';
      } else {
        $li_class = 'page';
      }

      if ($li_class != 'page') {
        if (!$item[2]) {
          $li_class .= ' deactivated';
        }

      } else {
        if ($item[0] == $current_age) {
          $li_class .= ' current';
        }
      }


      $link = '<li class="'.$li_class.'">';
      if ($item[1] != '#' && $item[1] != '') {
        $link .= '<a href="'.$item[1].'">';
      }

      $link .= $item[0];

      if ($item[1] != '#') {
        $link .= '</a>';
      }
      $link .= '</li>'."\r";

      $ret .= $link;
    }

    $ret .= '</ul>'."\r";

    return $ret;
  }

  function makeBodyClass($page) {
    switch($page) {
      case 'single':
      case 'page' :
        return 'article';
      default:
        return 'category';
    }
  }

  function makeComment($comment, $cmtReply, $replyId = '') {
    if($replyId == '') {
      $replyId = $comment->getId();
    }

    $html = '';
    $html .= '<section class="comment" id="comment'.$comment->getId().'" data-reply="'.$replyId.'">';
    $html .= ' <div class="wrapper">';
    $html .= '  <div class="avatar"><img src="'.Externals::getGravatar($comment->getAuthor()->getMail()).'" alt="Avatar"></div>';
    $html .= '  <div class="content">';

    $cmtReplyLink = $cmtReply.'#newComment?comment-reply='.$replyId;
    $cmtReplyLinkHtml = ' - <a class="reply" href="'.$cmtReplyLink.'">Antworten</a>';

    if(isValidUserUrl(rewriteUrl($comment->getAuthor()->getWebsite()))) {
      $html .= '   <i class="info">'.date('d.m.Y H:i', $comment->getDate()).' by <a href="'.rewriteUrl($comment->getAuthor()->getWebsite()).'" class="author">'.$comment->getAuthor()->getClearname().'</a>'.$cmtReplyLinkHtml.'</i>';

    } else {
      $html .= '   <i class="info">'.date('d.m.Y H:i', $comment->getDate()).' by <span class="author">'.$comment->getAuthor()->getClearname().'</span>'.$cmtReplyLinkHtml.'</i>';
    }

    $html .= $comment->getContentParsed()."\n";
    $html .= '  </div>';
    $html .= ' </div>';

    if($comment->hasReplies()) {
      $html .= ' <div class="answers">';
      foreach($comment->getReplies() as $reply) {
        $html .= makeComment($reply, $cmtReply, $replyId);
      }
      $html .= ' </div>';
    }

    $html .= '</section>';
    return $html;
  }

  function makeCategoryTitle($page) {
    if ($page == 'category') {
      if (isset($_GET['c'])) {
        $c = $_GET['c'];

      } else if(isset($_GET['p'])) {
        $c = $_GET['p'];

      } else {
        $c = null;
      }

      if ($c !== null) {
        $category = Category::newFromName($c);
        return '<span class="categoryTitle">'.$category->getName().'</span>';
      }
    }

    return '';
  }

  function makeForm($err, $dest, $time, $title, $formType, $reply = 'null') {
    $ret = '';
    switch($err['t']) {
      case 1:
        $ret .= '<p class="alert">Das Formular ist unvollständig. Makierte Felder sind Pflicht.</p>'."\r";
        break;
      case 2:
        $ret .= '<p class="alert">Sie müssen die Zeit abwarten!</p>'."\r";
        break;
      case 3:
        $ret .= '<p class="alert">E-Mail ist ungültig</p>'."\r";
        break;
      case 4:
        $ret .= '<p class="alert">Dein Kommentar ist zu lang (max. 1500 Zeichen).</p>'."\r";
        break;
      default:
        break;
    }
    $ret .= '<script type="text/javascript"></script>'."\r";
    $ret .= '<form action="'.$dest.'" method="post" class="userform">'."\r";
    $ret .= ' <fieldset>'."\r";
    $ret .= '  <legend>'.$title.'</legend>'."\r";

    $ret .= '  <label class="required"><span>Name</span>';
    $ret .= '  <input type="text" name="usr" required="required"';
    if($err['t'] != 0 && $err['c'] != '') {
      $ret .= ' value="'.$err['c']['user'].'"';
    }
    $ret .= '></label>'."\r";

    $ret .= '  <span class="antSp">'."\r";
    $ret .= '   <label for="email">Die Abfrage erfolt in einem anderen Feld. Tragen Sie hier bitte NICHTS ein!</label>'."\r";
    $ret .= '   <input type="email" name="email">'."\r";
    $ret .= '   <br>'."\r";
    $ret .= '  </span>'."\r";

    $ret .= '  <span class="antSp">'."\r";
    $ret .= '   <label for="homepage">Tragen Sie auch hier bitte NICHTS ein!</label>'."\r";
    $ret .= '   <input type="text" name="homepage">'."\r";
    $ret .= '  </span>'."\r";

    $ret .= '  <label class="required"><span>E-Mail</span>';
    $ret .= '  <input type="text" name="usrml" required="required"';
    if($err['t'] != 0 && $err['c'] != '') {
      $ret .= ' value="'.$err['c']['mail'].'"';
    }
    $ret .= '></label>'."\r";

    $ret .= '  <label><span>Website</span>';
    $ret .= '  <input type="text" name="usrpg"';
    if($err['t'] != 0 && $err['c'] != '') {
      $ret .= ' value="'.$err['c']['page'].'"';
    }
    $ret .= '></label>'."\r";

    $ret .= '  <label class="required"><span>Comment</span>';
    $ret .= '  <textarea name="usrcnt" id="usrcnt" required="required">';
    if($err['t'] != 0 && $err['c'] != '') {
      $ret .= $err['c']['cnt'];
    }
    $ret .= '</textarea>'."\r";
    $ret .= '</label>'."\r";

    if($formType == 'commentForm') {
      $ret .= '<p class="beCommentNewInfo messageLength">'."\r";
      $ret .= '  Noch <span>1500</span> Zeichen übrig.'."\r";
      $ret .= '</p>'."\r";
    }
    $ret .= '  <input type="hidden" name="date" value="'.$time.'">'."\r";
    $ret .= '  <input type="hidden" name="reply" value="'.$reply.'" class="reply">'."\r";

    $ret .= '  <input type="submit" name="formaction" value="Absenden" class="button" id="formSubmit">'."\r";
    $ret .= '  <input type="reset" name="formreset" value="Eingaben löschen" class="button" id="formReset">'."\r";

    $ret .= ' </fieldset>'."\r";
    $ret .= '</form>'."\r";
    $ret .= '<p class="newCommentTime">'."\r";
    $ret .= '  Warte noch <strong id="wait">20</strong> Sekunden, bevor du posten kannst.'."\r";
    $ret .= '</p>'."\r";
    $ret .= '<p class="newCommentDisclaimer">'."\r";
    $ret .= '  Mit * makierte Felder sind Pflicht, deine Mailadresse wird nicht veröffentlicht. Mehr dazu im <a href="/impressum">Impressum</a>.'."\r";
    $ret .= '</p>'."\r";
    return $ret;
  }

?>
