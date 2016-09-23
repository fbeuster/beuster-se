/** beuster{se} | (c) 2010-2016 **/

admin.snippetPreview = {
  slide_duration: 400,

  bindHandlers: function() {
    $('td.title').on('click', this.titleHandler);
  },

  infoHandler: function() {
    var preview = $('div.preview');

    preview.slideUp(admin.snippetPreview.slide_duration, function() {
      preview.closest('tr').remove();
    });
  },

  init: function() {
    this.bindHandlers();
  },

  request: function(row, snippet) {
    $.ajax({
      type: "POST",
      url: "/api.php",
      data: {
        'scope' : 'admin',
        'method' : 'ApiSnippetPreview',
        'data' : {
          'name' : snippet
        }
      },
      success: function(data) {
        if (data == 'no_module') {
        } else if (data == 'no_access') {
        } else {
          var info  = $('<i></i>'),
              wrap  = $('<div></div>'),
              td    = $('<td></td>'),
              tr    = $('<tr></tr>');

          info.text( I18n.t('admin.snippet.overview.preview.label') )
              .addClass('info')
              .on('click', admin.snippetPreview.infoHandler);

          wrap.html(data)
              .addClass('preview')
              .attr('data-snippet', snippet)
              .css('display', 'none')
              .prepend(info)
              .appendTo(td);

          td.attr('colspan', '4')
            .appendTo(tr);

          tr.insertAfter(row);

          wrap.slideDown();
        }
      }
    });
  },

  titleHandler: function() {
    var preview = $('div.preview'),
        snippet = $(this).parent().attr('data-snippet');

    if (preview.length == 0) {
      admin.snippetPreview.request($(this).parent(), snippet);

    } else {
      var row = $(this).parent();

      preview.slideUp(admin.snippetPreview.slide_duration, function() {
        preview.closest('tr').remove();

        if ( preview.attr('data-snippet') != snippet ) {
          admin.snippetPreview.request(row, snippet);
        }
      });
    }
  }
};