
  <div class="beContentEntry">
  	<h1 class="beContentEntryHeader">Artikelübersicht</h1>
  	<p>
  		Insgesamt wurden <?php echo count($data['news']); ?> Artikel
      und <?php echo $data['cmtAmount']; ?> Kommentare verfasst. <?php echo $data['enaAmount']; ?> Artikel warten noch auf Freischaltung.
  	</p>
    <table class="backendTable">
      <thead>
        <tr class="backendTableHeadRow">
          <th>#</th>
          <th>ID</th>
          <th>Datum</th>
          <th>Hits</th>
          <th>Hits/d</th>
          <th>Artikel</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['news'])) {
          $i = 0;
          foreach($data['news'] as $entry) { ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?>>
          <td class="tabZahl w30"><?php echo ($i+1); ?></td>
          <td class="tabZahl w30"><?php echo $entry['id']; ?></td>
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
    <p class="backendBackLink">
      <a href="/admin" class="back">Zurück zur Administration</a>
    </p>
  </div>