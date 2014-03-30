<?php 
  if (count($data['news'])) { 
    if($data['news'][0]['CatDescr'] !== '') { ?>
  <h1><?php echo $data['news'][0]['CatDispName']; ?> - beuster{se}</h1>
  <?php } ?>
    <menu>
      <?php echo genSubMenu(); ?>
    </menu>
    
  <?php

  $i = 0;
  $last = count($data['news']) - 1;
  foreach ($data['news'] as $beitrag) { ?>
  
  <article>
    <h2><a href="<?php echo getLink($beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']);?>"><?php echo $beitrag['Titel']; ?></a></h2>
    <div>
      <span>Kategorie: <a href="/<?php echo lowerCat($beitrag['Cat']); ?>"><?php echo $beitrag['Cat']; ?></a></span>
      <span>von <?php echo $beitrag['author']->getClearname(); ?></span>
      <span>
        <a href="<?php echo getLink($beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']);?>#comments">
          <?php echo $beitrag['Cmt']; ?> Kommentare
        </a>
      </span>
    </div>
    <img src="<?php echo $beitrag['thumb']; ?>" alt="Vorschaubild">
    <?php } ?>
    <p>
      <time datetime="<?php echo $beitrag['datAttr']; ?>"><?php echo $beitrag['Datum']; ?></time>
      <?php echo str_replace('###link###', getLink($beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']), $beitrag['Inhalt'])."\n"; ?>
    </p>
  </article>

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
  <p>
    Es sind keine News vorhanden
  </p><?php
 }?>