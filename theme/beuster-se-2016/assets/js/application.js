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
  }
});
