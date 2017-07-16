$(document).ready(function() {
  if ($('.attachments').length > 0) {
    $('.attachments a[data-file]').click(function(e){
      e.preventDefault();

      var file  = $(this).attr('data-file'),
          $link = $(this);
      $.ajax({
        type: "POST",
        url: "/api.php",
        data: {
          'method' : 'ApiDownloadCounter',
          'data' : {
            'file' : file
          }
        },
        success: function(data) {
          if (data == 'success') {
            var $counter = $link.closest('.attachments').find('.counter[data-file='+file+']');
            $counter.text(parseInt($counter.text()) + 1);
          }

          window.location.href = $link.attr('href');
        }
      });

    });
  }
});