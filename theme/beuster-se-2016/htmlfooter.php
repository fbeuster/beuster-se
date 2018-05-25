<?php

  $lb = Lixter::getLix()->getLinkBuilder();

?>
      <footer>
        <div class="socials">
          <a href="https://twitter.com/FBeuster" class="tw">Twitter</a>
          <a href="https://www.youtube.com/user/waterwebdesign" class="yt">YouTube</a>
          <a href="https://github.com/fbeuster" class="gh">GitHub</a>
          <a href="https://www.instagram.com/felixbeuster/" class="ig">Instagram</a>
          <a href="https://fixel.me" class="lk">fixel.me</a>
        </div>
        <div class="links">
          <a href="<?php echo $lb->makeOtherPageLink('about'); ?>">
            Über und Feedback
          </a>
          <a href="<?php echo $lb->makeOtherPageLink('impressum'); ?>">
            Impressum und Datenschutz
          </a>
          <a href="<?php echo $lb->makeOtherPageLink('privacy-settings'); ?>">
            Privatsphäreeinstellungen
          </a>
        </div>
        <div class="copy">
          &copy; Copyright 2010 - 2017 Felix Beuster
        </div>
      </footer>
    </div>

    <div class="lightbox">
      <div class="wrapper">
        <div class="image">
          <img src="/<?php echo Lixter::getLix()->getSystemFile('assets/img/spacer.gif'); ?>" alt="" id="imgViewport">
        </div>
        <div class="text">
          <span class="description">Ich bin ein Test.</span>
          <span class="close" title="<?php I18n::e('article.gallery.close'); ?>"><?php I18n::e('article.gallery.close'); ?></span>
        </div>
      </div>
    </div>

    <?php EUCookieNotifier::embed('/impressum'); ?>

  <?php if (Utilities::isDevServer()) { ?>
  <!-- No Google Analytics, dev server -->

  <?php } elseif (Config::getConfig()->get('ext', 'google_analytics') == null) { ?>
  <!-- No Google Analytics configured -->

  <?php } else { ?>
  <?php
    $user =  User::newFromCookie();

    if (  ($user && $user->isAdmin()) ||
          $_GET['p'] == 'admin' ||
          $_GET['p'] == 'phpmyadmin') {
  ?>
  <!-- No Google Analytics, logged in admin -->

  <?php } else { ?>
  <!-- Google Analytics -->
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo Config::getConfig()->get('ext', 'google_analytics'); ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '<?php echo Config::getConfig()->get('ext', 'google_analytics'); ?>', {'anonymize_ip' : true});
  </script>

  <!-- / Google Anlaytics -->
  <?php } } ?>

  </body>
</html>
