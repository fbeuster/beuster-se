<?php $form = Setup::getSetup()->getFormHandler(); ?>

<form action="index.php?step=admin_user" method="post">
  <fieldset>
    <?php $form->showMessages(); ?>
    <span><a href="index.php?step=content">Back</a></span>
    <br>
    <legend>admin user</legend>
    <label for="admin_username">admin_username</label>
    <?php $form->textField('admin_username', 'admin_username', SetupHelper::getFieldOptions('admin_username')); ?>
    <br>
    <label for="admin_realname">admin_realname</label>
    <?php $form->textField('admin_realname', 'admin_realname', SetupHelper::getFieldOptions('admin_realname')); ?>
    <br>
    <label for="admin_password">admin_password</label>
    <?php $form->passwordField('admin_password', 'admin_password'); ?>
    <br>
    <label for="admin_password_2">admin_password_2</label>   <?php $form->passwordField('admin_password_2', 'admin_password_2'); ?>
    <br>
    <label for="admin_mail">admin_mail</label>
    <?php $form->textField('admin_mail', 'admin_mail', SetupHelper::getFieldOptions('admin_mail')); ?>
    <br>
    <label for="admin_website">admin_website</label>
    <?php $form->textField('admin_website', 'admin_website', SetupHelper::getFieldOptions('admin_website')); ?>
    <br>
    <input type="submit" value="Save and next">
  </fieldset>
</form>