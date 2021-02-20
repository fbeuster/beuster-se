<?php

  class ImageDecorator extends Decorator {

    const ALIGN_PATTERN = '(left|right)';
    const HEIGHT_PATTERN = '[0-9]*';
    const WIDTH_PATTERN = '[0-9]*';

    const SINGLE_OPTIONS_PATTERN = '(\s'.self::WIDTH_PATTERN.'\s'.self::HEIGHT_PATTERN.'(\s'.self::ALIGN_PATTERN.')?)';
    const SEPARATE_OPTIONS_PATTERN = '(\s'.self::WIDTH_PATTERN.')(\s'.self::HEIGHT_PATTERN.')(\s'.self::ALIGN_PATTERN.')?';

    const VALUE_PATTERN = '([0-9]*)';
    const IMAGE_PATTERN = '#\[img'.self::VALUE_PATTERN.self::SINGLE_OPTIONS_PATTERN.'?\]#';

    public function __construct($content) {
      parent::__construct($content, self::IMAGE_PATTERN, '#'.self::VALUE_PATTERN.'#');
    }

    public function decorate() {
      while($this->hasDecoration()) {
        $id = $this->getDecorationValue();
        $this->replaceDecoration($this->getImage($id), $id);
      }
    }

    private function getImage($id) {
      $image = new Image($id);
      $name = $image->getTitle();
      $options = $this->getImageOptions();

      if ($options['width'] * $options['height'] == 0) {
        $path = $image->getAbsolutePath();

      } else {
        $thumbSizes = Lixter::getLix()->getTheme()->getThumbnailSizes();
        $path = $image->getAbsoluteThumbnailPath($options['width'], $options['height']);
      }

      if ($options['align'] == null) {
        $pre = '</p><p class="image">';
        $post = '</p><p>';

      } else {
        $pre = '<span class="image '.$options['align'].'">';
        $post = '</span>';
      }

      $imageSrc  = '<img src="'.$path.'" alt="'.$name.'" name="'.$name.'" title="'.$name.'" data-src="'.$image->getAbsolutePath().'">';

      return $pre.$imageSrc.$post;
    }

    private function getImageOptions() {
      preg_match('#'.self::SEPARATE_OPTIONS_PATTERN.'#', $this->getDecorationOptions(), $options);

      return array( 'align' => isset($options[4]) ? trim($options[4]) : null,
                    'height' => isset($options[2]) ? trim($options[2]) : 0,
                    'width' => isset($options[1]) ? trim($options[1]) : 0);
    }
  }

?>
