<?php
    function genGal($pics) {
        $ret = '';
        $j = count($pics);
        $ret .= ' <div class="beContentEntryGallery">';
        $ret .= '  <ul class="beContentEntryGalleryImageList">';
        $i = 0;
        foreach($pics as $pic) {
            $i++;
            $ret .= '   <li><a href="'.$pic->getAbsolutePath().'">';
            $ret .= '    <img src="'.$pic->getAbsoluteThumbnailPath(295, 190).'" id="pic'.$i.'" class="pic" alt="" name="'.$pic->getTitle().'" title="'.$pic->getTitle().'">';
            $ret .= '   </a></li>';
            $ret .= '   <li class="hasjs"><img src="'.$pic->getAbsoluteThumbnailPath(295, 190).'" id="pic'.$i.'" class="pic" alt="" name="'.$pic->getTitle().'" title="'.$pic->getTitle().'"></li>';
        }
        $ret .= '  </ul>';
        $ret .= '  <br class="clear">'."\r";
        $ret .= '  <span id="galL" title="Zurück">&nbsp;</span>';
        $ret .= '  <div class="beContentEntryGalleryBigImage">';
        $ret .= '   <img src="/'.Lixter::getLix()->getSystemFile('assets/img/spacer.gif').'" alt="picL.0" id="pic">';
        $ret .= '  </div>';
        $ret .= '  <span id="galR" title="Weiter">&nbsp;</span>';
        $ret .= '  <br class="clear">';
        $ret .= ' </div>';
        return $ret;
    }

    function genFormPublic($err, $dest, $time, $title, $formType, $reply = 'null') {
        $ret = '';
        switch($err['t']) {
            case 1:
                $ret .= '<p class="alert">'.I18n::t('general_form.errors.incomplete').'</p>'."\r";
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
        $ret .= '<form action="'.$dest.'" method="post">'."\r";
        $ret .= '  <label for="usr">'.I18n::t('general_form.name.label').' *:</label>'."\r";
        $ret .= '  <input type="text" name="usr"';
        if($err['t'] != 0 && $err['c'] != '') {
            $ret .= ' value="'.$err['c']['user'].'"';
        }
        $ret .= ' required placeholder="'.I18n::t('general_form.name.placeholder').'">'."\r";
        $ret .= '  <br>'."\r";

        $ret .= '  <span class="antSp">'."\r";
        $ret .= '   <label for="email">'.I18n::t('general_form.mail_spam.label').'</label>'."\r";
        $ret .= '   <input type="email" name="email">'."\r";
        $ret .= '   <br>'."\r";
        $ret .= '  </span>'."\r";

        $ret .= '  <span class="antSp">'."\r";
        $ret .= '   <label for="homepage">'.I18n::t('general_form.website_spam.label').'</label>'."\r";
        $ret .= '   <input type="text" name="homepage">'."\r";
        $ret .= '  </span>'."\r";

        $ret .= '  <label for="usrml">'.I18n::t('general_form.mail.label').' *:</label>'."\r";
        $ret .= '  <input type="text" name="usrml"';
        if($err['t'] != 0 && $err['c'] != '') {
            $ret .= ' value="'.$err['c']['mail'].'"';
        }
        $ret .= ' required placeholder="'.I18n::t('general_form.mail.placeholder').'">'."\r";
        $ret .= '  <br>'."\r";

        $ret .= '  <label for="usrpg">'.I18n::t('general_form.website.label').':</label>'."\r";
        $ret .= '  <input type="text" name="usrpg"';
        if($err['t'] != 0 && $err['c'] != '') {
            $ret .= ' value="'.$err['c']['page'].'"';
        }
        $ret .= ' placeholder="'.I18n::t('general_form.website.placeholder').'">'."\r";
        $ret .= '  <br>'."\r";

        $ret .= '  <label for="usrcnt">'.I18n::t('general_form.message.label').' *:</label>'."\r";
        $ret .= '  <textarea name="usrcnt" id="usrcnt" required  placeholder="'.I18n::t('general_form.message.placeholder').'">';
        if($err['t'] != 0 && $err['c'] != '') {
            $ret .= $err['c']['cnt'];
        }
        $ret .= '</textarea>'."\r";
        $ret .= '  <input type="hidden" name="date" value="'.$time.'">'."\r";
        $ret .= '  <input type="hidden" name="reply" value="'.$reply.'" class="reply">'."\r";
        $ret .= '  <input type="submit" name="formaction" value="'.I18n::t('general_form.submit').'" class="button" id="formPublicSub">'."\r";
        $ret .= '  <input type="reset" name="formreset" value="'.I18n::t('general_form.clear').'" class="button" id="formPublicReset">'."\r";
        $ret .= '</form>'."\r";
        if($formType == 'commentForm') {
            $ret .= '<p class="beCommentNewInfo messageLength">'."\r";
            $remain = '<span>1500</span>';
            $ret .= I18n::t('general_form.remain', array($remain))."\r";
            $ret .= '</p>'."\r";
        }
        $ret .= '<p class="beCommentNewTime">'."\r";
        $wait = '<strong id="wait">20</strong>';
        $ret .= I18n::t('general_form.wait', array($wait))."\r";
        $ret .= '</p>'."\r";
        $ret .= '<p class="beCommentNewDisclaimer">'."\r";
        $more_info = '<a href="/impressum">'.I18n::t('general_form.imprint').'</a>';
        $ret .= I18n::t('general_form.disclaimer', array($more_info))."\r";
        $ret .= '</p>'."\r";
        return $ret;
    }

    function genMenu() {
        $pars = getTopCats();
        foreach($pars as $par) {
            echo '<li><a href="/'.(($par!=getCatID('Blog'))?lowerCat(getCatName($par)):'').'">'.getCatName($par).'</a></li>'."\r";
        }
    }

    function genSubMenu() {
        if(isset($_GET['n'])) {
            $catID = getNewsCat($_GET['n']);
            $cat = getCatName($catID);
            $catParentID = getCatParent($catID);
            $catParentName = getCatname($catParentID);
            echo '<li class="between">&gt;</li><li><a href="/">Blog</a></li>'."\r";
            if(lowerCat($cat) !== 'blog') {
                echo '<li class="between">&gt;</li><li><a href="/'.lowerCat($catParentName).'">'.$catParentName.'</a></li>'."\r";
                echo '<li class="between">&gt;</li><li><a href="/'.lowerCat($cat).'">'.$cat.'</a></li>'."\r";
            }
        } else {
            if(isset($_GET['c']) && Category::exists($_GET['c'])) {
                $catID = $_GET['c'];
            } else if(isset($_GET['p']) && Category::exists($_GET['p'])) {
                $catID = $_GET['p'];
            } else {
                $catID = getCatID('Blog');
            }
            if(!is_numeric($catID)) {
                $catID = getCatID($catID);
            }
            $catParentID = getCatParent($catID);
            $children = getChildrenNames($catParentID);
            $noborder = ' class="noborder"';
            if($catID == getCatID('blog')) {
                echo ' <li'.$noborder.'><a href="/blog">Blog</a></li>'."\r";
                $noborder = '';
            }
            foreach($children as $child) {
                echo ' <li'.$noborder.'><a href="/'.lowerCat($child).'">'.$child.'</a></li>'."\r";
                $noborder = '';
            }
        }
    }

    function genComment($cmt, $cmtReply, $replyId = '') {
        if($replyId == '') {
            $replyId = $cmt->getId();
        }
        $html = '';
        $html .= '<div class="beCommentEntry" id="comment'.$cmt->getId().'" data-reply="'.$replyId.'">';
        $html .= '  <span class="beCommentEntryAvatar"><img src="'.Externals::getGravatar($cmt->getAuthor()->getMail()).'" alt="Avatar"></span>';
        $html .= '  <span class="beCommentEntryHeader">';
        $html .= '    <time datetime="'.date('c', $cmt->getDate()).'" class="long">'.date('d.m.Y H:i', $cmt->getDate()).'</time> -';
        $html .= '    <span class="author">';
        if(isValidUserUrl(rewriteUrl($cmt->getAuthor()->getWebsite()))) {
            $html .= '<a href="'.rewriteUrl($cmt->getAuthor()->getWebsite()).'">'.$cmt->getAuthor()->getClearname().'</a>';
        } else {
            $html .= $cmt->getAuthor()->getClearname();
        }
        $html .= '</span>';
        $html .= '  </span>';
        $html .= '  <div class="beCommentEntryContent">';
        $html .= $cmt->getContentParsed()."\n";
        $html .= '  </div>';
        $html .= '  <br class="clear">';
        $cmtReplyLink = $cmtReply.'#newComment?comment-reply='.$replyId;
        $html .= '  <a class="reply" href="'.$cmtReplyLink.'">Antworten</a>';
        if($cmt->hasReplies()) {
            $html .= '  <div class="replies">';
            foreach($cmt->getReplies() as $reply) {
                $html .= genComment($reply, $cmtReply, $replyId);
            }
            $html .= '  </div>';
        }
        $html .= '</div>';
        return $html;
    }
?>