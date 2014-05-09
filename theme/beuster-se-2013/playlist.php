<?php
  if (count($data['articles'])) {
    foreach ($data['articles'] as $article) {
      $url = 'http://beusterse.de'.replaceUml($article->getLink());
      $anhang = ' #beusterse ';
      if(strlen($article->getTitle()) > 61) {
        $text = substr($article->getTitle(), 0, 60).'...'.$anhang.$url;
      } else {
        $text = $article->getTitle().$anhang.$url;
      } 



      ?>

 <article class="beContentEntry">
    <span class="socialTxt" title="<?php echo $text; ?>" style="display: none;"><?php echo $text; ?></span>
    <h1 class="beContentEntryHeader"><?php echo $article->getTitle(); ?></h1>
    <?php echo $article->getContentParsed()."\n"; ?>
    <?php if(count($data['pics']) >= 1) { echo '<section>'.genGal($data['pics'], $mob).'</section>'; } ?>
  </article>
    <?php } ?>

  <!-- connected articles -->
  <div class="beContentConnected">
    <span class="beContentConnectedHeader">Weitere Videos der Reihe</span>
  <?php $i = 0;
        if(isset($data['videos'])) {
          foreach($data['videos'] as $vid) {
            $i++;
            $css = (($i % 2 == 0) ? 'even' : 'odd'); ?>
    <div class="beContentConnectedEntry <?php echo $css; ?>">
      <div class="beContentConnectedEntryThumb">
        <img src="/<?php echo $vid['thumb']; ?>" alt="<?php //Playlist-Nr ?>" title="<?php echo $vid['title']; ?>">
      </div>
      <p>
        <a href="/<?php echo $vid['art'] ?>">
          <?php echo $vid['title']; ?>
        </a>
      </p>
      <br class="clear">
    </div>
  <?php   }
        } else { ?>
        Diese Playlist hat noch keine weiteren Videos.
  <?php } ?>
  </div>
  <br class="clear">

  <!-- comments -->
  <div id="beContentEntryComments">
    <h2 id="comments" class="beContentEntryCommentsHeader">Kommentare (<?php echo count($article->getComments()); ?>)</h2>
    <?php 
      $cmtReply = $article->getLink();
      if(count($article->getComments()) > 0) {
        foreach($article->getComments() as $cmt) {

          echo genComment($cmt, $cmtReply);
        }
        if($article['seitenzahlC'] > 1) { ?>
    <div class="beCommentEntry">
      <?php echo genPager($article->getPagesCmt(), $article->getStartCmt(), $article->getLink().'/page', $mob); ?>
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
