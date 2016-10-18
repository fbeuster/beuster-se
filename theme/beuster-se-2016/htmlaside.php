<?php
  if($currPage == 'portfolio') {
    $asideClass = 'portfolio';
  } else {
    $asideClass = $pageType;
  }
?>

    <aside>
      <?php
      if (false) {
      $page = Lixter::getLix()->getPage();

      echo moduleSearch();
      echo moduleSocialShare();
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
      echo moduleArchive();
      } ?>
    </aside>