<?php

  /**
  *
  */
  class Editor {

    private $content;
    private $id;
    private $name;

    public function __construct($id, $name, $content){
      $this->content  = $content;
      $this->id       = $id;
      $this->name     = $name;
    }

    public function show() {
      Utilities::loadSystemView(
        'editor.php',
        array(
          'id'      => $this->id,
          'name'    => $this->name,
          'content' => $this->content));
    }
  }

?>
