  
  <article>
    <?php if(count($data['down'])) {
      $down = $data['down']; ?>
    <h1><?php echo $down['name']; ?></h1>
    <p><?php echo $down['descr']; ?></p>
    <p>
      <a rel="license" href="http://creativecommons.org/licenses/<?php echo $down['lic']; ?>/3.0/">
        <img alt="Creative Commons Lizenzvertrag" style="border-width:0; float: left; margin: 2px 5px" src="http://i.creativecommons.org/l/<?php echo $down['lic']; ?>/3.0/88x31.png" class="cc" />
      </a>
      <span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type"><?php echo $down['name']; ?></span>
      von <a xmlns:cc="http://creativecommons.org/ns#" href="http://beusterse.de" property="cc:attributionName" rel="cc:attributionURL">Felix Beuster</a>
      steht unter einer <a rel="license" href="http://creativecommons.org/licenses/<?php echo $down['lic']; ?>/3.0/"><?php echo lic($down['lic']); ?></a>.
    </p>
    <p>
    <?php if(isset($data['download']) && $data['download'] == $down['path']) { ?>
      Download startet automatisch in 3 Sekunden. Wenn nicht, <a href="/<?php echo $down['path']; ?>">hier</a> klicken.<br />
    <?php } else { ?>
      <a href="/<?php echo $down['id'].'/downloads/'.buildLinkTitle($down['name']).'/'.$down['val'].'-'.md5($down['fileID']); ?>">
        Download <?php echo $down['name'].' '.$down['ver'].' ('.$down['size'].')'; ?> - <?php echo $down['anz']; ?> Downloads
      </a><br />
    <?php }
      if($down['log'] != 0) {       
        if(isset($data['download']) && $data['download'] == $down['logpath']) { ?>
      Download startet automatisch in 3 Sekunden. Wenn nicht, <a href="/<?php echo $down['logpath']; ?>">hier</a> klicken.
        <?php } else { ?>
      <a href="/<?php echo $down['id'].'/downloads/'.buildLinkTitle($down['name']).'/'.$down['val'].'-'.md5($down['log']); ?>">Download Changelog (<?php echo $down['logsize']; ?>) - <?php echo $down['loganz']; ?> Downloads</a>
        <?php }
      }
    } else {
       // Fehler
    } ?>
    </p>
  </article>