<?php $form = Setup::getSetup()->getFormHandler(); ?>

<form action="index.php?step=content" method="post" class="<?php echo $form->getErrorClass(); ?>">
  <fieldset>
    <legend><?php echo I18n::t("setup.content.legend"); ?></legend>
    <div class="progress">
      <div class="bar w40">&nbsp;</div>
    </div>
    <?php $form->showMessages(); ?>
    <span class="nav_button"><a href="index.php?step=database"><?php echo I18n::t("setup.content.back_link"); ?></a></span>
    <label for="db_char"><?php echo I18n::t("setup.content.label_db_char"); ?></label>
    <?php $form->selectField('db_char', 'db_char', SetupHelper::getCharsets(), 'utf8', SetupHelper::getFieldOptions('db_char')); ?>
    <label for="new_db"><?php echo I18n::t("setup.content.label_new_db"); ?></label>
    <?php $form->radioButton('new_db', 'new_db', array('checked' => 'checked', 'value' => 'new_db')); ?>
    <label for="from_existing"><?php echo I18n::t("setup.content.label_from_existing"); ?></label>
    <?php $form->radioButton('from_existing', 'new_db', array('value' => 'from_existing')); ?>
    <div class="file_uploader">
      <label for="sql_file"><?php echo I18n::t("setup.content.label_sql_file"); ?></label>
      <input type="file" name="sql_file" id="sql_file">
    </div>
    <hr>
    <input type="submit" value="<?php echo I18n::t("setup.content.submit"); ?>" class="nav_button">
  </fieldset>
</form>
