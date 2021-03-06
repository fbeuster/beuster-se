/** beuster{se} | (c) 2010-2016 **/

admin.staticPageActions = {
  slide_duration : 400,

  bindHandlers: function() {
    if ($('.entry_list.static_pages').length > 0) {
      $('td.actions a.delete').on('click', this.deleteHandler);
    }
  },

  deleteHandler: function() {
    var row = $(this).closest('tr'),
        url = row.attr('data-static-page');

    $.ajax({
      type: "POST",
      url: "/api.php",
      data: {
        'scope' : 'admin',
        'method' : 'ApiStaticPageDelete',
        'data' : {
          'url' : url
        }
      },
      success: function(data) {
        if (data == 'success') {
          row
            .find('td')
            .wrapInner('<div></div>');

          row
            .find('td div')
            .slideUp( admin.staticPageActions.slide_duration, function(){
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