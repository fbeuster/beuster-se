<?php

  $page = Lixter::getLix()->getPage();

  if ($page->hasArticles()) {  ?>

<section class="content">
  <?php if ($page->getType() !== Page::INDEX_PAGE) { ?>
    <span class="categoryTitle"><?php echo $page->getTitle(); ?></span>
  <?php } ?>

  <?php if ($page->getCategory()) { ?>
    <?php $parent = $page->getCategory()->getParent(); ?>
    <?php if ($parent) { ?>
      <a href="/<?php echo $parent->getNameUrl(); ?>" class="back">
        <?php I18n::e('utilities.back_link', array($parent->getName())); ?>
      </a>

    <?php } else if (count($page->getCategory()->getChildren())) { ?>
      <menu class="subcategories">
        <?php foreach ($page->getCategory()->getChildren() as $child) { ?>
          <li>
            <a href="/<?php echo $child->getNameUrl(); ?>">
              <?php echo $child->getName(); ?>
            </a>
          </li>
        <?php } ?>
      </menu>
    <?php } ?>
  <?php } ?>

<?php

  foreach ($page->getArticles() as $article) {
    if($article->getThumbnail() != null) {
      if($article->isPlaylist()) {
        $thumb_src = $article->getThumbnail();
      } else {
        $thumb_src = $article->getThumbnail()->getPathThumb(800, 450);
      }
    } else {
      $thumb_src = Lixter::getLix()->getTheme()->getFile('assets/img/default_thumbnail_800_450.jpg');
    } ?>

  <section class="article">
    <div class="thumb">
      <a href="<?php echo $article->getLink();?>">
        <img src="/<?php echo $thumb_src; ?>" alt="<?php echo $article->getTitle(); ?>">
      </a>
    </div>
    <div class="content">
      <a class="header" href="<?php echo $article->getLink();?>">
        <h2><?php echo $article->getTitle(); ?></h2>
      </a>
      <p>
        <?php
          $length = 250;
          echo $article->getContentPreview($length)."\n";
        ?>
      </p>
      <i class="info">
        <?php I18n::e('article.info.by'); ?>
        <a href="/<?php echo $article->getAuthor()->getName(); ?>">
          <?php echo $article->getAuthor()->getClearname(); ?>
        </a>

        <?php I18n::e('article.info.on'); ?>
        <time datetime="<?php echo date('c', $article->getDate()); ?>">
          <?php echo date('d.m.Y', $article->getDate()); ?>
        </time>

        <?php I18n::e('article.info.in'); ?>
        <a href="/<?php echo $article->getCategory()->getNameUrl(); ?>">
          <?php echo $article->getCategory()->getName(); ?>
        </a>
      </i>
      <a class="more" href="<?php echo $article->getLink();?>">
        <?php I18n::e('article.preview.read_more'); ?>
      </a>
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