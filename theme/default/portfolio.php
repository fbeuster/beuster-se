
    <h1>Portfolio</h1>
<?php
  if(count($data['portSets']) > 0) {
    foreach($data['portSets'] as $portSet) {
      echo '<h2>'.$portSet->getName().'</h2>';
      foreach($portSet->getItems() as $portItem) {
        $portImage = $portItem->getImage();
        echo '  <div>'."\n";
        echo '    <img src="'.$portImage->getPath().'" title="'.$portImage->getTitle().'" name="'.$portImage->getTitle().'" alt="'.$portImage->getTitle().'" class="portImage">'."\n";
        echo '    <span class="infotext">'.$portItem->getTitle().'</span>'."\n";
        echo '  </div>'."\n";
      }
    }
  }
?>