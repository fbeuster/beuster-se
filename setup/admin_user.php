<?php $form = Setup::getSetup()->getFormHandler(); ?>

<form action="index.php?step=admin_user" method="post">
  <fieldset>
    <legend><?php echo I18n::t("setup.admin_user.legend"); ?></legend>
    <div class="progress">
      <div class="bar w60">&nbsp;</div>
    </div>
    <?php $form->showMessages(); ?>
    <span class="nav_button"><a href="index.php?step=content"><?php echo I18n::t("setup.admin_user.back_link"); ?></a></span>
    <p><?php echo I18n::t("setup.admin_user.step_info"); ?></p>
    <label for="admin_username"><?php echo I18n::t("setup.admin_user.label_username"); ?></label>
    <?php $form->textField('admin_username', 'admin_username', SetupHelper::getFieldOptions('admin_username')); ?>
    <label for="admin_realname"><?php echo I18n::t("setup.admin_user.label_realname"); ?></label>
    <?php $form->textField('admin_realname', 'admin_realname', SetupHelper::getFieldOptions('admin_realname')); ?>
    <label for="admin_password"><?php echo I18n::t("setup.admin_user.label_password"); ?></label>
    <?php $form->passwordField('admin_password', 'admin_password'); ?>
    <label for="admin_password_2"><?php echo I18n::t("setup.admin_user.label_password_2"); ?></label>
    <?php $form->passwordField('admin_password_2', 'admin_password_2'); ?>
    <label for="admin_mail"><?php echo I18n::t("setup.admin_user.label_mail"); ?></label>
    <?php $form->textField('admin_mail', 'admin_mail', SetupHelper::getFieldOptions('admin_mail')); ?>
    <label for="admin_website"><?php echo I18n::t("setup.admin_user.label_website"); ?></label>
    <?php $form->textField('admin_website', 'admin_website', SetupHelper::getFieldOptions('admin_website')); ?>
    <hr>
    <input type="submit" value="<?php echo I18n::t("setup.admin_user.submit"); ?>" class="nav_button">
  </fieldset>
</form>