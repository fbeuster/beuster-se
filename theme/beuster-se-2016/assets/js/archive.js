/** beuster{se} | (c) 2010-2015 **/

beusterse.archive = {
  init: function() {
    if($('.module.archive ul').first().length == 0)
      return;

    this.binHandlers();
    this.minimizeAll();
  },

  binHandlers: function() {
    $('.articleArchiveYear').click(this.yearClick);
  },

  minimizeAll: function() {
    $('.module.archive ul').first().children().each(function(){
      $(this).children('ul').each(function(){
        $(this).hide();
      });
    });
  },

  yearClick: function() {
    $(this).next().each(function(){
      $(this).is(':visible') ? $(this).slideUp() : $(this).slideDown();
    });
  }
}