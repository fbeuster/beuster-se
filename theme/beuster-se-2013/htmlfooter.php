
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
    <a href="/about">Über und Feedback</a> | <a href="/impressum">Impressum und Datenschutz</a><br>
    &copy; Copyright 2010-2017 Felix Beuster
  </footer>
  <!-- Ende beMainFooter -->

  <!-- lightbox -->
  <div class="beLightbox">
    <div class="beLightboxWrapper">
      <div class="beLightboxImage">
        <img src="/images/spacer.gif" alt="" id="imgViewport">
      </div>
      <div class="beLightboxText">
        <span class="beLightboxDecription">Ich bin ein Test.</span>
        <span class="beLightboxClose">Close</span>
      </div>
    </div>
  </div>
  <!-- ende lightbox -->

  <?php
    if (!Utilities::isDevServer()) {
      $user =  User::newFromCookie();

    if (!$user || !$user->isAdmin()) {
  ?>
  <!-- Google Analytics -->
  <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-1710454-3']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  </script>
  <!-- ende Google Anlaytics -->
  <?php
      }
    }
  ?>
  <?php
  if(time() >= strtotime('28-03-2014') && time() <= strtotime('27-04-2014')) { ?>
  <!-- Give me a cake, it's my birthday :) -->
  <div class="birth4"><a href="http://beusterse.de/305/blog/4-Jahre-beusterse">4 Jahre beuster{se}</a></div>
  <?php } ?>
 </body>
</html>