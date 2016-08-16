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
        $('#usrcnt').checkLength($('.messageLength'), $('#formSubmit'));
      }
    }
  },

  bindHandlers: function() {
    $('#formReset').click(this.resetFormControls);
  },

  countdown: function (i) {
    $('#formSubmit').attr('disabled','disabled');

    window.setTimeout(function() {
      if(i > 0) {
        i--;
        beusterse.comment_form.count--;
        $('#wait').text(i);
        beusterse.comment_form.countdown(i);
      }
    }, 1000);

    if($('#wait').text() == 0 && i == 0) {
      $('.newCommentTime').css('display','none');
      $('#formSubmit')
        .removeAttr('disabled','disabled');
    }
  },

  resetFormControls: function() {
    if($('.comments form .reply').attr('value') != 'null') {
      $('.comments form .reply').attr('value', 'null');
      $('.comments form legend').text('Schreibe einen Kommentar!');
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
                          .text('Antwort abbrechen')
                          .addClass('cancelReply')
                          .click(beusterse.comment_form.resetFormControls)
      $('.comments form legend')
        .text('Schreibe einen Kommentar an ' + author + '!')
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
      if(count <= 0) {
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
