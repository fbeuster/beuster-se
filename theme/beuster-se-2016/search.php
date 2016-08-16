
<section class="content">
  <span class="categoryTitle">Suchergebnisse</span>
  <section class="search_info">
    <p>
      Du hast nach folgendem Begriff gesucht:
      <b><?php echo $data['str']; ?></b>
    </p>

  <?php

    if(isset($data['error'])) {
      echo '<p>'.$data['error'].'</p>';

    } else if(isset($data['result'])) {
      $results        = $data['anzRes'];
      $results_start  = $data['page'] * 5 - 4;

      if($data['anzRes'] < $end = $data['page'] * 5) {
        $results_end = $data['anzRes'];
      } else {
        $results_end = $end;
      }


  ?>
      <p>
        Ergebnisse <?php echo $results_start;?>
        bis <?php  echo $results_end; ?>
        von <?php echo $results; ?>
      </p>
    </section>
    <?php
      $i = 1;
      foreach($data['result'] as $result) {
        $article = $result->getArticle();

        if($article->getThumbnail() != null) {
          if($article->isPlaylist()) {
            $thumb_src = $article->getThumbnail();
          } else {
            $thumb_src = $article->getThumbnail()->getPathThumb();
          }
        } else {
          $thumb_src = Lixter::getLix()->getTheme()->getFile('assets/img/sample_800_450.jpg');
        }
    ?>

      <section class="article">
        <div class="thumb">
          <img src="<?php echo $thumb_src; ?>" alt="thumb">
        </div>
        <div class="content">
          <h2><?php echo $article->getTitle(); ?></h2>
          <p>
            <?php
              echo str_replace( '###link###',
                                $article->getLink(),
                                $result->getMarkedContent())."\n";
            ?>
          </p>
          <i class="info">
            by <?php echo $article->getAuthor()->getClearname(); ?> on
            <time datetime="<?php echo date('c', $article->getDate()); ?>">
              <?php echo date('d.m.Y', $article->getDate()); ?>
            </time>
            in
            <a href="/<?php echo $article->getCategory()->getNameUrl(); ?>">
              <?php echo $article->getCategory()->getName(); ?>
            </a>
          </i>
          <a class="more" href="<?php echo $article->getLink();?>">Read more</a>
        </div>
      </section>



    <?php
          $i++;
        }
      } else {
        echo '<p>Der angegebene Suchbegriff wurde nicht gefunden</p>';
      }
    ?>
  <?php

    if(isset($data['pageNbr']) && $data['pageNbr'] >= 1) {
      echo genPager($data['pageNbr'], $data['page'], '/search/'.$data['str'].'/page');
    }
  ?>
</section>
