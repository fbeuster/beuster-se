/** beuster{se} | (c) 2010-2015 **/

var beusterse = beusterse || {};

//@ jquery
//@ _i18n
//@ _utilities
//@ archive
//@ comment_form
//@ gallery
//@ pager

$(document).ready(function(){
  beusterse.i18n.init()
                .complete( runApp );

  function runApp() {
    beusterse.archive.init();
    beusterse.comment_form.init();
    beusterse.gallery.init();
    beusterse.pager.init();
  }
});
