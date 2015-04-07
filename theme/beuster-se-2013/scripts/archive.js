/** beuster{se} | (c) 2010-2015 **/

beusterse.archive = {
  init: function() {
    if($('.articleArchiveMain').length == 0)
      return;

    this.binHandlers();
    this.minimizeAll();
  },

  binHandlers: function() {
    $('.articleArchiveYear').click(this.yearClick);
  },

  minimizeAll: function() {
    $('.articleArchiveMain').children().each(function(){
      $(this).children('.articleArchiveSub').each(function(){
        $(this).hide();
      });
    });
  },

  yearClick: function() {
    $(this).parent().children('.articleArchiveSub').each(function(){
      $(this).is(':visible') ? $(this).slideUp() : $(this).slideDown();
    });
  }
}