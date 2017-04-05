<?php

  class SidebarModuleSocial extends SidebarListModule {

    public function __construct() {
      $config = array("title" => '',
                      "classes" => "socialInteraction",
                      "list" => array(
                        '<a href="http://www.youtube.com/user/waterwebdesign" class="socialLinkYoutube" title="YouTube"></a>',
                        '<a href="https://twitter.com/#!/FBeuster" class="socialLinkTwitter" title="Twitter"></a>',
                        '<a href="https://www.facebook.com/beusterse" class="socialLinkFacebook" title="Facebook"></a>',
                        '<a href="https://plus.google.com/102857640059997003370" class="socialLinkGoogle" title="Google+" rel="publisher"></a>'));
      parent::__construct($config);
    }

    public static function html() {
      $archive = new self();
      echo $archive->getHTML();
    }
  }