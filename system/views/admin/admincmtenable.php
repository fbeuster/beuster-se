
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">
    <h1>Freischaltung</h1>
    <?php if(empty($data['cmt'])) { ?>
      <p>Keine Kommentare freizuschalten</p>
    <?php } else { ?>
      <form action="/admincmtenable" method="post" class="userform articleform">
        <fieldset>
          <table class="commentlist">
            <thead>
              <tr>
                <th class="date">Datum</th>
                <th class="tabTitle">News</th>
                <th class="tabName">Name</th>
                <th class="tabMail">Mail</th>
                <th>Inhalt</th>
                <th class="sel radio">Lö.</th>
                <th class="sel radio">Fr.</th>
              </tr>
            </thead>
            <tbody>
            <?php $i = 0; foreach($data['cmt'] as $cmt) { $i++; ?>
              <tr>
                <td><?php echo date('d.m.Y H:m', $cmt['date']); ?></td>
                <td><div class="wrap close"><a href="<?php echo $cmt['news']->getLink();?>" title="<?php echo $cmt['news']->getTitle(); ?>"><?php echo $cmt['news']->getTitle();?></a></div></td>
                <td><?php if(isValidUserUrl($cmt['user']->getWebsite())) {echo '<a href="'.$cmt['user']->getWebsite().'">'.$cmt['user']->getClearname().'</a>';} else {echo $cmt['user']->getClearname();}?></td>
                <td><?php echo $cmt['user']->getMail();?></td>
                <td><div class="wrap close"><?php echo $cmt['content'];?></div></td>
                <td><input type="radio" name="<?php echo $cmt['id'];?>" class="del" value="del"></td>
                <td><input type="radio" name="<?php echo $cmt['id'];?>" class="ena" value="frei"></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
          <ul class="inlines">
            <li id="allDel">Delete all</li>
            <li id="allEna">Enable all</li>
            <li id="allUnC">Deselect all</li>
          </ul>
          <input type="hidden" name="ids" value="<?php echo $data['idss']; ?>">
          <input type="submit" name="formaction">
        </fieldset>
      </form>
    <?php } ?>
  </section>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>