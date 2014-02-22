  
  <div class="beContentEntry">
    <h1 class="beContentEntryHeader">Downloads</h1>
    <?php if($downSum = count($data['down'])) { ?>
      <p class="smallInfo">
        Hinweise zu Lizenzen und Verwendungsrichtlinien, gibt es gegebenenfalls auf den jeweiligen Downloadseiten.<br>
        Insgesamt <?php echo $downSum; ?> Downloads in <?php echo count($data['downCats']); ?> Kategorien verfügbar.
      </p>
      <?php foreach($data['downCats'] as $c) { ?>
        <h2 class="downcat"><?php echo $c; ?></h2>
        <?php foreach($data['down'] as $d) {
          if($d['cat'] == $c) {?>
        <h3 class="downloads"><a href="<?php echo '/'.$d['id'].'/downloads/'.buildLinkTitle($d['name']); ?>"><?php echo $d['name']; ?></a></h3>
        <p><?php echo str_replace('###link###', '/'.$d['id'].'/downloads/'.buildLinkTitle($d['name']), $d['descr']); ?></p>
          <?php }
        }
      }
    } ?>
  </div>