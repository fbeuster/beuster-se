<?php

  $lb = Lixter::getLix()->getLinkBuilder();

?>
  </div>

  <!-- Google AdSense -->
  <div class="adSense footer" id="google-ads-2">
  <?php if(Utilities::isDevServer()) { ?>
    Google AdSense
  <?php } else { ?>
    <script type="text/javascript"><!--
      adUnit = document.getElementById("google-ads-2");
      adWidth = adUnit.offsetWidth;
      google_ad_client = "ca-pub-4132935023049723";
      if ( adWidth >= 728 ) {
        /* beusterseFooter */
        google_ad_slot = "2286931269";
        google_ad_width = 728;
        google_ad_height = 90;
      } else if ( adWidth >= 330 ) {
        /* responsiveGe428 */
        google_ad_slot = "3206886433";
        google_ad_width = 320;
        google_ad_height = 50;
      } else {
        /* Do not display the Google Ad */
        google_ad_slot = "0";
        adUnit.style.display = "none";
      }
      //-->
    </script>
    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
  <?php } ?>
  </div>
  <!-- Ende Google AdSense -->

  <!-- beMainFooter -->
  <footer id="beMainFooter">
    <a href="<?php echo $lb->makeOtherPageLink('about'); ?>">Über und Feedback</a> | <a href="<?php echo $lb->makeOtherPageLink('impressum'); ?>">Impressum und Datenschutz</a><br>
    &copy; Copyright 2010-2017 Felix Beuster
  </footer>
  <!-- Ende beMainFooter -->

  <!-- lightbox -->
  <div class="beLightbox">
    <div class="beLightboxWrapper">
      <div class="beLightboxImage">
        <img src="/<?php echo Lixter::getLix()->getSystemFile('assets/img/spacer.gif'); ?>" alt="" id="imgViewport">
      </div>
      <div class="beLightboxText">
        <span class="beLightboxDecription">Ich bin ein Test.</span>
        <span class="beLightboxClose">Close</span>
      </div>
    </div>
  </div>
  <!-- ende lightbox -->

  <?php EUCookieNotifier::embed('/impressum'); ?>

  <?php
    if (!Utilities::isDevServer() &&
        Config::getConfig()->get('site', 'google_analytics') != null) {

      $user =  User::newFromCookie();

    if (!$user || !$user->isAdmin()) {
  ?>
  <!-- Google Analytics -->
  <script type="text/javascript">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', '<?php echo Config::getConfig()->get('site', 'google_analytics'); ?>', 'auto');
    ga('send', 'pageview');
  </script>
  <!-- ende Google Anlaytics -->
  <?php
      }
    }
  ?>
  <?php
  if(time() >= strtotime('28-03-2014') && time() <= strtotime('27-04-2014')) { ?>
  <!-- Give me a cake, it's my birthday :) -->
  <div class="birth4"><a href="https://beusterse.de/305/blog/4-Jahre-beusterse">4 Jahre beuster{se}</a></div>
  <?php } ?>
 </body>
</html>