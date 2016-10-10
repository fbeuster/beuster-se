/** beuster{se} | (c) 2010-2016 **/

var beusterse = beusterse || {};

//@ lib/_jquery
//@ lib/_i18n
//@ lib/_utilities
//@ comment_form
//@ gallery
//@ search

$(document).ready(function(){
  beusterse.i18n.init()
                .complete( runApp );

  function runApp() {
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
  }
});
