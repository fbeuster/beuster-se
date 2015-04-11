
    <h1>Kategorieverwaltung</h1>
    <?php if(isset($data['cats'], $data['pars'])) {
            $i = 0;?>
    <form action="/admincat" method="post">
      <fieldset id="admin">
        <h2>Top Level Kategorien</h2>
         <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Lö.</th>
              <th>Ziel nach Lö.</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($data['pars'] as $par) { ?>
            <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?>>
              <td><?php echo $par['id']?></td>
              <td><?php echo $par['name']?></td>
              <td>
                <?php if($par['name'] != 'Blog') { ?>
                <input type="radio" name="del<?php echo $par['id'];?>" value="parDel">
                <?php } ?>
              </td>
              <td>
              <?php if($par['name'] != 'Blog') { ?>
                <select name="parDelTarget<?php echo $par['id']; ?>">
                  <option value="err">New Parent wählen</option>
                  <?php 
                    foreach($data['pars'] as $p) {
                      if($par['name'] != $p['name']) { ?>
                  <option value="<?php echo $p['id']; ?>"><?php echo $p['name'] ?></option>
                      <?php }
                    } ?>
                </select>
              <?php } ?>
              </td>
            </tr>
          <?php $i++; } ?>
          </tbody>
        </table>
        <p>
          <input type="submit" value="Top Level übernehmen" name="parSubmitTable">
        </p>
        <h2>Sub Level Kategorien</h2>
         <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Parent</th>
              <th>Parent (neu)</th>
              <th>Lö.</th>
              <th>Ziel nach Lö.</th>
            </tr>
          </thead>
          <tbody>
          <?php $i = 0;
            foreach($data['cats'] as $cat) {?>
            <tr>
              <td><?php echo $cat['id']; ?></td>
              <td><?php echo $cat['name']; ?></td>
              <td><?php echo $cat['parent']; ?></td>
              <td>
                <select name="catNewPar<?php  echo $cat['id']; ?>">
                  <option value="err">New Parent wählen</option>
                  <?php 
                    foreach($data['pars'] as $par) {
                      if($cat['parent'] != $par['name']) { ?>
                  <option value="<?php echo $par['id']; ?>"><?php echo $par['name']; ?></option>
                      <?php }
                    } ?>
                </select>
              </td>
              <td><input type="radio" name="catDel<?php echo $cat['id'];?>" class="del" value="catDel"></td>
              <td>
                <select name="catDeleteTarget<?php echo $cat['id'];?>">
                  <option>Ziel wählen</option>
                  <?php
                    foreach($data['cats'] as $c) {
                      if($cat['name'] != $c['name'] && !in_array($c['name'], $data['pars'])) { ?>
                  <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                      <?php }
                    } ?>
                </select>
              </td>
            </tr>
            <?php $i++; } ?>
          </tbody>
        </table>
        <p>
          <input type="submit" value="Sub Level übernehmen" name="catSubmitTable">
        </p>
        <h2> Neue Kategorien erzeugen</h2>
        <p>
          <label>
            Neuen Parent anlegen:
          </label>
          <input type="text" name="catCreateNewPar">
          <input type="submit" value="Parent anlegen" name="catSubmitNewPar">
          <br class="clear">
        </p>
        <p>
          <label>
            Neue Kategorie in vorhandenen Parent:
          </label>
          <input type="text" name="catCreateNewCat">
          <select name="catCreateNewCatPar">
            <option>Parent wählen</option>
            <?php foreach($data['pars'] as $par) { ?>
            <option value="<?php echo $par['id']; ?>"><?php echo $par['name']; ?></option>
            <?php } ?>
          </select>
          <input type="submit" value="Kategorie anlegen" name="catSubmitNewCat">
          <br>
        </p>
      </fieldset>
    </form>
    <?php } else { ?>
    <p>Keine Kategorien vorhanden.</p>
    <?php } ?>
    <p>
      <a href="/admin">zurück zur Administration</a>
    </p>