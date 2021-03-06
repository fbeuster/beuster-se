<?php

  $lb     = Lixter::getLix()->getLinkBuilder();
  $search = Lixter::getLix()->getPage(); ?>

    <h1><?php echo $search->getTitle(); ?></h1>
      <?php echo $search->getSearchInfo(); ?>
      <?php if ($search->hasResults()) {
        foreach($search->getPagedSearchResults() as $result) {
          $article = $result->getArticle();
         ?>
    <article>
      <header>
        <p>
          <time><?php echo date('d.m.Y', $article->getDate()); ?></time>
          <a href="<?php echo $article->getLink();?>" class="title"><?php echo $result->getMarkedTitle(); ?></a>
          <span>
            <a href="<?php echo $article->getLink();?>">
              <?php echo count($article->getComments()); ?> Kommentare
            </a>
          </span>
          <span>
            Kategorie: <a href="/<?php echo $article->getCategory()->getName(); ?>"><?php echo $article->getCategory()->getName(); ?></a>
          </span>
        </p>
      </header>
      <p>
        <?php echo $result->getMarkedContent();?>
        <a href="<?php echo $article->getLink(); ?>"> weiter</a>
      </p>
            <?php echo $article->getContentPreview(); ?>
    </article>
    <?php }

    echo genPager($search->getTotalPages(),
                  $search->getCurrentPage(),
                  $search->getLink().$lb->makePageAppendix());
  } ?>