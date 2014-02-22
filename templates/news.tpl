<?php 
  if (count($data['news'])) { 
    if($data['news'][0]['CatDescr'] !== '') { ?>
  <h1 class="beCategoryHeader"><?php echo $data['news'][0]['CatDispName']; ?> - beuster{se}</h1>
  <?php } ?>
    <menu class="beCategoryNav">
      <?php echo genSubMenu($db); ?>
    </menu>
  
    <div id="beMainContent">
  <?php

  $i = 0;
  $last = count($data['news']) - 1;
  foreach ($data['news'] as $beitrag) { ?>
  
  <?php if($i%2==0) { ?><div class="beContentEntryRow"><?php } ?>
  <article class="beContentEntry">
    <h2 class="beContentEntryHeader"><a href="<?php echo getLink($db, $beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']);?>"><?php echo $beitrag['Titel']; ?></a></h2>
    <div class="beContentEntryFooter">
      <span class="categoryLink">Kategorie: <a href="/<?php echo lowerCat($beitrag['Cat']); ?>"><?php echo $beitrag['Cat']; ?></a></span>
      <span class="authorLink">von <?php echo $beitrag['Autor']; ?></span>
      <span class="commentsLink">
        <a href="<?php echo getLink($db, $beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']);?>#comments">
          <?php echo $beitrag['Cmt']; ?> Kommentare
        </a>
      </span>
      <br class="clear">
    </div>
    <?php if($beitrag['thumb'] !== 0) {
      $cssClasses = 'thumb';
      if($beitrag['playlist'] == 1) {
        $cssClasses .= ' playlist noShadow';
      } ?>
    <img src="<?php echo $beitrag['thumb']; ?>" class="beContentEntryThumb <?php echo $cssClasses; ?>" alt="Vorschaubild">
    <?php } ?>
    <p>
      <time class="beContentEntryTime"  datetime="<?php echo $beitrag['datAttr']; ?>"><?php echo $beitrag['Datum']; ?></time>
      <?php echo str_replace('###link###', getLink($db, $beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']), $beitrag['Inhalt'])."\n"; ?>
    </p>
  </article>

  <?php if($i%2 == 1 || $i == $last) { ?></div><?php } ?>

<?php
    $i++;
  }
    if((isset($_GET['p']) &&
      $_GET['p'] != 'blog') ||
      isset($_GET['c']) ||
      $beitrag['archive'] == 1) {
      $dest = '/'.$beitrag['dest'].'/page';
    } else {
      $dest = '/page';
    }
    echo genPager($beitrag['seitenzahl'], $beitrag['start'], $dest, $mob); ?>
  <?php } else { ?>
  <p class="info">
    Es sind keine News vorhanden
  </p><?php
 }?>