$(document).ready(function(){
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
          enableCookies();
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
    var cookies = document.cookie.split(';');

    for (var i = 0; i < cookies.length; i++) {
      var cookie = cookies[i].split('=');
      deleteCookie(cookie[0]);
    }

    function deleteCookie(cookiename) {
      var d = new Date();
      d.setDate(d.getDate() - 1);

      var expires = ';expires=' + d,
          name    = cookiename,
          value   = '';

      document.cookie = name + '=' + value + expires + '; path=/';
    }
  }

  function enableCookies() {
    var date    = new Date(),
        expires,
        name    = 'eu_cookies_accepted',
        value   = 'yes';

    date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toGMTString();

    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";

    // remove notification
    if ($('.eu_cookie_notifier').length > 0) {
      $('.eu_cookie_notifier').slideUp(400, function(){
        $('.eu_cookie_notifier').remove();
      });
    }
  }
});
