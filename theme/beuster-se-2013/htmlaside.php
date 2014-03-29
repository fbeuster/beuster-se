<?php
  if($currPage == 'portfolio') {
    $asideClass = 'portfolio';
  } else {
    $asideClass = $pageType;
  }
?>


    </div>
    <!-- ende beMainContent -->
    <!-- beMainAside -->
    <aside id="beMainAside" class="<?php echo $asideClass; ?>">
      <?php 
      echo moduleSearch();
      echo moduleSocialShare($mob);
      echo moduleDonate();
      if($currPage == 'index') {
        echo moduleTopArticles($mob);
      } else if($currPage == 'single') {
        echo moduleArticleInfo($mob,  $ret['data']['aside']);
        echo moduleRandomArticle($mob);
      } else if($currPage == 'page') {
        echo moduleRandomArticle($mob);
      } else {
        echo moduleRandomArticle($mob);
      }
      echo moduleAdSenseAside($mob, $local, $noGA);
      echo moduleArchive($mob); ?>
    </aside>
    <!-- ende beMainAside -->
    <br class="clear">