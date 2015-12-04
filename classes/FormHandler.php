<?php

class FormHandler {

  public function __construct() {
    $_SESSION['form_errors']    = array();
    $_SESSION['form_messages']  = array();
  }

  public function addErrors($errors) {
    if (is_array($errors)) {
      $_SESSION['form_errors'] = array_merge($_SESSION['form_errors'], $errors);
    }
  }

  public function addMessages($messages) {
    if (is_array($messages)) {
      $_SESSION['form_messages'] = array_merge(
                                    $_SESSION['form_messages'],
                                    $messages);
    }
  }

  public function checkbox($id, $name, $extra = null) {
    echo $this->newInputField('checkbox', $id, $name, $extra);
  }

  private function hasFieldError($field_name) {
    return !empty($_SESSION['form_errors']) &&
            isset( $_SESSION['form_errors'][$field_name] );
  }

  public function getErrorClass() {
    return $this->isValid() ? '' : 'with_error';
  }

  private function getFieldError($field_name) {
    if ($this->hasFieldError($field_name)) {
      return I18n::t( $_SESSION['form_errors'][$field_name] );
    }
    return '';
  }

  public function isValid() {
    return empty($_SESSION['form_errors']) && empty($_SESSION['form_messages']);
  }

  public function label($for, $text) {
    echo '<label for="' . $for . '">' . $text . '</label>';
  }

  private function makeTagAttributes($id, $name, $extra) {
    $id_name    = ' id="' . $id . '" name="' . $name . '"';
    $extra_str  = '';

    if (is_array($extra)) {
      foreach ($extra as $key => $value) {
        if ($id == 'class' && $this->hasFieldError($name)) {
          $value .= ' with_error';
        }

        $extra_str .= ' ' . $key . '="' . $value . '"';
      }
    }

    return $id_name . $extra_str;
  }

  private function newInputField($type, $id, $name, $extra) {
    $tag_attributes = $this->makeTagAttributes($id, $name, $extra);
    return '<input type="' . $type . '"' . $tag_attributes . '>';
  }

  public function passwordField($id, $name, $extra = null) {
    echo $this->newInputField('password', $id, $name, $extra);
    $this->showFieldError( $name );
  }

  public function radioButton($id, $group, $extra) {
    echo $this->newInputField('radio', $id, $group, $extra);
    $this->showFieldError( $group );
  }

  public function selectField($id, $name, $options, $pre_selected = '', $extra = null) {
    $tag_attributes = $this->makeTagAttributes($id, $name, $extra);

    echo '<select' . $tag_attributes . '>';
    foreach ($options as $value => $text) {
      $selected = $value === $pre_selected ? ' selected="selected"' : '';
      echo '<option value="' . $value . '"' . $selected . '>' . $text . '</option>';
    }
    echo '</select>';
    $this->showFieldError( $name );
  }

  public function showFieldError($field_name) {
    $error = $this->getFieldError($field_name);
    if ($error != '') {
      echo '<span>' . $error . '</span>';
    }
  }

  public function showMessages() {
    foreach ($_SESSION['form_messages'] as $msg) {
      echo '<span>' . $msg . '</span><br>';
    }
  }

  public function textField($id, $name, $extra = null) {
    echo $this->newInputField('text', $id, $name, $extra);
    $this->showFieldError( $name );
  }
}

?>