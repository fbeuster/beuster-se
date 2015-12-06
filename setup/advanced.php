<?php $form = Setup::getSetup()->getFormHandler(); ?>

<form action="index.php?step=advanced" method="post">
  <fieldset>
    <legend><?php echo I18n::t("setup.advanced.legend"); ?></legend>
    <div class="progress">
      <div class="bar w100">&nbsp;</div>
    </div>
    <?php $form->showMessages(); ?>
    <span class="nav_button"><a href="index.php?step=custom"><?php echo I18n::t("setup.advanced.back_link"); ?></a></span>
    <label for="devServer"><?php echo I18n::t("setup.advanced.label_dev_server"); ?></label>
    <?php $form->checkbox('devServer', 'devServer'); ?>
    <div class="dev_server">
      <label for="devServerAddress"><?php echo I18n::t("setup.advanced.label_dev_server_address"); ?></label>
      <?php $form->textField('devServerAddress', 'devServerAddress'); ?>
      <label for="remoteServerAddress"><?php echo I18n::t("setup.advanced.label_remote_server_address"); ?></label>
      <?php $form->textField('remoteServerAddress', 'remoteServerAddress'); ?>
    </div>
    <hr>
    <input type="submit" value="<?php echo I18n::t("setup.advanced.submit"); ?>" class="nav_button">
  </fieldset>
</form>