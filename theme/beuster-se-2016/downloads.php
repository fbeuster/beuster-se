
    <h1>Downloads</h1>
    <?php if($data['downStat']['no'] > 0) { ?>
      <p>
        Hinweise zu Lizenzen und Verwendungsrichtlinien, gibt es gegebenenfalls auf den jeweiligen Downloadseiten.<br>
        Insgesamt <?php echo $data['downStat']['no']; ?> Downloads in <?php echo count($data['downSets']); ?> Kategorien verfügbar.
      </p>
      <?php foreach($data['downSets'] as $c) { ?>
        <h2><?php echo $c->getCatName(); ?></h2>
        <?php 
          foreach ($c->getDownloadFiles() as $d) { ?>
        <h3><a href="<?php echo '/'.$d->getId().'/downloads/'.buildLinkTitle($d->getName()); ?>"><?php echo $d->getName(); ?></a></h3>
        <p><?php echo str_replace('###link###', '/'.$d->getId().'/downloads/'.buildLinkTitle($d->getName()), $d->getDescriptionParsed()); ?></p>
          <?php
        }
      }
    } ?>
  </div>