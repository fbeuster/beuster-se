<?php
  if (count($data['articles'])) {
    foreach ($data['articles'] as $article) {
      $url = 'http://beusterse.de'.replaceUml($article->getLink());
      $anhang = ' #beusterse ';
      if(strlen($article->getTitle()) > 61) {
        $text = substr($article->getTitle(), 0, 60).'...'.$anhang.$url;
      } else {
        $text = $article->getTitle().$anhang.$url;
      } ?>

 <article class="beContentEntry">
    <span class="socialTxt" title="<?php echo $text; ?>" style="display: none;"><?php echo $text; ?></span>
    <h1 class="beContentEntryHeader"><?php echo $article->getTitle(); ?></h1>
    <?php if($article->getProjState() !== 0){
      switch(substr($article->getProjState(), 0, 1)){
        case '1': $projStyle = 'background: #70d015; color: #000000;'; break;
        case '2': $projStyle = 'background: #efef15; color: #000000;'; break;
        case '3': $projStyle = 'background: #ff8015; color: #000000;'; break;
        case '4': $projStyle = 'background: #efefef; color: #2b2b2b;'; break;
        default: $projStyle = ''; break;
      }
    ?>
    <span class="projState" value="<?php echo substr($article->getProjState(), 0, 1); ?>" style="<?php echo $projStyle; ?>">
      Status: <?php echo substr($article->getProjState(), 1, strlen($article->getProjState())-1); ?>
    </span>
    <?php } ?>
    <?php echo $article->getContentParsed()."\n"; ?>
    <?php if(count($data['pics']) >= 1) { echo '<section>'.genGal($data['pics']).'</section>'; } ?>
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
    <h2 id="comments" class="beContentEntryCommentsHeader">Kommentare (<?php echo count($article->getComments()); ?>)</h2>
    <?php
      $cmtReply = $article->getLink();
      if(count($article->getComments()) > 0) {
        foreach($article->getComments() as $cmt) {

          echo genComment($cmt, $cmtReply);
        }
        if($article->getPagesCmt() > 1) { ?>
    <div class="beCommentEntry">
      <?php echo genPager($article->getPagesCmt(), $article->getStartCmt(), $article->getLink().'/page'); ?>
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
       echo genFormPublic($err, $cmtReply, time(), $bbCmt, 'Kommentar schreiben', 'commentForm', $data['comment_reply']); ?>
    </div>
  </div>

  <?php }  ?>
<?php }  ?>