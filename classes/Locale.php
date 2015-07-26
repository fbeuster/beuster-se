<?php

  class Locale {
    # This class is highly based on the i18n class of Philipp15b
    # https://github.com/Philipp15b/php-i18n

    const LANG_FILE_TYPE_JSON = 'json';

    private $lang_path  = 'locale/';
    private $cache_path = 'cache/locale/';

    private $lang;
    private $lang_array;
    private $lang_file_type;

    private $lang_file;
    private $cache_file;

    public function __construct($lang = 'en', $lang_file_type = self::LANG_FILE_TYPE_JSON) {
      $this->lang = $lang;
      $this->lang_file_type = $lang_file_type;

      $this->init();
      $this->includeLanguage();
    }

    private function init() {
      $this->lang_file  = $this->lang_path . $this->lang . '.' . $this->lang_file_type;
      $this->cache_file = $this->cache_path . $this->lang . '.php';

      if(!file_exists($this->cache_file) || filemtime($this->cache_file) <= filemtime($this->lang_file)) {
        $this->compile();
      }
    }

    private function compile() {
      $this->loadLangFile();

      $compiled = "";
      $compiled .= "<?php class I18n {\n";
      $compiled .= $this->compileArray($this->lang_array);
      $compiled .= 'public static function t($string, $args = null) {' . "\n";
      $compiled .= '  $string = str_replace(".", "_", $string);' . "\n";
      $compiled .= '  if(constant("self::" . $string) === null) {' . "\n";
      $compiled .= '    return "Missing translation for \'$string\'";' . "\n";
      $compiled .= "  }\n";
      $compiled .= '  if($args === null) {' . "\n";
      $compiled .= '    return constant("self::" . $string);' . "\n";
      $compiled .= "  }\n";
      $compiled .= '  return vsprintf(constant("self::" . $string), $args);' . "\n";
      $compiled .= "}\n";
      $compiled .= "}\n";

      $this->saveCacheFile($compiled);
    }

    private function compileArray($array, $prefix = '') {
      $compiled = '';

      foreach($array as $keyword => $translation) {
        if(is_array($translation)) {
          $compiled .= $this->compileArray($translation, $prefix . $keyword . '_');
        } else {
          $compiled .= 'const ' . $prefix . $keyword . ' = \'' . str_replace('\'', '\\\'', $translation) . "';\n";
        }
      }

      return $compiled;
    }

    private function includeLanguage() {
      require_once $this->cache_file;
    }

    private function loadLangFile() {
      switch($this->lang_file_type) {
        case self::LANG_FILE_TYPE_JSON:
          $this->lang_array = FileLoader::loadJson($this->lang_file, true);
          break;
        default:
          throw new Exception($this->lang_file_type + ' is not a supported translation file type.', 1);
          break;
      }
    }

    private function saveCacheFile($content) {
      if (file_put_contents($this->cache_file, $content) === FALSE) {
        throw new Exception("Could not write cache file to path " . $this->cache_file);
      }
    }
  }

?>