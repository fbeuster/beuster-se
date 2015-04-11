
  <div class="beContentEntry">
    <h1 class="beContentEntryHeader">Suchergebnisse</h1>
      <p>Du hast nach folgendem Begriff gesucht: <b><?php echo $data['str']; ?></b></p>
      <?php
       if(isset($data['error']))
       {
        echo '<p>'.$data['error'].'</p>';
       } else if(isset($data['result'])){?>
        <p class="searchInfo">
         Ergebnisse <?php echo $data['page'] * 5 - 4;?> bis <?php if($data['anzRes'] < $end = $data['page'] * 5) echo $data['anzRes']; else echo $end; ?>
         von <?php echo $data['anzRes']; ?>
       </p><?php
        $i = 1;
        foreach($data['result'] as $result)
        {
         ?>
    <article class="searchEntry">
      <header>
        <p>
          <time><?php echo $result['dat'] ?></time>
          <a href="<?php echo getLink($result['cat'], $result['id'], $result['tit']);?>" class="title"><?php echo $result['tit']; ?></a>
          <span>
            <a href="<?php echo getLink($result['cat'], $result['id'], $result['tit']);?>">
              <?php echo $result['cmt']; ?> Kommentare
            </a>
          </span>
          <span>
            Kategorie: <a href="/<?php echo $result['cat']; ?>"><?php echo $result['cat']; ?></a>
          </span>
        </p>
      </header>
      <p><?php echo str_replace('###link###', getLink($result['cat'], $result['id'], $result['tit']), $result['inh'])."\n";?></p>
    </article><?php
         $i++;
        }
       } else {
        echo '<p>Der angegebene Suchbegriff wurde nicht gefunden</p>';
       }
      ?>
  </div>
  <?php
    if($data['pageNbr'] >= 1) {
      echo genPager($data['pageNbr'], $data['page'], '/search/'.$data['str'].'/page', $mob);
    }
  ?>