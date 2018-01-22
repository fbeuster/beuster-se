/** beuster{se} | (c) 2010-2016 **/

admin.commentActions = {
  slide_duration : 400,

  bindHandlers: function() {
    if ($('.entry_list.comments').length > 0) {
      $('td.actions a.delete').on('click', this.deleteHandler);
    }
  },

  deleteHandler: function() {
    var row     = $(this).closest('tr'),
        comment = row.attr('data-comment');

    $.ajax({
      type: "POST",
      url: "/api.php",
      data: {
        'scope' : 'admin',
        'method' : 'ApiCommentDelete',
        'data' : {
          'id' : comment
        }
      },
      success: function(data) {
        if (data == 'success') {
          row
            .find('td')
            .wrapInner('<div></div>');

          row
            .find('td div')
            .slideUp( admin.commentActions.slide_duration, function(){
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
