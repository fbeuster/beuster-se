/** beuster{se} | (c) 2010-2015 **/

beusterse.portfolio = {
  init: function() {
    if($('div.portSlider').length == 0)
      return;

    this.bindHandler();
    $('.portSlider').addClass('hasJs');
  },

  bindHandler: function() {
    $('img.portThumb').click(this.scrollToTarget);
  },

  scrollToTarget: function() {
    var target = $(this).parent().removeAttr('href');
    var target = $(this).attr('name');
    var targetDiv = $(target).parent().parent();
    var tarScroll = $(target).offset().left - $(targetDiv).offset().left;
    $(targetDiv).stop().animate({
      scrollLeft: '+=' + tarScroll + 'px'
    }, 1200);
  }
}