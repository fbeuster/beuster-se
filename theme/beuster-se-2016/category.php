<?php

  $page = Lixter::getLix()->getPage();

  if ($page->hasArticles()) {  ?>

<section class="content">
  <span class="categoryTitle"><?php echo $page->getTitle(); ?></span>

<?php

  foreach ($page->getArticles() as $article) {
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
      <img src="/<?php echo $thumb_src; ?>" alt="thumb">
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
  }

  if ( $page->getDestination() != '' ) {
    $dest = '/'.$page->getDestination().'/page';

  } else {
    $dest = '/page';
  }
  echo '<br class="clear">'."\r";
  echo genPager($page->getTotalPagesCount(), $page->getStartPage(), $dest);

?>

</section>

  <?php } else { ?>
  <section class="content">
    <section class="article">
      <p><?php I18n::e('category.no_articles_found'); ?></p>
    </section>
  </section><?php
 }?>