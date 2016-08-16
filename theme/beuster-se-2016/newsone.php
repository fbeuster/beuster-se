<?php if (count($data['articles'])) { ?>
  <?php foreach ($data['articles'] as $article) { ?>
    <?php # echo '<pre>'; print_r($article->getCategory()->getNameUrl()); echo '</pre>'; ?>
    <article>
      <a href="/<?php echo $article->getCategory()->getNameUrl(); ?>" class="back">&lt; Back to <?php echo $article->getCategory()->getName(); ?></a>
      <section class="article">
        <h1><?php echo $article->getTitle(); ?></h1>
        <i>
          written by <?php echo $article->getAuthor()->getClearname(); ?>
          on
          <time datetime="<?php echo date('c', $article->getDate()); ?>" class="long">
            <?php echo date('d.m.Y H:i', $article->getDate()); ?>
          </time>
        </i>
        <?php echo $article->getContentDecorated(); ?>
      </section>
      <a href="/<?php echo $article->getCategory()->getNameUrl(); ?>" class="back">&lt; Back to <?php echo $article->getCategory()->getName(); ?></a>

      <?php if(count($data['pics']) >= 1) { ?>
        <section class="gallery">
          <h2>Gallery</h2>
          <ul>
          <?php foreach ($data['pics'] as $image) { ?>
            <li>
              <img src="<?php echo makeAbsolutePath($image['pfad'], '', true); ?>" alt="<?php echo $image['name']; ?>">
            </li>
          <?php } ?>
          </ul>
        </section>
      <?php } ?>

      <section class="comments">
        <h2>Comments</h2>
        <?php if(count($article->getComments()) > 0) { ?>
          <?php foreach($article->getComments() as $comment) { ?>
            <?php echo makeComment($comment, $article->getLink()); ?>
          <?php } ?>
        <?php } else { ?>
          No comments yet.
        <?php } ?>

        <?php
          $err = array('t' => $data['eType'], 'c' => $data['ec']);
          echo makeForm($err, $article->getLink(), time(),
                        'Write something new', 'commentForm', $data['comment_reply']);
        ?>
      </section>

    </article>
  <?php } ?>
<?php } ?>



<?php
  if ( false && count($data['articles'])) {
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
    <?php echo $article->getContentDecorated()."\n"; ?>
    <?php if(count($data['pics']) >= 1) { echo '<section>'.genGal($data['pics']).'</section>'; } ?>
  </article>

  <!-- comments -->
  <div>
    <h2 id="comments">Kommentare (<?php echo $article->getCommentsCount(); ?>)</h2>
    <?php
      $cmtReply = $article->getLink();
      if(count($article->getComments()) > 0) {
        foreach($article->getComments() as $cmt) {
          echo genComment($cmt, $cmtReply);
        }
        if($article->getPagesCmt() > 1) { ?>
    <div>
      <?php echo genPager($article->getPagesCmt(), $article->getStartCmt(), $article->getLink().'/page'); ?>
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
       echo genFormPublic($err, $cmtReply, time(), 'Kommentar schreiben', 'commentForm', $data['comment_reply']); ?>
    </div>
  </div>

  <?php }  ?>