<?php $search = Lixter::getLix()->getPage(); ?>

<section class="content">
  <span class="categoryTitle"><?php echo $search->getTitle(); ?></span>
  <?php echo $search->getSearchInfo(); ?>
  <?php if ($search->hasResults()) {
    foreach($search->getPagedSearchResults() as $result) {
      $article = $result->getArticle();

      if($article->getThumbnail() == null) {
        $thumb_src = Lixter::getLix()->getTheme()->getFile('assets/img/default_thumbnail_800_450.jpg');

      } else {
        if($article->isPlaylist()) {
          $thumb_src = $article->getThumbnail();
        } else {
          $thumb_src = $article->getThumbnail()->getPathThumb(800, 450);
        }
      }
?>
      <section class="article">
        <div class="thumb">
          <img src="<?php echo $thumb_src; ?>" alt="thumb">
        </div>
        <div class="content">
          <h2><?php echo $result->getMarkedTitle(); ?></h2>
          <p>
            <?php echo $result->getMarkedContent(250);?>
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
    <?php }

    echo genPager($search->getTotalPages(),
                  $search->getCurrentPage(),
                  '/search/'.$search->getSearchTerm().'/page');
  } ?>
