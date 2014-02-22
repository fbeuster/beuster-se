  <div class="beContentEntry">
<h1>E-Mail ändern</h1>
      <p>
       An diese E-Mail wird auch der tägliche Bericht gesendet.
      </p>
      <p>
       <?php echo $data['err']; ?>
      </p>
      <form action="/userdata" method="post">
       <fieldset id="admin">
        <label>Deine E-Mail:</label>
        <input type="email" name="mailold" value="<?php echo $data['mailold']; ?>" disabled>
        <br>
        <label>Neue E-Mail:</label>
        <input type="email" name="mail"><br><br>
        <input type="submit" name="changeMail" value="E-Mail ändern">
       </fieldset>
       <fieldset id="admin">
        <label>Altes Passwort:</label>
        <input type="password" name="passOld">
        <br>
        <label>Neues Passwort:</label>
        <input type="password" name="pass"><br><br>
        <label>(wiederholen):</label>
        <input type="password" name="pass2"><br><br>
        <input type="submit" name="changePass" value="Passwort ändern"><br><br>
        <input type="submit" name="changeBoth" value="Beides ändern">
       </fieldset>
      </form>
    <p class="backendBackLink">
      <a href="/admin" class="back">Zurück zur Administration</a>
    </p>
  </div>