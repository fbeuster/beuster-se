/** beuster{se} | (c) 2010-2016 **/
beusterse.comment_form = {
  count : 20,

  init: function() {
    if($('.comments form').length > 0) {
      $('.comment .reply').each(function(){
        $(this).removeAttr('href');
        $(this).click(beusterse.comment_form.setupDataForReply);
      });

      this.countdown(this.count);

      if($('.messageLength').length > 0) {
        $('#usrcnt').checkLength($('.messageLength'), $('#formaction'));
      }
    }
  },

  bindHandlers: function() {
    $('#formreset').click(this.resetFormControls);
  },

  countdown: function (i) {
    $('#formaction').attr('disabled','disabled');

    window.setTimeout(function() {
      if(i > 0) {
        i--;
        beusterse.comment_form.count--;
        $('#wait').text(i);
        beusterse.comment_form.countdown(i);
      }
    }, 1000);

    if($('#wait').text() == 0 && i == 0) {
      $('.newCommentTime').slideUp(400, function() {
        $('.newCommentTime').css('display','none');
      });

      $('#formaction')
        .removeAttr('disabled','disabled');

      if ($('div.success').length > 0) {
        $('div.success').slideUp(400, function() {
          $('div.success').remove();
        });
      }
    }
  },

  resetFormControls: function() {
    if($('.comments form .reply').attr('value') != 'null') {
      $('.comments form .reply').attr('value', 'null');
      $('.comments form legend').text( I18n.t('comment.form.legend') );
    }
  },

  setupDataForReply: function() {
    var parent        = $(this).closest('.comment'),
        author        = parent.find('.author').first().text(),
        replyId       = parent.attr('data-reply'),
        scrollAmount  = $('.comments form').offset().top;

    $('.comments form .reply').attr('value', replyId);
    $('html, body').animate({
        scrollTop: scrollAmount
    }, 400, 'swing', function() {
      var cancelButton = $('<span></span>')
                          .text( I18n.t('comment.reply.cancel') )
                          .addClass('cancelReply')
                          .click(beusterse.comment_form.resetFormControls)
      $('.comments form legend')
        .text( I18n.t('comment.reply.legend', [author]) )
        .append(cancelButton);
    });
  }
}

$.fn.checkLength = function(info, disable) {
  function check(len) {
    var maxLen = 1500;
    // disable
    if(len > maxLen) {
      disable.attr('disabled','disabled');
    } else {
      if(len <= 0) {
        disable.removeAttr('disabled','disabled');
      }
    }
    // info
    info.find('span').text(maxLen - len);
    if(len > maxLen) {
      info.addClass('error');
      info.removeClass('warning');
    } else if(len > maxLen - 20) {
      info.removeClass('error');
      info.addClass('warning');
    } else {
      info.removeClass('error');
      info.removeClass('warning');
    }
  }
  this.change(function(){
    check($('#usrcnt').val().length);
  });
  this.keyup(function(){
    check($('#usrcnt').val().length);
  });
};
