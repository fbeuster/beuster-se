
    <h1>Freischaltung</h1>
    <?php if(empty($data['cmt'])) { ?>
    <p>Keine Kommentare freizuschalten</p>
    <?php } else { ?>
    <form action="/admincmtenable" method="post">
      <table>
        <thead>
          <tr>
            <th>Datum</th>
            <th class="tabTitle">News</th>
            <th class="tabName">Name</th>
            <th class="tabMail">Mail</th>
            <th>Inhalt</th>
            <th class="sel">Lö.</th>
            <th class="sel">Fr.</th>
          </tr>
        </thead>
        <tbody>
        <?php $i = 0; foreach($data['cmt'] as $cmt) { $i++; ?>
          <tr>
            <td><?php echo $cmt['datum'];?></td>
            <td><div class="wrap close"><a href="<?php echo $cmt['link'];?>" title="<?php echo $cmt['titelFull']; ?>"><?php echo $cmt['titel'];?></a></div></td>
            <td><?php if(isValidUserUrl($cmt['web'])) {echo '<a href="'.$cmt['web'].'">'.$cmt['name'].'</a>';} else {echo $cmt['name'];}?></td>
            <td><?php echo $cmt['mail'];?></td>
            <td><div class="wrap close"><?php echo $cmt['inhalt'];?></div></td>
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
    </form>
    <?php } ?>
    <p>
      <a href="/admin">zurück zur Administration</a>
    </p>