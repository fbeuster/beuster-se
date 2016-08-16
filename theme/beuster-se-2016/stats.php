
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">
    <h1>Statistiken</h1>

    <h2>Artikelranking (Letzten 10 Artikel)</h2>
    <table class="newslist">
      <thead>
        <tr>
          <th class="smallNumber">#</th>
          <th class="date">Datum</th>
          <th class="bigNumber">Hits</th>
          <th class="bigNumber">Hits/d</th>
          <th>Artikel</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['last'])) {
          $i = 0;
          foreach($data['last'] as $entry) { ?>
        <tr>
          <td><?php echo ($i+1); ?></td>
          <td><?php echo $entry['date']; ?></td>
          <td><?php echo $entry['hits']; ?></td>
          <td><?php echo $entry['hitsPerDay']; ?></td>
          <td><a href="<?php echo $entry['Link']; ?>"><?php echo $entry['Titel']; ?></a></td>
        </tr>
      <?php
            $i++;
          }
        } ?>
      </tbody>
    </table>

    <h2>Artikelranking (Top10 Aufrufe)</h2>
    <table class="newslist">
      <thead>
        <tr>
          <th class="smallNumber">#</th>
          <th class="date">Datum</th>
          <th class="bigNumber">Hits</th>
          <th class="bigNumber">Hits/d</th>
          <th>Artikel</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['top'])) {
          $i = 0;
          foreach($data['top'] as $entry) { ?>
        <tr>
          <td><?php echo ($i+1); ?></td>
          <td><?php echo $entry['date']; ?></td>
          <td><?php echo $entry['hits']; ?></td>
          <td><?php echo $entry['hitsPerDay']; ?></td>
          <td><a href="<?php echo $entry['Link']; ?>"><?php echo $entry['Titel']; ?></a></td>
        </tr>
      <?php
            $i++;
          }
        } ?>
      </tbody>
    </table>

    <h2>Downloads</h2>
    <table class="newslist">
      <thead>
        <tr>
          <th class="bigNumber">Downl.</th>
          <th>Programm</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['down'])){
          $i = 0;
          foreach($data['down'] as $down){ ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?>>
          <td><?php echo $down['down']; ?></td>
          <td><?php echo $down['name']; ?></td>
        </tr>
       <?php
            $i++;
          }
        } ?>
      </tbody>
    </table>
  </section>

  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>