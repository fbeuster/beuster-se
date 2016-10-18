/** beuster{se} | (c) 2010-2015 **/

beusterse.comment_form = {
  count : 20,

  init: function() {
    if($('.beCommentNew').length > 0) {
      $('.beCommentEntry .reply').each(function(){
        $(this).removeAttr('href');
        $(this).click(beusterse.comment_form.setupDataForReply);
      });

      this.countdown(this.count);

      if($('.messageLength').length > 0) {
        $('#usrcnt').checkLength($('.messageLength'), $('#formPublicSub'));
      }
    }
  },

  bindHandlers: function() {
    $('#formPublicReset').click(this.resetFormControls);
  },

  countdown: function (i) {
    $('#formPublicSub')
      .attr('disabled','disabled')
      .css('color','#00a9ff');

    window.setTimeout(function() {
      if(i > 0) {
        i--;
        beusterse.comment_form.count--;
        $('#wait').text(i);
        beusterse.comment_form.countdown(i);
      }
    }, 1000);

    if($('#wait').text() == 0 && i == 0) {
      $('.beCommentNewTime').css('display','none');
      $('#formPublicSub')
        .removeAttr('disabled','disabled')
        .css('color','#efefef');
    }
  },

  resetFormControls: function() {
    if($('.beCommentNew .reply').attr('value') != 'null') {
      $('.beCommentNew .reply').attr('value', 'null');
      $('.beCommentNewHeader').text( I18n.t('comment.form.legend') );
    }
  },

  setupDataForReply: function() {
    var parent        = $(this).parent(),
        author        = parent.find('.beCommentEntryHeader .author').first().text(),
        replyId       = parent.attr('data-reply'),
        scrollAmount  = $('.beCommentNew').offset().top;

    $('.beCommentNew .reply').attr('value', replyId);
    $('html, body').animate({
        scrollTop: scrollAmount
    }, 400, 'swing', function() {
      var cancelButton = $('<span></span>')
                          .text( I18n.t('comment.reply.cancel') )
                          .addClass('cancelReply')
                          .click(beusterse.comment_form.resetFormControls)
      $('.beCommentNewHeader')
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
      disable.css('color','#00a9ff');
    } else {
      if(len <= 0) {
        disable.removeAttr('disabled','disabled');
        disable.css('color','#efefef');
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