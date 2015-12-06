
<form action="index.php?step=database" method="post">
  <fieldset>
    <legend><?php echo I18n::t("setup.welcome.legend"); ?></legend>
    <div class="progress">
      <div class="bar">&nbsp;</div>
    </div>
    <h2><?php echo I18n::t("setup.welcome.header"); ?></h2>
    <p><?php echo I18n::t("setup.welcome.content"); ?></p>
    <hr>
    <input type="submit" value="<?php echo I18n::t("setup.welcome.submit"); ?>" class="nav_button">
  </fieldset>
</form>
