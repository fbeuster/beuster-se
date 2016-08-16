
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">
  	<h1>Artikelübersicht</h1>
  	<p>
  		Insgesamt wurden <?php echo count($data['news']); ?> Artikel
      und <?php echo $data['cmtAmount']; ?> Kommentare verfasst. <?php echo $data['enaAmount']; ?> Artikel warten noch auf Freischaltung.
  	</p>
    <table class="newslist">
      <thead>
        <tr>
          <th class="smallNumber">#</th>
          <th class="smallNumber">ID</th>
          <th class="date">Datum</th>
          <th class="bigNumber">Hits</th>
          <th class="bigNumber">Hits/d</th>
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
  </section>

  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>
