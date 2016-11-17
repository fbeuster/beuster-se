/** beuster{se} | (c) 2010-2016 **/

admin.articleActions = {
  slide_duration : 400,

  bindHandlers: function() {
    if ($('.entry_list.articles').length > 0) {
      $('td.actions a.delete').on('click', this.deleteHandler);
    }
  },

  deleteHandler: function() {
    var row     = $(this).closest('tr'),
        article = row.attr('data-article');

    $.ajax({
      type: "POST",
      url: "/api.php",
      data: {
        'scope' : 'admin',
        'method' : 'ApiArticleDelete',
        'data' : {
          'id' : article
        }
      },
      success: function(data) {
        if (data == 'success') {
          row
            .find('td')
            .wrapInner('<div></div>');

          row
            .find('td div')
            .slideUp( admin.articleActions.slide_duration, function(){
              row.remove();
            });
        }
      }
    });
  },

  init: function() {
    this.bindHandlers();
  }
};