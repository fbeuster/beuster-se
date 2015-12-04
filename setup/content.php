<?php $form = Setup::getSetup()->getFormHandler(); ?>

<form action="index.php?step=content" method="post" class="<?php echo $form->getErrorClass(); ?>">
  <fieldset>
    <?php $form->showMessages(); ?>
    <span><a href="index.php?step=database">Back</a></span>
    <br>
    <legend>content</legend>
    <label for="db_char">db_char</label>
    <?php $form->selectField('db_char', 'db_char', SetupHelper::getCharsets(), 'utf8', SetupHelper::getFieldOptions('db_char')); ?>
    <br>
    <label for="new_db">new_db</label>
    <?php $form->radioButton('new_db', 'new_db', array('checked' => 'checked', 'value' => 'new_db')); ?>
    <br>
    <label for="from_existing">from_existing</label>
    <?php $form->radioButton('from_existing', 'new_db', array('value' => 'from_existing')); ?>
    <br>
    <label for="sql_file">sql_file</label>
    <input type="file" name="sql_file" id="sql_file">
    <br>
    <input type="submit" value="Save and next">
  </fieldset>
</form>
