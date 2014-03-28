
    <h1>Portfolio - beuster{se}</h1>
<?php
  if(count($data['portFiles']) > 0) {
    foreach($data['portFiles'] as $portFileGroup) {
      echo '<h2>'.$portFileGroup[0]['group'].'</h2>';
      foreach($portFileGroup as $portFile) {
        echo '  <div id="'.$portFile['id'].'">'."\n";
        echo '    <img src="'.$portFile['path'].'" title="'.$portFile['name'].'" name="'.$portFile['name'].'" alt="'.$portFile['name'].'" class="portImage">'."\n";
        echo '    <span style="display: none;">'.$portFile['text'].'</span>'."\n";
        echo '  </div>'."\n";
      }
    }
  }
?>