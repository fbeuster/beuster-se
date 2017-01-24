/** beuster{se} | (c) 2010-2016 **/

beusterse.search = {
  div: 'div.search',
  init_delay: 1500,

  init: function () {
    this.bindHandlers();

    if (!$(this.div + ' input[type=text]').val() ||
      $(this.div + ' input[type=text]').val().trim() == '') {
      setTimeout(this.deactivateSearch, this.init_delay);
    }
  },

  activateSearch: function () {
    $(beusterse.search.div)
      .removeClass('inactive')
      .find('input[type=text]')
        .focus();
  },

  bindHandlers: function () {
    $('body').click(this.clickHandler);
    $(this.div + ' input[type=submit]').click(this.searchClickHandler);
    $(this.div + ' input[type=text]').focus(this.focusHandler);
    $(this.div + ' input').focusout(this.focusoutHandler);
  },

  deactivateSearch: function () {
    $(beusterse.search.div)
      .addClass('inactive')
      .find('input[type=text]')
        .val('');
  },

  clickHandler: function (event) {
    if( beusterse.search.isMouseOverSearch(event.target) ) {
      beusterse.search.activateSearch();

    } else {
      if (!$(beusterse.search.div + ' input[type=text]').val() ||
        $(beusterse.search.div + ' input[type=text]').val().trim() == '') {
        beusterse.search.deactivateSearch();
      }
    }
  },

  focusHandler: function (event) {
    $(beusterse.search.div).removeClass('inactive');
  },

  focusoutHandler: function (event) {
    if (!$(event.relatedTarget).is('input[type=submit]')) {
      if (!$(beusterse.search.div + ' input[type=text]').val() ||
        $(beusterse.search.div + ' input[type=text]').val().trim() == '') {
        beusterse.search.deactivateSearch();
      }
    }
  },

  isMouseOverSearch: function (target) {
    return  $(target).is('.search') || $(target).is('.search form') ||
            $(target).is('.search form input');
  },

  searchClickHandler: function (event) {
    if ($(beusterse.search.div).hasClass('inactive')) {
      event.preventDefault();
      beusterse.search.activateSearch();
    }
  }
}

