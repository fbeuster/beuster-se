<?php
  $page     = Lixter::getLix()->getPage();
  $article  = $page->getArticle();
?>
    <article>
      <a href="/<?php echo $article->getCategory()->getNameUrl(); ?>" class="back">
        <?php I18n::e('article.back_link', array($article->getCategory()->getName())); ?>
      </a>
      <section class="article">
        <h1><?php echo $article->getTitle(); ?></h1>
        <i>
          <?php I18n::e('article.info', array($article->getAuthor()->getClearname())); ?>
          <time datetime="<?php echo date('c', $article->getDate()); ?>" class="long">
            <?php echo date('d.m.Y H:i', $article->getDate()); ?>
          </time>
        </i>
        <?php echo $article->getContentDecorated(); ?>
      </section>
      <a href="/<?php echo $article->getCategory()->getNameUrl(); ?>" class="back">
        <?php I18n::e('article.back_link', array($article->getCategory()->getName())); ?>
      </a>

      <?php if(count($article->getGallery()) > 0) { ?>
        <section class="gallery">
          <h2><?php I18n::e('article.gallery.title'); ?></h2>
          <ul>
          <?php foreach ($article->getGallery() as $image) { ?>
            <li>
              <img src="<?php echo $image->getAbsolutePath(); ?>" alt="<?php echo $image->getPath(); ?>" data-caption="<?php echo $image->getTitle(); ?>">
            </li>
          <?php } ?>
          </ul>
        </section>
      <?php } ?>

      <?php if(count($article->getAttachments()) > 0) { ?>
        <section class="attachments">
          <h2>Attachments</h2>
          <ul>
          <?php foreach ($article->getAttachments() as $attachment) { ?>
            <li>
              <a href="<?php echo $attachment->getPath(); ?>">
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

      <section class="comments">
        <h2><?php I18n::e('comment.title'); ?></h2>
        <?php if(count($article->getComments()) > 0) { ?>
          <?php foreach($article->getComments() as $comment) { ?>
            <?php echo makeComment($comment, $article->getLink()); ?>
          <?php } ?>
        <?php } else { ?>
          <?php I18n::e('comment.empty'); ?>
        <?php } ?>

        <?php
          $err = array('t' => $page->getError(), 'c' => $page->getValues());
          echo makeForm($err, $article->getLink(), time(),
                        I18n::t('comment.form.legend'), 'commentForm', $page->getCommentReply());
        ?>
      </section>

    </article>
