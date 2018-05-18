<?php

  Lixter::getLix()->getTheme()->addThumbnailSize(800, 450);

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
    $p = Lixter::getLix()->getPage();

    if (  $p->getType() == Page::CATEGORY_PAGE
      ||  $p->getType() == Page::SEARCH_PAGE) {
      return 'category';
    }

    if (  $p->getType() == Page::STATIC_PAGE) {
      return 'article';
    }

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

    $avatar = $comment->getAuthor()->getUserInfo('profile_image');

    if ($avatar == '') {
      $version  = $comment->getAuthor()->getId() % 3;
      $path     = 'assets/img/default_avatar_'.$version.'.png';
      $avatar   = '/'.Lixter::getLix()->getTheme()->getFile($path);
    }

    $html = '';
    $html .= '<section class="comment" id="comment'.$comment->getId().'" data-reply="'.$replyId.'">';
    $html .= ' <div class="wrapper">';
    $html .= '  <div class="avatar"><img src="'.$avatar.'" alt="Avatar"></div>';
    $html .= '  <div class="content">';

    if(isValidUserUrl(rewriteUrl($comment->getAuthor()->getWebsite()))) {
      $author_link  = '<a href="'.rewriteUrl($comment->getAuthor()->getWebsite()).'" class="author">'.$comment->getAuthor()->getClearname().'</a>';

    } else {
      $author_link = '<span class="author">'.$comment->getAuthor()->getClearname().'</span>';
    }

    $comment_reply  = $cmtReply.'#newComment?comment-reply='.$replyId;
    $comment_reply  = ' - <a class="reply" href="'.$comment_reply.'">'.I18n::t('comment.reply.link').'</a>';
    $comment_by     = I18n::t('comment.by', array(date('d.m.Y H:i', $comment->getDate()),
                                                  $author_link));

    $html .= '   <i class="info">'.$comment_by.$comment_reply.'</i>';
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
    $user = User::newFromCookie();

    switch($err['t']) {
      case 1:
        $ret .= '<p class="alert">'.I18n::t('general_form.errors.incomplete_form').'</p>'."\r";
        break;
      case 2:
        $ret .= '<p class="alert">'.I18n::t('general_form.errors.too_quick').'</p>'."\r";
        break;
      case 3:
        $ret .= '<p class="alert">'.I18n::t('general_form.errors.invalid_mail').'</p>'."\r";
        break;
      case 4:
        $ret .= '<p class="alert">'.I18n::t('general_form.errors.too_long', array(1500)).'</p>'."\r";
        break;
      default:
        break;
    }
    $ret .= '<script type="text/javascript"></script>'."\r";
    $ret .= '<form action="'.$dest.'" method="post" class="userform">'."\r";
    $ret .= ' <fieldset>'."\r";
    $ret .= '  <legend>'.$title.'</legend>'."\r";

    if (!$user) {
      $ret .= '  <label class="required"><span>'.I18n::t('general_form.name.label').'</span>';
      $ret .= '  <input type="text" name="usr" required="required"';
      if($err['t'] != 0 && $err['c'] != '') {
        $ret .= ' value="'.$err['c']['user'].'"';
      }
      $ret .= ' placeholder="'.I18n::t('general_form.name.placeholder').'"></label>'."\r";
    }

    $ret .= '  <span class="antSp">'."\r";
    $ret .= '   <label for="email">'.I18n::t('general_form.mail_spam.label').'</label>'."\r";
    $ret .= '   <input type="email" name="email">'."\r";
    $ret .= '   <br>'."\r";
    $ret .= '  </span>'."\r";

    $ret .= '  <span class="antSp">'."\r";
    $ret .= '   <label for="homepage">'.I18n::t('general_form.website_spam.label').'</label>'."\r";
    $ret .= '   <input type="text" name="homepage">'."\r";
    $ret .= '  </span>'."\r";

    if (!$user) {
      $ret .= '  <label class="required"><span>'.I18n::t('general_form.mail.label').'</span>';
      $ret .= '  <input type="text" name="usrml" required="required"';
      if($err['t'] != 0 && $err['c'] != '') {
        $ret .= ' value="'.$err['c']['mail'].'"';
      }
      $ret .= ' placeholder="'.I18n::t('general_form.mail.placeholder').'"></label>'."\r";

      $ret .= '  <label><span>'.I18n::t('general_form.website.label').'</span>';
      $ret .= '  <input type="text" name="usrpg"';
      if($err['t'] != 0 && $err['c'] != '') {
        $ret .= ' value="'.$err['c']['page'].'"';
      }
      $ret .= ' placeholder="'.I18n::t('general_form.website.placeholder').'"></label>'."\r";
    }

    if ($user) {
      if ($user->getWebsite() == '') {
        $name = $user->getClearname();

      } else {
        $name = '<a href="'.rewriteUrl($user->getWebsite()).'">';
        $name .= $user->getClearname();
        $name .= '</a>';
      }

      $ret .= '<p>';
      $ret .= I18n::t('general_form.commenting_as',
                      array($name, $user->getMail()));
      $ret .= '</p>';
    }

    $ret .= '  <label class="required"><span class="textarea">'.I18n::t('comment.form.message.label').'</span>';
    $ret .= '  <textarea name="usrcnt" id="usrcnt" required="required"';
    $ret .= ' placeholder="'.I18n::t('comment.form.message.placeholder').'">';
    if($err['t'] != 0 && $err['c'] != '') {
      $ret .= $err['c']['cnt'];
    }
    $ret .= '</textarea>'."\r";
    $ret .= '</label>'."\r";

    if($formType == 'commentForm') {
      $ret .= '<p class="beCommentNewInfo messageLength">'."\r";
      $remain = '<span>1500</span>';
      $ret .= I18n::t('general_form.remain', array($remain))."\r";
      $ret .= '</p>'."\r";
    }

    $ret .= '  <label class="checkbox">';
    $ret .= '  <input type="checkbox" name="notifications_enabled"';
    if (  $err['t'] != 0 &&
          $err['c'] != '' &&
          $err['c']['notifications_enabled'] === true) {
      $ret .= ' checked="checked"';
    } else {
      $ret .= '';
    }
    $ret .= '><span>'.I18n::t('comment.form.notifications.label').'</span></label>'."\r";

    $ret .= '  <input type="hidden" name="date" value="'.$time.'">'."\r";
    $ret .= '  <input type="hidden" name="reply" value="'.$reply.'" class="reply">'."\r";

    $ret .= '<p class="newCommentDisclaimer">'."\r";

    $more_info = '<a href="/impressum">'.I18n::t('general_form.privacy').'</a>';
    $ret .= I18n::t('general_form.disclaimer', array($more_info))."\r";

    $ret .= '</p>'."\r";

    $ret .= '  <input type="submit" name="formaction" value="'.I18n::t('general_form.submit').'" class="button" id="formaction">'."\r";
    $ret .= '  <input type="reset" name="formreset" value="'.I18n::t('general_form.clear').'" class="button" id="formreset">'."\r";

    $ret .= ' </fieldset>'."\r";
    $ret .= '</form>'."\r";
    $ret .= '<p class="newCommentTime">'."\r";

    $wait = '<strong id="wait">20</strong>';
    $ret .= I18n::t('general_form.wait', array($wait))."\r";

    $ret .= '</p>'."\r";
    return $ret;
  }

?>
