/** beuster{se} | (c) 2010-2016 **/

admin.attachmentActions = {
  slide_duration: 400,

  bindHandlers: function() {
    if ($('.entry_list.attachments').length > 0) {
      $('td.actions a.delete').on('click', this.deleteHandler);
    }
  },

  deleteHandler: function() {
    var row         = $(this).closest('tr'),
        attachment  = row.attr('data-attachment');

    $.ajax({
      type: "POST",
      url: "/api.php",
      data: {
        'scope' : 'admin',
        'method' : 'ApiAttachmentDelete',
        'data' : {
          'id' : attachment
        }
      },
      success: function(data) {
        if (data == 'success') {
          row
            .find('td')
            .wrapInner('<div></div>');

          row
            .find('td div')
            .slideUp( admin.attachmentActions.slide_duration, function(){
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