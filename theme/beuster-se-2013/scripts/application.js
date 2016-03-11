/** beuster{se} | (c) 2010-2015 **/

var beusterse = beusterse || {};

//@ jquery
//@ _i18n
//@ _utilities
//@ archive
//@ bbcode
//@ comment_form
//@ gallery
//@ pager
//@ portfolio

$(document).ready(function(){
  beusterse.i18n.init()
                .complete( runApp );

  function runApp() {
    beusterse.archive.init();
    beusterse.bbCode.init();
    beusterse.comment_form.init();
    beusterse.gallery.init();
    beusterse.pager.init();
    beusterse.portfolio.init();
  }
});
