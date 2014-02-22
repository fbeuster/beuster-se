
<!--<div id="beMainContent">-->
  <div id="beMainPortfolio">
    <h1 class="bePortfolioHeader">Portfolio - beuster{se}</h1>
<?php
  if(count($data['portFiles']) > 0) {
    foreach($data['portFiles'] as $portFileGroup) {
      echo '<h2 class="bePortfolioSubHeader">'.$portFileGroup[0]['group'].'</h2>';
      /*echo '<ul class="portImgList">'."\n";
      foreach($portFileGroup as $portFile) {
        if(!$mob) {
          $href = '#'.$portFile['id'];
        } else {
          $href = $portFile['path'];
        }
        echo ' <li><a href="'.$href.'"><img src="'.$portFile['path'].'" title="'.$portFile['name'].'" alt="'.$portFile['name'].'" class="portThumb" name="#'.$portFile['id'].'"></a></li>'."\n";
      }
      echo '</ul>'."\n";*/
      //echo '<div class="portSlider">'."\n";
      /*if(!$mob) {
        $width = ' style="width: '.(count($portFileGroup) * 850).'px;"';
      } else {
        $width = '';
      }*/
      //echo ' <div'.$width.'>'."\n";
      foreach($portFileGroup as $portFile) {
        //echo '  <article id="'.$portFile['id'].'">'."\n";
        echo '  <div id="'.$portFile['id'].'" class="bePortfolioEntry">'."\n";
        //echo '   <p>'."\n";
        //if($mob) echo '    <a href="'.$portFile['path'].'>'."\n";
        echo '    <img src="'.$portFile['path'].'" title="'.$portFile['name'].'" name="'.$portFile['name'].'" alt="'.$portFile['name'].'" class="portImage">'."\n";
        //if($mob) echo '    </a>'."\n";
        echo '    <span class="infotext" style="display: none;">'.$portFile['text'].'</span>'."\n";
        //echo '   </p>'."\n";
        echo '  </div>'."\n";
        //echo '  </article>'."\n";
      }
      /*echo ' </div>'."\n";
      echo '</div>'."\n";*/
    }
  }
?>
    <br class="clear">
  <!--</div>-->