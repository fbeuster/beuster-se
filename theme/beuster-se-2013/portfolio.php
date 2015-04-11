<!--<div id="beMainContent">-->
  <div id="beMainPortfolio">
    <h1 class="bePortfolioHeader">Portfolio - beuster{se}</h1>
<?php
  if(count($data['portSets']) > 0) {
    foreach($data['portSets'] as $portSet) {
      echo '<h2 class="bePortfolioSubHeader">'.$portSet->getName().'</h2>';
      foreach($portSet->getItems() as $portItem) {
        $portImage = $portItem->getImage();
        echo '  <div class="bePortfolioEntry">'."\n";
        echo '    <img src="'.makeAbsolutePath($portImage->getPath()).'" title="'.$portImage->getTitle().'" name="'.$portImage->getTitle().'" alt="'.$portImage->getTitle().'" class="portImage">'."\n";
        echo '    <span class="infotext">'.$portItem->getTitle().'</span>'."\n";
        echo '  </div>'."\n";
      }
    }
  }
?>
    <br class="clear">