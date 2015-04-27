<?php
  if($currPage == 'portfolio') {
    $asideClass = 'portfolio';
  } else {
    $asideClass = $pageType;
  }
?>

    <aside>
      <?php
      echo moduleSearch();
      echo moduleSocialShare();
      echo moduleDonate();
      if($currPage == 'index') {
        echo moduleTopArticles();
      } else if($currPage == 'single') {
        $cnt = Lixter::getLix()->getContent();
        echo moduleArticleInfo($cnt['data']['aside']);
        echo moduleRandomArticle();
      } else if($currPage == 'page') {
        echo moduleRandomArticle();
      } else {
        echo moduleRandomArticle();
      }
      echo moduleAdSenseAside($noGA);
      echo moduleArchive(); ?>
    </aside>