<?php

  $page = Lixter::getLix()->getPage();

  if ($page->hasArticles()) { ?>
    <menu>
      <?php echo genSubMenu(); ?>
    </menu>

    <?php
      foreach ($page->getArticles() as $article) { ?>

  <article>
    <h2><a href="<?php $article->getLink();?>"><?php echo $article->getTitle(); ?></a></h2>
    <div>
      <span>Kategorie: <a href="<?php echo $article->getCategory()->getLink(); ?>"><?php echo $article->getCategory()->getName(); ?></a></span>
      <span>von <?php echo $article->getAuthor()->getClearname(); ?></span>
      <span>
        <a href="<?php echo $article->getLink();?>#comments">
          <?php echo count($article->getComments()); ?> Kommentare
        </a>
      </span>
    </div>
          <?php if($article->getThumbnail() != null) {
            if($article->isPlaylist()) { ?>
            <img src="<?php echo $article->getThumbnail(); ?>" alt="Vorschaubild">
            <?php } else { ?>
            <img src="<?php echo $article->getThumbnail()->getPathThumb(); ?>" alt="Vorschaubild">
            <?php } ?>
          <?php } ?>
    <p>
      <time datetime="<?php echo date('c', $article->getDate()); ?>"><?php echo date('d.m.Y', $article->getDate()); ?></time>
      <?php echo $article->getContentPreview(); ?>
      <a href="<?php echo $article->getLink(); ?>"> weiter</a>
    </p>
  </article>

  <?php
      }

      if ( $page->getDestination() != '' ) {
        $dest = '/'.$page->getDestination().'/page';

      } else {
        $dest = '/page';
      }

      echo genPager($page->getTotalPagesCount(), $page->getStartPage(), $dest); ?>
    <?php } else { ?>
      <p class="info"><?php I18n::e('category.no_articles_found'); ?></p><?php
    } ?>