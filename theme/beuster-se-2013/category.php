<?php

  $page = Lixter::getLix()->getPage();

  if ($page->hasArticles()) { ?>
    <menu class="beCategoryNav">
      <?php echo genSubMenu(); ?>
    </menu>

    <div id="beMainContent">
    <?php

      $i = 0;
      $last = count($page->getArticles()) - 1;
      foreach ($page->getArticles() as $article) { ?>

        <?php if($i%2==0) { ?><div class="beContentEntryRow"><?php } ?>
        <article class="beContentEntry">
          <h2 class="beContentEntryHeader"><a href="<?php echo $article->getLink();?>"><?php echo $article->getTitle(); ?></a></h2>
          <div class="beContentEntryFooter">
            <span class="categoryLink">Kategorie: <a href="/<?php echo $article->getCategory()->getnameUrl(); ?>"><?php echo $article->getCategory()->getName(); ?></a></span>
            <span class="authorLink">von <?php echo $article->getAuthor()->getClearname(); ?></span>
            <span class="commentsLink">
              <a href="<?php echo $article->getLink();?>#comments">
                <?php echo count($article->getComments()); ?> Kommentare
              </a>
            </span>
            <br class="clear">
          </div>
          <?php if($article->getThumbnail() != null) {
            $cssClasses = 'thumb';
            if($article->isPlaylist()) {
              $cssClasses .= ' playlist noShadow'; ?>
            <img src="<?php echo makeAbsolutePath($article->getThumbnail(), '', true); ?>" class="beContentEntryThumb <?php echo $cssClasses; ?>" alt="Vorschaubild">
            <?php } else { ?>
            <img src="<?php echo makeAbsolutePath($article->getThumbnail()->getPathThumb(), '', true); ?>" class="beContentEntryThumb <?php echo $cssClasses; ?>" alt="Vorschaubild">
            <?php } ?>
          <?php } ?>
          <p>
            <time class="beContentEntryTime"  datetime="<?php echo date('c', $article->getDate()); ?>"><?php echo date('d.m.Y', $article->getDate()); ?></time>
            <?php echo str_replace('###link###', $article->getLink(), $article->getContentPreview())."\n"; ?>
          </p>
        </article>

        <?php if($i%2 == 1 || $i == $last) { ?></div><?php } ?>

        <?php
        $i++;
      }
      if ( $page->getDestination() != '' ) {
        $dest = '/'.$page->getDestination().'/page';

      } else {
        $dest = '/page';
      }
      echo '<br class="clear">'."\r";
      echo genPager($page->getTotalPagesCount(), $page->getStartPage(), $dest); ?>
    <?php } else { ?>
      <p class="info"><?php echo I18n::t('category.no_articles_found'); ?></p><?php
    } ?>