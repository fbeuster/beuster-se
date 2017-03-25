<?php $search = Lixter::getLix()->getPage(); ?>

  <div class="beContentEntry">
    <h1 class="beContentEntryHeader"><?php echo $search->getTitle(); ?></h1>
    <?php echo $search->getSearchInfo(); ?>
    <?php
      if ($search->hasResults()) {
        foreach($search->getPagedSearchResults() as $result) {
          $article = $result->getArticle();
         ?>
    <article class="searchEntry">
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
    </article><?php
        } ?>
  </div>
  <?php
        echo genPager($search->getTotalPages(),
                      $search->getCurrentPage(),
                      '/search/'.$search->getSearchTerm().'/page');
      } ?>
