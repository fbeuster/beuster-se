
      <footer>
        <div class="socials">
          <a href="https://twitter.com/FBeuster" class="tw">Twitter</a>
          <a href="https://www.youtube.com/user/waterwebdesign" class="yt">YouTube</a>
          <a href="https://github.com/fbeuster" class="gh">GitHub</a>
          <a href="https://www.instagram.com/felixbeuster/" class="ig">Instagram</a>
          <a href="https://fixel.me" class="lk">fixel.me</a>
        </div>
        <div class="links">
          <a href="/about">Über und Feedback</a>
          <a href="/impressum">Impressum und Datenschutz</a>
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

  <?php } elseif (Config::getConfig()->get('google_analytics') == null) { ?>
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
  <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '<?php echo Config::getConfig()->get('google_analytics'); ?>']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  </script>
  <!-- / Google Anlaytics -->
  <?php } } ?>

  </body>
</html>
