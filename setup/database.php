<?php $form = Setup::getSetup()->getFormHandler(); ?>

<form action="index.php?step=database" method="post" class="<?php echo $form->getErrorClass(); ?>">
  <fieldset>
    <legend><?php echo I18n::t("setup.database.legend"); ?></legend>
    <div class="progress">
      <div class="bar w20">&nbsp;</div>
    </div>
    <?php $form->showMessages(); ?>
    <span class="nav_button"><a href="index.php?step=welcome"><?php echo I18n::t("setup.database.back_link"); ?></a></span>
    <label for="db_host"><?php echo I18n::t("setup.database.label_db_host"); ?></label>
    <?php $form->textField('db_host', 'db_host', SetupHelper::getFieldOptions('db_host')); ?>
    <label for="db_name"><?php echo I18n::t("setup.database.label_db_name"); ?></label>
    <?php $form->textField('db_name', 'db_name', SetupHelper::getFieldOptions('db_name')); ?>
    <label for="db_user"><?php echo I18n::t("setup.database.label_db_user"); ?></label>
    <?php $form->textField('db_user', 'db_user'); ?>
    <label for="db_pass"><?php echo I18n::t("setup.database.label_db_pass"); ?></label>
    <?php $form->passwordField('db_pass', 'db_pass'); ?>
    <hr>
    <input type="submit" value="<?php echo I18n::t("setup.database.submit"); ?>" class="nav_button">
  </fieldset>
</form>
