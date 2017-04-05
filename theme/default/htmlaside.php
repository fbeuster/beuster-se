    <aside>
      <?php
      $page = Lixter::getLix()->getPage();

      echo (new SidebarModuleSearch( array("classes" => "searchBox") ))->getHTML();
      echo moduleDonate();

      if($currPage == 'index') {
        echo moduleTopArticles();

      } else if($page->getType() == Page::ARTICLE_PAGE) {
        echo moduleArticleInfo($page);
        echo moduleRandomArticle();

      } else if($currPage == 'page') {
        echo moduleRandomArticle();

      } else {
        echo moduleRandomArticle();
      }
      echo moduleAdSenseAside($noGA);
      SidebarModuleArchive::html(); ?>
    </aside>