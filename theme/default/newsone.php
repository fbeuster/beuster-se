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

 <article>
    <h1><?php echo $article->getTitle(); ?></h1>
    <span value="<?php echo substr($article->getProjState(), 0, 1); ?>">
      Status: <?php echo substr($article->getProjState(), 1, strlen($article->getProjState())-1); ?>
    </span>
    <?php } ?>
    <?php echo $article->getContentParsed()."\n"; ?>
    <?php if(count($data['pics']) >= 1) { echo '<section>'.genGal($data['pics'], $mob).'</section>'; } ?>
  </article>

  <!-- comments -->
  <div>
    <h2 id="comments">Kommentare (<?php echo echo count($article->getComments()); ?>)</h2>
    <?php
      $cmtReply = $article->getLink();
      if(count($article->getComments()) > 0) {
        foreach($article->getComments() as $cmt) {
          echo genComment($cmt, $cmtReply);
        }
        if($article->getPagesCmt() > 1) { ?>
    <div>
      <?php echo genPager($article->getPagesCmt(), $article->getStartCmt(), $article->getLink().'/page', $mob); ?>
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