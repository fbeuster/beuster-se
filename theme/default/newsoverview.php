
  	<h1>Artikelübersicht</h1>
  	<p>
  		Insgesamt wurden <?php echo count($data['news']); ?> Artikel
      und <?php echo $data['cmtAmount']; ?> Kommentare verfasst. <?php echo $data['enaAmount']; ?> Artikel warten noch auf Freischaltung.
  	</p>
    <table>
      <thead>
        <tr>
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
          <td ><?php echo ($i+1); ?></td>
          <td ><?php echo $entry['id']; ?></td>
          <td ><?php echo $entry['date']; ?></td>
          <td ><?php echo $entry['hits']; ?></td>
          <td ><?php echo $entry['hitsPerDay']; ?></td>
          <td><a href="<?php echo $entry['Link']; ?>"><?php echo $entry['Titel']; ?></a></td>
        </tr>
      <?php
            $i++;
          }
        } ?>
      </tbody>
    </table>
    <p>
      <a href="/admin">Zurück zur Administration</a>
    </p>