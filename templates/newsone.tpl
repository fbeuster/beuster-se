<?php
  if (count($data['news'])) {
    foreach ($data['news'] as $beitrag) {
      $url = 'http://beusterse.de'.replaceUml(getLink($db, $beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']));
      $anhang = ' #beusterse ';
      if(strlen($beitrag['Titel']) > 61) {
        $text = substr($beitrag['Titel'], 0, 60).'...'.$anhang.$url;
      } else {
        $text = $beitrag['Titel'].$anhang.$url;
      } ?>

 <article class="beContentEntry">
    <span class="socialTxt" title="<?php echo $text; ?>" style="display: none;"><?php echo $text; ?></span>
    <h1 class="beContentEntryHeader"><?php echo $beitrag['Titel']; ?></h1>
    <?php if($beitrag['projState'] !== 0){ 
      switch(substr($beitrag['projState'], 0, 1)){
        case '1': $projStyle = 'background: #70d015; color: #000000;'; break;
        case '2': $projStyle = 'background: #efef15; color: #000000;'; break;
        case '3': $projStyle = 'background: #ff8015; color: #000000;'; break;
        case '4': $projStyle = 'background: #efefef; color: #2b2b2b;'; break;
        default: $projStyle = ''; break;
      }
    ?>
    <span class="projState" value="<?php echo substr($beitrag['projState'], 0, 1); ?>" style="<?php echo $projStyle; ?>">
      Status: <?php echo substr($beitrag['projState'], 1, strlen($beitrag['projState'])-1); ?>
    </span>
    <?php } ?>
    <?php echo $beitrag['Inhalt']."\n"; ?>
    <?php if(count($data['pics']) >= 1) { echo '<section>'.genGal($data['pics'], $mob).'</section>'; } ?>
  </article>

  <?php if(false) { ?>
  <!-- connected articles -->
  <div class="beContentConnected">
    <div class="beContentConnectedEntry odd">
      <div class="beContentConnectedEntryThumb"></div>
      <p>
        Ich bin ein ähnlicher bzw. verbundener Artikel. Man kann mich auch lesen.
      </p>
      <br class="clear">
    </div>
    <div class="beContentConnectedEntry even">
      <div class="beContentConnectedEntryThumb"></div>
      <p>
        Ich bin ein ähnlicher bzw. verbundener Artikel. Man kann mich auch lesen.
      </p>
      <br class="clear">
    </div>
  </div>
  <br class="clear">
  <?php } ?>

  <!-- comments -->
  <div id="beContentEntryComments">
    <h2 id="comments" class="beContentEntryCommentsHeader">Kommentare (<?php echo $beitrag['Cmt']; ?>)</h2>
    <?php
      $cmtReply = getLink($db, $beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']);
      if(count($data['comments']) > 0) {
        foreach($data['comments'] as $cmt) {

          echo genComment($cmt, $cmtReply);
        }
        if($beitrag['seitenzahlC'] > 1) { ?>
    <div class="beCommentEntry">
      <?php echo genPager($beitrag['seitenzahlC'], $beitrag['startC'], getLink($db, $beitrag['Cat'], $beitrag['ID'], $beitrag['Titel']).'/page', $mob); ?>
    </div>
      <?php } ?>
    <?php } else { ?>
    <div class="beCommentEntry">
      <p>Keine Kommentare vorhanden.</p>
    </div>
    <?php } ?>
    <div class="beCommentNew" id="newComment">
      <span class="beCommentNewHeader">Schreibe einen Kommentar!</span>
       <?php $err = array('t' => $data['eType'], 'c' => $data['ec']);
       echo genFormPublic($err, $cmtReply, time(), $mob, $bbCmt, 'Kommentar schreiben', 'commentForm', $data['comment_reply']); ?>
    </div>
  </div>
        
  <?php }  ?>
<?php }  ?>