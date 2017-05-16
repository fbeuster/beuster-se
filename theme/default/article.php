<?php
  $page     = Lixter::getLix()->getPage();
  $article  = $page->getArticle();

  $url    = 'https://beusterse.de'.replaceUml($article->getLink());
  $anhang = ' #beusterse ';

  if (strlen($article->getTitle()) > 61) {
    $text = substr($article->getTitle(), 0, 60).'...'.$anhang.$url;

  } else {
    $text = $article->getTitle().$anhang.$url;
  }
?>

 <article>
    <h1><?php echo $article->getTitle(); ?></h1>
    <?php if ($article->getProjState() !== 0) { ?>
    <span value="<?php echo substr($article->getProjState(), 0, 1); ?>">
      Status: <?php echo substr($article->getProjState(), 1, strlen($article->getProjState())-1); ?>
    </span>
    <?php } ?>
    <?php echo $article->getContentDecorated()."\n"; ?>
    <?php if(count($article->getGallery()) > 0) { echo '<section>'.genGal($article->getGallery()).'</section>'; } ?>
  </article>

  <?php if(count($article->getAttachments()) > 0) { ?>
    <section>
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
    </section>
  <?php } ?>

  <!-- comments -->
  <div>
    <h2 id="comments">
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
    <div>
      <?php echo genPager($article->getPagesCmt(), $article->getStartCmt(), $article->getLink().'/page'); ?>
    </div>
      <?php } ?>
    <?php } else { ?>
    <div>
      <p><?php I18n::e('comment.empty'); ?></p>
    </div>
    <?php } ?>
    <div id="newComment">
      <span><?php I18n::e('comment.form.legend'); ?></span>
       <?php $err = array('t' => $data['eType'], 'c' => $data['ec']);
       echo genFormPublic($err, $cmtReply, time(), I18n::t('comment.form.legend'), 'commentForm', $page->getCommentReply()); ?>
    </div>
  </div>
