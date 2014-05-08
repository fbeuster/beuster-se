  <?php 
  if (count($data['articles'])) {
    /*if($data['articles'][0]['CatDescr'] !== '') { ?>
      <h1 class="beCategoryHeader"><?php echo $data['articles'][0]->getCategory(); ?> - beuster{se}</h1>
    <?php }*/ ?>
    <menu class="beCategoryNav">
      <?php echo genSubMenu(); ?>
    </menu>
  
    <div id="beMainContent">
    <?php

      $i = 0;
      $last = count($data['articles']) - 1;
      foreach ($data['articles'] as $article) { ?>
  
        <?php if($i%2==0) { ?><div class="beContentEntryRow"><?php } ?>
        <article class="beContentEntry">
          <h2 class="beContentEntryHeader"><a href="<?php echo $article->getLink();?>"><?php echo $article->getTitle(); ?></a></h2>
          <div class="beContentEntryFooter">
            <span class="categoryLink">Kategorie: <a href="/<?php echo lowerCat($article->getCategory()); ?>"><?php echo $article->getCategory(); ?></a></span>
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
            <img src="<?php echo $article->getThumbnail(); ?>" class="beContentEntryThumb <?php echo $cssClasses; ?>" alt="Vorschaubild">
            <?php } else { ?>
            <img src="<?php echo $article->getThumbnail()->getPathThumb(); ?>" class="beContentEntryThumb <?php echo $cssClasses; ?>" alt="Vorschaubild">
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
      if((isset($_GET['p']) &&
        $_GET['p'] != 'blog') ||
        isset($_GET['c']) ||
        $data['conf']['archive'] == 1) {
        $dest = '/'.$data['conf']['dest'].'/page';
      } else {
        $dest = '/page';
      }
      echo genPager($data['conf']['seitenzahl'], $data['conf']['start'], $dest, $mob); ?>
    <?php } else { ?>
      <p class="info">
        Es sind keine News vorhanden
      </p><?php
    } ?>