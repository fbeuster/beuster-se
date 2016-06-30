<?php

  class ImageDecorator extends Decorator {

    public function __construct($content) {
      parent::__construct($content, '#\[img([0-9]*)\]#', '#([0-9]*)#');
    }

    public function decorate() {
      while($this->hasDecoration()) {
        $id = $this->getDecorationValue();

        $this->replaceDecoration($this->getImage($id));
      }
    }

    private function getImage($id) {
      $db = Database::getDB();

      $fields = array('Name', 'Pfad');
      $conds  = array('ID = ?', 'i', array($id));
      $res    = $db->select('pics', $fields, $conds);

      if(count($res) != 1)
        return '';

      $name = $res[0]['Name'];
      $path = $res[0]['Pfad'];

      $path   = makeAbsolutePath($path, '', true);
      $image  = '</p><p class="image"><img src="'.$path.'" alt="'.$name.'" name="'.$name.'" title="'.$name.'"></p><p>';

      return $image;
    }
  }

?>