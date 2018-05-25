/** beuster{se} | (c) 2010-2016 **/

var beusterse = beusterse || {};

//@ lib/_jquery
//@ lib/_i18n
//@ lib/_utilities
//@ archive
//@ comment_form
//@ gallery
//@ search

$(document).ready(function(){
  beusterse.i18n.init()
                .complete( runApp );

  function runApp() {
    beusterse.archive.init();
    beusterse.comment_form.init();
    beusterse.gallery.init();
    beusterse.search.init();

    $('body.article section.gallery li').hover(function(){
      $(this).toggleClass('activated');
      $('body').toggleClass('highlighted');
    });

    $('body.category section.article').hover(function(){
      $(this).toggleClass('activated');
      $('body').toggleClass('highlighted');
    });

    var $expander = $('header nav .expander');
    $expander.click(function(){
      $expander.toggleClass('expanded');
      $('header menu').toggleClass('expanded');

      if ($expander.hasClass('expanded')) {
        $expander.text('Menü ausblenden');
      } else {
        $expander.text('Menü anzeigen');
      }
    });

    if ($('.privacy_settings').length > 0) {
      var cookies_enabled   = new Event('cookies_enabled'),
          cookies_disabled  = new Event('cookies_disabled');

      var accepted_class      = 'accepted',
          not_accepted_class  = 'not_accepted';

      $('.toggle').click(function(){
        var status,
            toggle = '';

        if ($(this).hasClass(accepted_class)) {
          old     = accepted_class;
          status  = not_accepted_class;

        } else {
          old     = not_accepted_class;
          status  = accepted_class;
        }

        $(this).removeClass(old);
        $(this).addClass(status);

        if ($(this).hasClass('cookies')) {
          toggle = 'cookies';

          if (status == not_accepted_class) {
            clearCookies();
            document.dispatchEvent(cookies_disabled);

          } else {
            document.dispatchEvent(cookies_enabled);
          }
        }

        if (toggle != '') {
          $(this)
            .find(  '.label')
            .attr(  'title',
                    I18n.t('privacy_settings.' + toggle +
                            '.' + status + '.title'))
            .text(  I18n.t( 'privacy_settings.' + toggle +
                            '.' + status + '.label'));
        }
      });
    }

    function clearCookies() {
      console.log('clear');
      var cookies = document.cookie.split(';');

      for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].split('=');
        deleteCookie(cookie[0]);
      }

      function deleteCookie(cookiename) {
        console.log('delete ' + cookiename);
        var d = new Date();
        d.setDate(d.getDate() - 1);

        var expires = ';expires=' + d,
            name    = cookiename,
            value   = '';

        document.cookie = name + '=' + value + expires + '; path=/';
      }
    }
  }
});
