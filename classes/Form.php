<?php

  class Form {

    const ELEMENT_CONTROL   = 1;
    const ELEMENT_HIDDEN    = 2;
    const ELEMENT_INPUT     = 3;
    const ELEMENT_TEXTAREA  = 4;

    const WAIT_TIME = 20;

    private $action;
    private $elements;
    private $errors;
    private $success;

    function __construct($action, $errors, $success) {
      $this->action   = $action;
      $this->elements = array();
      $this->errors   = $errors;
      $this->success  = $success;
    }

    public function addControl($name, $type, $value) {
      $this->elements[] = array(
        'element'   => self::ELEMENT_CONTROL,
        'name'      => $name,
        'type'      => $type,
        'value'     => $value
      );
    }

    public function addHiddenInput($name, $value) {
      $this->elements[] = array(
        'element'   => self::ELEMENT_HIDDEN,
        'name'      => $name,
        'type'      => 'hidden',
        'value'     => $value
      );
    }

    public function addInputText($label, $name, $required, $value) {
      $this->elements[] = array(
        'element'   => self::ELEMENT_INPUT,
        'label'     => $label,
        'name'      => $name,
        'required'  => $required,
        'type'      => 'text',
        'value'     => $value
      );
    }

    public function addTextarea($label, $name, $required, $value) {
      $this->elements[] = array(
        'element'   => self::ELEMENT_TEXTAREA,
        'label'     => $label,
        'name'      => $name,
        'required'  => $required,
        'value'     => $value
      );
    }

    private function makeControl($element) {
      $input_html = '<input';
      $input_html .= ' id="'.$element['name'].'"';
      $input_html .= ' type="'.$element['type'].'"';
      $input_html .= ' name="'.$element['name'].'"';
      $input_html .= ' value="'.I18n::t('general_form.'.$element['value']).'"';

      $input_html .= '>';

      return $input_html;
    }

    private function makeHiddenInput($element) {
      $input_html = '<input';
      $input_html .= ' type="'.$element['type'].'"';
      $input_html .= ' name="'.$element['name'].'"';

      if ($element['value']) {
        $input_html .= ' value="'.$element['value'].'"';
      }

      $input_html .= '>';

      return $input_html;
    }

    private function makeInput($element) {
      $input_html = '<input';
      $input_html .= ' type="'.$element['type'].'"';
      $input_html .= ' name="'.$element['name'].'"';
      $input_html .= ' placeholder="'.I18n::t('general_form.'.$element['label'].'.placeholder').'"';

      if ($element['value']) {
        $input_html .= ' value="'.$element['value'].'"';
      }

      if ($element['required']) {
        $input_html .= ' required="required"';
      }

      $input_html .= '>';

      return $input_html;
    }

    private function makeLabel($element) {
      $label_html = '';

      switch ($element['element']) {
        case self::ELEMENT_CONTROL:
          $label_html .= $this->makeControl($element);
          break;

        case self::ELEMENT_HIDDEN:
          $label_html .= $this->makeHiddenInput($element);
          break;

        case self::ELEMENT_INPUT:
          $label_html .= $this->makeLabelOpen($element);
          $label_html .= $this->makeInput($element);
          $label_html .= '</label>';
          break;

        case self::ELEMENT_TEXTAREA:
          $label_html .= $this->makeLabelOpen($element);
          $label_html .= $this->makeTextarea($element);
          $label_html .= '</label>';
          break;

        default:
          break;
      }

      return $label_html;
    }

    private function makeLabelOpen($element) {
      $label_class = '';

      if ($element['required']) {
        $label_class .= 'required ';
      }

      if (isset($this->errors[$element['name']])) {
        $label_class .= 'has_error ';
      }

      $label_html = '<label class="'.$label_class.'">';
      $label_html .= '<span';

      if ($element['element'] == self::ELEMENT_TEXTAREA) {
        $label_html .= ' class="textarea"';
      }

      $label_html .= '>'.I18n::t('general_form.'.$element['label'].'.label').'</span>';
      return $label_html;
    }

    private function makeTextarea($element) {
      $textarea_html = '<textarea';
      $textarea_html .= ' id="'.$element['name'].'"';
      $textarea_html .= ' name="'.$element['name'].'"';
      $textarea_html .= ' placeholder="'.I18n::t('general_form.'.$element['label'].'.placeholder').'"';

      if ($element['required']) {
        $textarea_html .= ' required="required"';
      }

      $textarea_html .= '>';

      if ($element['value']) {
        $textarea_html .= $element['value'];
      }

      $textarea_html .= '</textarea>';

      return $textarea_html;
    }

    public function show($class) {

      /* showing errors */
      if (isset($this->errors) && !empty($this->errors)) {
        echo '<div class="error">';
        echo '<div class="title">Error</div>';
        echo '<ul class="messages">';

        foreach ($this->errors as $name => $error) {
          echo '<li>'.$error.'</li>';
        }

        echo '</ul>';
        echo '</div>';
      }

      /* showing success message */
      if ($this->success) {
        echo '<div class="success">';
        echo '<div class="title">Success</div>';
        echo '<p>Thank you for your message.'.'</p>';
        echo '</div>';
      }

      /* adding form */
      echo '<form acrion="'.$this->action.'" method="post" class="'.$class.'">';
      echo '<fieldset>';

      foreach ($this->elements as $element) {
        echo $this->makeLabel($element);
      }

      echo '</fieldset>';
      echo '</form>';

      /* adding disclainer */
      echo '<p class="newCommentTime">'."\r";

      $wait = '<strong id="wait">20</strong>';
      I18n::e('general_form.wait', array($wait))."\r";

      echo '</p>'."\r";
      echo '<p class="newCommentDisclaimer">'."\r";

      # TODO
      # check if impressum or disclaimer exists
      $lb         = Lixter::getLix()->getLinkBuilder();
      $more_info  = '<a href="'.$lb->makeOtherPageLink('impressum').
                    '">'.I18n::t('general_form.privacy').'</a>';
      I18n::e('general_form.disclaimer', array($more_info))."\r";

      echo '</p>'."\r";
    }
  }

?>
