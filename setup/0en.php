<?php class I18n {
public static function t($string, $args = null) {
  $string = str_replace(".", "_", $string);
  if(constant("self::" . $string) === null) {
    return "Missing translation for '$string'";
  }
  if($args === null) {
    return constant("self::" . $string);
  }
  return vsprintf(constant("self::" . $string), $args);
}
}
