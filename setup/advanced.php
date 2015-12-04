<?php $form = Setup::getSetup()->getFormHandler(); ?>

<form action="index.php?step=advanced" method="post">
  <fieldset>
    <?php $form->showMessages(); ?>
    <span><a href="index.php?step=custom">Back</a></span>
    <br>
    <legend>advanced</legend>
    <label for="devServer">devServer</label>
    <?php $form->checkbox('devServer', 'devServer'); ?>
    <br>
    <label for="devServerAddress">devServerAddress</label>
    <?php $form->textField('devServerAddress', 'devServerAddress'); ?>
    <br>
    <label for="remoteServerAddress">remoteServerAddress</label>
    <?php $form->textField('remoteServerAddress', 'remoteServerAddress'); ?>
    <br>
    <input type="submit" value="Save and next">
  </fieldset>
</form>