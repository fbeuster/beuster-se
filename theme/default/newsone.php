<?php
  if (count($data['news'])) {
    foreach ($data['news'] as $beitrag) {
      $url = 'http://beusterse.de'.replaceUml(getLink($beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']));
      $anhang = ' #beusterse ';
      if(strlen($beitrag['Titel']) > 61) {
        $text = substr($beitrag['Titel'], 0, 60).'...'.$anhang.$url;
      } else {
        $text = $beitrag['Titel'].$anhang.$url;
      } ?>

 <article>
    <h1><?php echo $beitrag['Titel']; ?></h1>
    <span value="<?php echo substr($beitrag['projState'], 0, 1); ?>">
      Status: <?php echo substr($beitrag['projState'], 1, strlen($beitrag['projState'])-1); ?>
    </span>
    <?php } ?>
    <?php echo $beitrag['Inhalt']."\n"; ?>
    <?php if(count($data['pics']) >= 1) { echo '<section>'.genGal($data['pics'], $mob).'</section>'; } ?>
  </article>

  <!-- comments -->
  <div>
    <h2 id="comments">Kommentare (<?php echo $beitrag['Cmt']; ?>)</h2>
    <?php
      $cmtReply = getLink($beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']);
      if(count($data['comments']) > 0) {
        foreach($data['comments'] as $cmt) {
          echo genComment($cmt, $cmtReply);
        }
        if($beitrag['seitenzahlC'] > 1) { ?>
    <div>
      <?php echo genPager($beitrag['seitenzahlC'], $beitrag['startC'], getLink($beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']).'/page', $mob); ?>
    </div>
      <?php } ?>
    <?php } else { ?>
    <div>
      <p>Keine Kommentare vorhanden.</p>
    </div>
    <?php } ?>
    <div id="newComment">
      <span>Schreibe einen Kommentar!</span>
       <?php $err = array('t' => $data['eType'], 'c' => $data['ec']);
       echo genFormPublic($err, $cmtReply, time(), $mob, $bbCmt, 'Kommentar schreiben', 'commentForm', $data['comment_reply']); ?>
    </div>
  </div>
        
  <?php }  ?>
<?php }  ?>