
    </div>
    <!-- ende beMainContent -->
    <!-- beMainAside -->
    <aside id="beMainAside" class="<?php echo $pageType; ?>">
      <?php
      $page = Lixter::getLix()->getPage();

      echo (new SidebarModuleSearch( array("classes" => "searchBox") ))->getHTML();
      SidebarModuleSocial::html();
      echo moduleDonate();

      if($currPage == 'index') {
        SidebarModuleListArticles::html(5, SidebarModuleListArticles::TYPE_MOST);

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

      SidebarModuleArchive::html(); ?>
    </aside>
    <!-- ende beMainAside -->
    <br class="clear">