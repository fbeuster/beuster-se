<?php
  $page     = Lixter::getLix()->getPage();
  $article  = $page->getArticle();
?>
    <article>
      <a href="<?php echo $article->getCategory()->getLink(); ?>" class="back">
        <?php I18n::e('article.back_link', array($article->getCategory()->getName())); ?>
      </a>
        <section class="article">
          <h1><?php echo $article->getTitle(); ?></h1>
          <i>
          <?php I18n::e('article.info.by'); ?>
          <a href="/<?php echo $article->getAuthor()->getName(); ?>">
            <?php echo $article->getAuthor()->getClearname(); ?>
          </a>

          <?php I18n::e('article.info.on'); ?>
          <time datetime="<?php echo date('c', $article->getDate()); ?>">
            <?php echo date('d.m.Y', $article->getDate()); ?>
          </time>
        </i>
        <?php echo $article->getContentDecorated(); ?>
      </section>
      <a href="<?php echo $article->getCategory()->getLink(); ?>" class="back">
        <?php I18n::e('article.back_link', array($article->getCategory()->getName())); ?>
      </a>

      <?php echo moduleGoogleAdSense(); ?>

      <?php if(count($article->getGallery()) > 0) { ?>
        <section class="gallery">
          <h2><?php I18n::e('article.gallery.title'); ?></h2>
          <ul>
          <?php foreach ($article->getGallery() as $image) { ?>
            <li>
              <img
                src="<?php echo $image->getAbsolutePath(); ?>"
                data-src="<?php echo $image->getAbsolutePath(); ?>"
                alt="<?php echo $image->getPath(); ?>"
                data-caption="<?php echo $image->getTitle(); ?>">
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
              <a href="/<?php echo $attachment->getPath(); ?>" data-file="<?php echo md5($attachment->getId()); ?>">
                <?php echo $attachment->getName(); ?>
              </a>
              <br>
              <span>Version: <?php echo $attachment->getVersion(); ?></span>
              <span>License: <?php echo $attachment->getLicense(); ?></span>
              <span>Downlaods:
                <span class="counter" data-file="<?php echo md5($attachment->getId()); ?>">
                  <?php echo $attachment->getDownloads(); ?>
                </span>
              </span>
            </li>
          <?php } ?>
          </ul>
        </section>
      <?php } ?>

      <?php if (count($article->getAttachments()) > 0 &&
                count($article->getGallery()) > 0) { ?>
      <a href="<?php echo $article->getCategory()->getLink(); ?>" class="back">
        <?php I18n::e('article.back_link', array($article->getCategory()->getName())); ?>
      </a>
      <?php } ?>

      <?php
        $recommendations = ArticleRecommender::getRecommendationsFromArticleCategory($article->getId(), $article->getCategory()->getId());
      ?>

      <?php if (count($recommendations) > 0) { ?>
        <section class="recommended">
          <h2><?php I18n::e('article.recommendations.from_category_headline',$article->getCategory()->getName()); ?></h2>
          <div class="recommendations">
            <?php foreach ($recommendations as $recommendation) {?>
              <?php
                if($recommendation->getThumbnail() != null) {
                  if($recommendation->isPlaylist()) {
                    $thumb_src = $recommendation->getThumbnail();
                  } else {
                    $thumb_src = $recommendation->getThumbnail()->getPathThumb(800, 450);
                  }
                } else {
                  $thumb_src = Lixter::getLix()->getTheme()->getFile('assets/img/default_thumbnail_800_450.jpg');
                }
              ?>
              <div class="recommendation">
                <div class="thumbnail">
                  <a href="<?php echo $recommendation->getLink();?>">
                    <img src="/<?php echo $thumb_src; ?>" alt="<?php echo $recommendation->getTitle(); ?>">
                  </a>
                </div>
                <div class="meta">
                  <a href="<?php echo $recommendation->getLink(); ?>">
                    <?php echo $recommendation->getTitle(); ?>
                  </a>
                  <p>
                    <?php echo $recommendation->getContentPreview(256); ?>
                  </p>
                </div>
              </div>
            <?php } ?>
          </div>
        </section>
      <?php } ?>

      <a href="<?php echo $article->getCategory()->getLink(); ?>" class="back">
        <?php I18n::e('article.back_link', array($article->getCategory()->getName())); ?>
      </a>

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

      <a href="<?php echo $article->getCategory()->getLink(); ?>" class="back">
        <?php I18n::e('article.back_link', array($article->getCategory()->getName())); ?>
      </a>

      <?php
        $recommendations = ArticleRecommender::getRandomRecommendationsFromArticle($article->getId());
      ?>

      <?php if (count($recommendations) > 0) { ?>
        <section class="recommended">
          <h2><?php I18n::e('article.recommendations.random_headline'); ?></h2>
          <div class="recommendations">
            <?php foreach ($recommendations as $recommendation) {?>
              <?php
                if($recommendation->getThumbnail() != null) {
                  if($recommendation->isPlaylist()) {
                    $thumb_src = $recommendation->getThumbnail();
                  } else {
                    $thumb_src = $recommendation->getThumbnail()->getPathThumb(800, 450);
                  }
                } else {
                  $thumb_src = Lixter::getLix()->getTheme()->getFile('assets/img/default_thumbnail_800_450.jpg');
                }
              ?>
              <div class="recommendation">
                <div class="thumbnail">
                  <a href="<?php echo $recommendation->getLink();?>">
                    <img src="/<?php echo $thumb_src; ?>" alt="<?php echo $recommendation->getTitle(); ?>">
                  </a>
                </div>
                <div class="meta">
                  <a href="<?php echo $recommendation->getLink(); ?>">
                    <?php echo $recommendation->getTitle(); ?>
                  </a>
                  <p>
                    <?php echo $recommendation->getContentPreview(256); ?>
                  </p>
                </div>
              </div>
            <?php } ?>
          </div>
        </section>
      <?php } ?>

      <a href="<?php echo $article->getCategory()->getLink(); ?>" class="back">
        <?php I18n::e('article.back_link', array($article->getCategory()->getName())); ?>
      </a>

    </article>
