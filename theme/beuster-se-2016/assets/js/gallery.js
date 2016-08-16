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
      'title' : $image.attr('title'),
      'name'  : $image.attr('name'),
      'alt'   : $image.attr('alt')
    });

    newImg.onload = function() {
      view.attr('src', $image.attr('src')).load(function(){
        var newScale  = beusterse.gallery.scaleLightbox(newImg.width, newImg.height)
            imgHeight = newScale.height,
            imgWidth  = newScale.width;

        $('.lightbox .wrapper').css({
          'marginTop' : (window.innerHeight - (imgHeight + 30)) / 2,
          'width'     : imgWidth,
          'height'    : imgHeight + 30
        });
        view.css({
          'width'   : imgWidth,
          'height'  : imgHeight
        });
      });
    }
    newImg.src = $image.attr('src');
    $('.lightbox .description').text(view.attr('name'));
  },

  openLightbox: function() {
    $('body').css('position','hidden');
    $('.lightbox').fadeIn(350);
  },

  scaleLightbox: function(_width, _height) {
    while(  (_width + 30) >= window.innerWidth ||
            (_height + 30) >= window.innerHeight ) {
      _width  = _width * 0.9;
      _height = _height * 0.9;
    }
    return { width: _width, height: _height };
  }
}