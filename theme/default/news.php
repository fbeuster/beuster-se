<?php
  if (count($data['articles'])) {
    /* if($data['articles'][0]['CatDescr'] !== '') { ?>
  <h1><?php echo $data['articles'][0]['CatDispName']; ?> - beuster{se}</h1>
  <?php } */ ?>
    <menu>
      <?php echo genSubMenu(); ?>
    </menu>

  <?php

  $i = 0;
  $last = count($data['articles']) - 1;
  foreach ($data['articles'] as $article) { ?>

  <article>
    <h2><a href="<?php $article->getLink();?>"><?php echo $article->getTitle(); ?></a></h2>
    <div>
      <span>Kategorie: <a href="/<?php echo $article->getCategory()->getNameUrl(); ?>"><?php echo $article->getCategory()->getName(); ?></a></span>
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
      <?php echo str_replace('###link###', $article->getLink(), $article->getContentPreview())."\n"; ?>
    </p>
  </article>

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
      echo genPager($data['conf']['seitenzahl'], $data['conf']['start'], $dest); ?>
  <?php } else { ?>
  <p>
    Es sind keine News vorhanden
  </p><?php
 }?>