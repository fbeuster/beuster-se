
<!--<div id="beMainContent">-->
  <div id="beMainPortfolio">
    <h1 class="bePortfolioHeader">Portfolio - beuster{se}</h1>
<?php
  if(count($data['portFiles']) > 0) {
    foreach($data['portFiles'] as $portFileGroup) {
      echo '<h2 class="bePortfolioSubHeader">'.$portFileGroup[0]['group'].'</h2>';
      foreach($portFileGroup as $portFile) {
        echo '  <div id="'.$portFile['id'].'" class="bePortfolioEntry">'."\n";
        echo '    <img src="'.$portFile['path'].'" title="'.$portFile['name'].'" name="'.$portFile['name'].'" alt="'.$portFile['name'].'" class="portImage">'."\n";
        echo '    <span class="infotext" style="display: none;">'.$portFile['text'].'</span>'."\n";
        echo '  </div>'."\n";
      }
    }
  }
?>
    <br class="clear">