<?php
  $page     = Lixter::getLix()->getPage();
  $article  = $page->getArticle();

  $url    = 'http://beusterse.de'.replaceUml($article->getLink());
  $anhang = ' #beusterse ';

  if (strlen($article->getTitle()) > 61) {
    $text = substr($article->getTitle(), 0, 60).'...'.$anhang.$url;

  } else {
    $text = $article->getTitle().$anhang.$url;
  }
?>

 <article class="beContentEntry">
    <span class="socialTxt" title="<?php echo $text; ?>" style="display: none;"><?php echo $text; ?></span>
    <h1 class="beContentEntryHeader"><?php echo $article->getTitle(); ?></h1>
    <?php if ($article->getProjState() !== 0) {
      switch (substr($article->getProjState(), 0, 1)) {
        case '1': $projStyle = 'background: #70d015; color: #000000;'; break;
        case '2': $projStyle = 'background: #efef15; color: #000000;'; break;
        case '3': $projStyle = 'background: #ff8015; color: #000000;'; break;
        case '4': $projStyle = 'background: #efefef; color: #2b2b2b;'; break;
        default: $projStyle = ''; break;
      }
    ?>
    <span class="projState" value="<?php echo substr($article->getProjState(), 0, 1); ?>" style="<?php echo $projStyle; ?>">
      Status: <?php echo substr($article->getProjState(), 1, strlen($article->getProjState())-1); ?>
    </span>
    <?php } ?>
    <?php echo $article->getContentDecorated()."\n"; ?>
    <?php if (count($article->getGallery()) > 0) { echo '<section>'.genGal($article->getGallery()).'</section>'; } ?>
  </article>

  <?php if(count($article->getAttachments()) > 0) { ?>
    <div class="beContentEntryAttachments">
      <h2>Attachments</h2>
      <ul>
      <?php foreach ($article->getAttachments() as $attachment) { ?>
        <li>
          <a href="/<?php echo $attachment->getPath(); ?>">
            <?php echo $attachment->getName(); ?>
          </a>
          <br>
          <span>Version: <?php echo $attachment->getVersion(); ?></span>
          <span>License: <?php echo $attachment->getLicense(); ?></span>
          <span>Downlaods: <?php echo $attachment->getDownloads(); ?></span>
        </li>
      <?php } ?>
      </ul>
    </div>
  <?php } ?>


  <!-- comments -->
  <div id="beContentEntryComments">
    <h2 id="comments" class="beContentEntryCommentsHeader">
      <?php I18n::e('comment.title'); ?>
      (<?php echo $article->getCommentsCount(); ?>)
    </h2>
    <?php
      $cmtReply = $article->getLink();
      if(count($article->getComments()) > 0) {
        foreach($article->getComments() as $cmt) {

          echo genComment($cmt, $cmtReply);
        }
        if($article->getPagesCmt() > 1) { ?>
    <div class="beCommentEntry">
      <br class="clear">
      <?php echo genPager($article->getPagesCmt(), $article->getStartCmt(), $article->getLink().'/page'); ?>
    </div>
      <?php } ?>
    <?php } else { ?>
    <div class="beCommentEntry">
      <p><?php I18n::e('comment.empty'); ?></p>
    </div>
    <?php } ?>
    <div class="beCommentNew" id="newComment">
      <span class="beCommentNewHeader"><?php I18n::e('comment.form.legend'); ?></span>
       <?php $err = array('t' => $data['eType'], 'c' => $data['ec']);
       echo genFormPublic($err, $cmtReply, time(), I18n::t('comment.form.legend'), 'commentForm', $page->getCommentReply()); ?>
    </div>
  </div>