<?php
  if (count($data['articles'])) {  ?>
<section class="content">
  <?php echo makeCategoryTitle($currPage); ?>

  <?php

  $i = 0;
  $last = count($data['articles']) - 1;
  foreach ($data['articles'] as $article) {

    if($article->getThumbnail() != null) {
      if($article->isPlaylist()) {
        $thumb_src = $article->getThumbnail();
      } else {
        $thumb_src = $article->getThumbnail()->getPathThumb();
      }
    } else {
      $thumb_src = Lixter::getLix()->getTheme()->getFile('assets/img/sample_800_450.jpg');
    } ?>

  <section class="article">
    <div class="thumb">
      <img src="<?php echo $thumb_src; ?>" alt="thumb">
    </div>
    <div class="content">
      <h2><?php echo $article->getTitle(); ?></h2>
      <p>
        <?php
          $length = 500;
          echo str_replace( '###link###',
                            $article->getLink(),
                            $article->getContentPreview($length))."\n";
        ?>
      </p>
      <i class="info">
        by <?php echo $article->getAuthor()->getClearname(); ?> on
        <time datetime="<?php echo date('c', $article->getDate()); ?>">
          <?php echo date('d.m.Y', $article->getDate()); ?>
        </time>
        in
        <a href="/<?php echo $article->getCategory()->getNameUrl(); ?>">
          <?php echo $article->getCategory()->getName(); ?>
        </a>
      </i>
      <a class="more" href="<?php echo $article->getLink();?>">Read more</a>
    </div>
  </section>

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
      echo '<br class="clear">'."\r";
      echo genPager($data['conf']['seitenzahl'], $data['conf']['start'], $dest); ?>
  <?php } else { ?>
  <p>
    Es sind keine News vorhanden
  </p><?php
 }?>