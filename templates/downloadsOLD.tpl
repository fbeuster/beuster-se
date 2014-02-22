<h1>Downloads</h1>
      <?php if(count($data['down'])){
       foreach($data['down'] as $down){?>
      <h2><?php echo $down['name'];?> <span class="downGroup">(in <?php echo $down['cat'] ?>)</span></h2>
      <p>
       <?php echo $down['descr']; ?><br />
       <a rel="license" href="http://creativecommons.org/licenses/<?php echo $down['lic']; ?>/3.0/">
        <img alt="Creative Commons Lizenzvertrag" style="border-width:0; float: left; margin: 2px 5px" src="http://i.creativecommons.org/l/<?php echo $down['lic']; ?>/3.0/88x31.png" class="cc" />
       </a>
       <span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type"><?php echo $down['name']; ?></span>
       von <a xmlns:cc="http://creativecommons.org/ns#" href="http://beusterse.de" property="cc:attributionName" rel="cc:attributionURL">Felix Beuster</a>
       steht unter einer <a rel="license" href="http://creativecommons.org/licenses/<?php echo $down['lic']; ?>/3.0/"><?php echo lic($down['lic']); ?></a>.
       <br style="clear:left;">
       <?php
        if(isset($data['download']) && $data['download'] == $down['path']){
       ?>Download startet automatisch in 3 Sekunden. Wenn nicht, <a href="/<?php echo $down['path']; ?>">hier</a> klicken.<br />
        <?php } else { 
       ?><a href="/downloads/<?php echo md5($down['fileID']); ?>">
        Download <?php echo $down['name'].' '.$down['ver'].' ('.$down['size'].')'; ?> - <?php echo $down['anz']; ?> Downloads
       </a><br />
       <?php }
        if($down['log'] != 0){       
        if(isset($data['download']) && $data['download'] == $down['logpath']){
       ?>Download startet automatisch in 3 Sekunden. Wenn nicht, <a href="/<?php echo $down['logpath']; ?>">hier</a> klicken.
        <?php } else { 
       ?><a href="/downloads/<?php echo md5($down['log']); ?>">Download Changelog (<?php echo $down['logsize']; ?>) - <?php echo $down['loganz']; ?> Downloads</a>
       <?php } }}}
      ?>
      </p>