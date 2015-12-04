<?php $form = Setup::getSetup()->getFormHandler(); ?>

<form action="index.php?step=database" method="post" class="<?php echo $form->getErrorClass(); ?>">
  <fieldset>
    <?php $form->showMessages(); ?>
    <span><a href="index.php?step=welcome">Back</a></span>
    <br>
    <legend>database</legend>
    <label for="db_host">db_host</label>
    <?php $form->textField('db_host', 'db_host', SetupHelper::getFieldOptions('db_host')); ?>
    <br>
    <label for="db_name">db_name</label>
    <?php $form->textField('db_name', 'db_name', SetupHelper::getFieldOptions('db_name')); ?>
    <br>
    <label for="db_user">db_user</label>
    <?php $form->textField('db_user', 'db_user'); ?>
    <br>
    <label for="db_pass">db_pass</label>
    <?php $form->passwordField('db_pass', 'db_pass'); ?>
    <br>
    <input type="submit" value="Save and next">
  </fieldset>
</form>
