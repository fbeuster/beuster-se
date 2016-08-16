
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">
    <h1>E-Mail ändern</h1>
    <p>
      An diese E-Mail wird auch der tägliche Bericht gesendet.
    </p>
    <p>
      <?php echo $data['err']; ?>
    </p>
    <form action="/userdata" method="post" class="userform articleform">
      <fieldset id="admin">
        <label>
          <span>Deine E-Mail</span>
          <input type="email" name="mailold" value="<?php echo $data['mailold']; ?>" disabled>
        </label>

        <label>
          <span>Neue E-Mail</span>
          <input type="email" name="mail">
        </label>

        <input type="submit" name="changeMail" value="E-Mail ändern">

      </fieldset>
      <fieldset id="admin">
        <label>
          <span>Altes Passwort</span>
          <input type="password" name="passOld">
        </label>

        <label>
          <span>Neues Passwort</span>
          <input type="password" name="pass">
        </label>

        <label>
         <span>(wiederholen)</span>
          <input type="password" name="pass2">
        </label>

        <input type="submit" name="changePass" value="Passwort ändern">
        <input type="submit" name="changeBoth" value="Beides ändern">
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>
