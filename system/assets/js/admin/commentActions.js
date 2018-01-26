/** beuster{se} | (c) 2010-2016 **/

admin.commentActions = {
  slide_duration : 400,

  bindHandlers: function() {
    if ($('.entry_list.comments').length > 0) {
      $('td.actions a.delete').on('click', this.deleteHandler);
      $('td.actions a.disable').on('click', this.disableHandler);
      $('td.actions a.enable').on('click', this.enableHandler);
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
            .find('td > div')
            .slideUp( admin.commentActions.slide_duration, function(){
              row.remove();
            });
        }
      }
    });
  },

  disableHandler: function() {
    var link    = $(this),
        comment = $(this).closest('tr')
                          .attr('data-comment');

    $.ajax({
      type: "POST",
      url: "/api.php",
      data: {
        'scope' : 'admin',
        'method' : 'ApiCommentDisable',
        'data' : {
          'id' : comment
        }
      },
      success: function(data) {
        if (data == 'success') {
          link.removeClass('disable')
              .addClass('enable')
              .attr('title', admin.i18n.translate('admin.comment.overview.enable.title'))
              .text(admin.i18n.translate('admin.comment.overview.enable.text'))
              .off('click')
              .on('click', admin.commentActions.enableHandler);
        }
      }
    });
  },

  enableHandler: function() {
    var link    = $(this),
        comment = $(this).closest('tr')
                          .attr('data-comment');

    $.ajax({
      type: "POST",
      url: "/api.php",
      data: {
        'scope' : 'admin',
        'method' : 'ApiCommentEnable',
        'data' : {
          'id' : comment
        }
      },
      success: function(data) {
        if (data == 'success') {
          link.removeClass('enable')
              .addClass('disable')
              .attr('title', admin.i18n.translate('admin.comment.overview.disable.title'))
              .text(admin.i18n.translate('admin.comment.overview.disable.text'))
              .off('click')
              .on('click', admin.commentActions.disableHandler);
        }
      }
    });
  },

  init: function() {
    this.bindHandlers();
  }
};
