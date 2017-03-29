
    </div>
    <!-- ende beMainContent -->
    <!-- beMainAside -->
    <aside id="beMainAside" class="<?php echo $pageType; ?>">
      <?php
      $page = Lixter::getLix()->getPage();

      echo moduleSearch();
      echo moduleSocialShare();
      echo moduleDonate();

      if($currPage == 'index') {
        echo moduleTopArticles();

      } else if($page->getType() == Page::ARTICLE_PAGE) {
        echo moduleArticleInfo($page);
        echo moduleAmazon(180, 150);
        echo moduleRandomArticle();

      } else if($currPage == 'page') {
        echo moduleRandomArticle();

      } else {
        echo moduleRandomArticle();
      }
      echo moduleAdSenseAside($noGA);
      echo moduleArchive(); ?>
    </aside>
    <!-- ende beMainAside -->
    <br class="clear">