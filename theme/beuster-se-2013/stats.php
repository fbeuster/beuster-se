  <div class="beContentEntry">
    <h1 class="beContentEntryHeader">Statistiken</h1>
    <h2>Artikelranking (Letzten 10 Artikel)</h2>
    <table class="backendTable">
      <thead>
        <tr class="backendTableHeadRow">
          <th>#</th>
          <th>Datum</th>
          <th>Hits</th>
          <th>Hits/d</th>
          <th>Artikel</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['last'])) {
          $i = 0;
          foreach($data['last'] as $entry) { ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?>>
          <td class="tabZahl w30"><?php echo ($i+1); ?></td>
          <td class="fullDate"><?php echo $entry['date']; ?></td>
          <td class="tabZahl w50"><?php echo $entry['hits']; ?></td>
          <td class="tabZahl w50"><?php echo $entry['hitsPerDay']; ?></td>
          <td><a href="<?php echo $entry['Link']; ?>"><?php echo $entry['Titel']; ?></a></td>
        </tr>
      <?php
            $i++;
          }
        } ?>
      </tbody>
    </table>
    <h2>Artikelranking (Top10 Aufrufe)</h2>
    <table class="backendTable">
      <thead>
        <tr class="backendTableHeadRow">
          <th>#</th>
          <th>Datum</th>
          <th>Hits</th>
          <th>Hits/d</th>
          <th>Artikel</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['top'])) {
          $i = 0;
          foreach($data['top'] as $entry) { ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?>>
          <td class="tabZahl w30"><?php echo ($i+1); ?></td>
          <td class="fullDate"><?php echo $entry['date']; ?></td>
          <td class="tabZahl w50"><?php echo $entry['hits']; ?></td>
          <td class="tabZahl w50"><?php echo $entry['hitsPerDay']; ?></td>
          <td><a href="<?php echo $entry['Link']; ?>"><?php echo $entry['Titel']; ?></a></td>
        </tr>
      <?php
            $i++;
          }
        } ?>
      </tbody>
    </table>
    <h2>Downloads</h2>
    <table class="backendTable noWidth">
      <thead>
        <tr class="backendTableHeadRow">
          <th>Programm</th>
          <th>Downloads</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['down'])){
          $i = 0;
          foreach($data['down'] as $down){ ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?>>
          <td><?php echo $down['name']; ?></td>
          <td class="tabZahl"><?php echo $down['down']; ?></td>
        </tr>
       <?php
            $i++;
          }
        } ?>
      </tbody>
    </table>
    <p class="backendBackLink"><a href="/admin">Zurück zur Administration</a></p>
  </div>