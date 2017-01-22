/** beuster{se} | (c) 2010-2016 **/

beusterse.gallery = {

  init: function() {
    this.bindHandlers();
  },

  bindHandlers: function() {
    $('.gallery ul li img').click(this.lightbox);

    $('.lightbox').click(this.hideLightbox);

    $('body.article p.image img').click(this.lightbox);

    $('.lightbox .close').click(this.closeLightbox);

    $(document).keydown(function(event) {
      var key = event.which || event.keyCode;
      if(key == KEY_ESCAPE && beusterse.gallery.isLightboxVisible()) {
        beusterse.gallery.closeLightbox();
      }
    });
  },

  closeLightbox: function() {
    $('body').css('overflow','inherit');
    $('.lightbox').fadeOut(350);
  },

  hideLightbox: function(event) {
    if( !(beusterse.gallery.isMouseOverLightbox(event.target)) ) {
      beusterse.gallery.closeLightbox();
    }
  },

  isLightboxVisible: function() {
    return $('.beLightbox').css('display') == 'block';
  },

  isMouseOverLightbox: function(target) {
    return  $(target).is('#imgViewport') || $(target).is('.lightbox .wrapper') ||
            $(target).is('.lightbox .text span');
  },

  lightbox: function() {
    var view    = $('#imgViewport'),
        newImg  = new Image();
    $image = $(this);

    beusterse.gallery.openLightbox();

    view.attr({
      'alt'           : $image.attr('alt'),
      'data-caption'  : $image.attr('data-caption'),
      'title'         : $image.attr('title')
    });

    newImg.onload = function() {
      view.attr('src', $image.attr('src')).load(function(){
        var textHeight = $('.lightbox .wrapper .text').outerHeight(true),
            newScale  = beusterse.gallery.scaleLightbox(newImg.width + textHeight,
                                                        newImg.height + textHeight)
            imgHeight = newScale.height,
            imgWidth  = newScale.width;

        $('.lightbox .wrapper').css({
          'marginTop' : (window.innerHeight - (imgHeight + textHeight)) / 2,
          'width'     : imgWidth,
          'height'    : imgHeight + textHeight
        });
        view.css({
          'width'   : imgWidth,
          'height'  : imgHeight
        });
      });
    }
    newImg.src = $image.attr('src');

    if (view.attr('data-caption') != undefined &&
        view.attr('data-caption') != null &&
        view.attr('data-caption') != '') {
      $('.lightbox .description').text(view.attr('data-caption'));

    } else {
      $('.lightbox .description').text(view.attr('alt'));
    }
  },

  openLightbox: function() {
    $('body').css('position','hidden');
    $('.lightbox').fadeIn(350);
  },

  scaleLightbox: function(_width, _height) {
    while(  _width >= window.innerWidth ||
            _height >= window.innerHeight ) {
      _width  = _width * 0.9;
      _height = _height * 0.9;
    }
    return { width: _width, height: _height };
  }
}