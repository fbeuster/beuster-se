$(document).ready(function() {
  $('.eu_cookie_notifier .close').click(function() {
    // set acceptence cookie
    var date    = new Date(),
        expires,
        name    = 'eu_cookies_accepted',
        value   = 'yes';

    date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toGMTString();

    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";

    // remove notification
    $('.eu_cookie_notifier').slideUp(400, function(){
      $('.eu_cookie_notifier').remove();
    });
  });
});
