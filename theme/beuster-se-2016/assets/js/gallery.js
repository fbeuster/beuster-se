/** beuster{se} | (c) 2010-2016 **/

beusterse.gallery = {

  init: function() {
    this.bindHandlers();
  },

  bindHandlers: function() {
    $('.gallery ul li img').click(this.lightbox);

    $('.lightbox').click(this.hideLightbox);

    $('body.article p.image img').click(this.lightbox);
    $('body.article span.image img').click(this.lightbox);

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

    view.attr({
      'alt'           : $image.attr('alt'),
      'data-caption'  : $image.attr('data-caption'),
      'title'         : $image.attr('title')
    });

    if (view.attr('data-caption') != undefined &&
        view.attr('data-caption') != null &&
        view.attr('data-caption') != '') {
      $('.lightbox .description').text(view.attr('data-caption'));

    } else {
      $('.lightbox .description').text(view.attr('alt'));
    }

    view.load(function(){
      beusterse.gallery.openLightbox();
    });

    $(newImg).load(function() {
      view.attr('src', $image.attr('data-src'));
      var textHeight = $('.lightbox .wrapper .text').outerHeight(true) / 2 * 3.6,
          newScale  = beusterse.gallery.scaleLightbox(newImg.width + textHeight,
                                                      newImg.height + textHeight)
          boxHeight = newScale.height,
          boxWidth  = newScale.width;

      $('.lightbox .wrapper').css({
        'marginTop' : (window.innerHeight - boxHeight) / 2,
        'width'     : boxWidth,
        'height'    : boxHeight
      });
      view.css({
        'width'   : boxWidth,
        'height'  : boxHeight - textHeight
      });
    });

    newImg.src = $image.attr('data-src');
  },

  openLightbox: function() {
    $('body').css('position','hidden');
    $('.lightbox').fadeIn(350);
  },

  scaleLightbox: function(_width, _height) {
    while(  _width >= window.innerWidth * 0.9 ||
            _height >= window.innerHeight * 0.9) {
      _width  = _width * 0.9;
      _height = _height * 0.9;
    }
    return { width: _width, height: _height };
  }
}