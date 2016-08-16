/** beuster{se} | (c) 2010-2015 **/

var I18n = I18n || {};

beusterse.i18n = {
  lang_path   : 'locale/',
  lang_file   : '',
  translation : {},

  init: function() {
    this.lang_file = this.lang_path + this.getLang() + '.json';
    this.createShortcut();
    return this.loadLangFile();
  },

  createShortcut: function() {
    I18n.t = beusterse.i18n.translate;
  },

  format: function(string, args) {
    for (var key in args) {
      string = string.replace('%s', args[key]);
    }
    return string;
  },

  getLang: function() {
    return $('meta[name=loaded_lang]').attr('content');
  },

  loadLangFile: function() {
    return $.getJSON(this.lang_file, {async: false}, function(data){
      beusterse.i18n.translation = data;
    });
  },

  translate: function(keyword, args) {
    if (typeof keyword === "undefined") {
      return 'No translate keyword given.';
    }

    var key_chain = keyword.split('.'),
        value     = beusterse.i18n.translation;

    for (var key in key_chain) {
      value = value[ key_chain[key] ];

      if (typeof value === "undefined") {
        return 'Missing translation for ' + keyword;
      }
    }

    if (typeof args !== "undefined") {
      return beusterse.i18n.format(value, args);
    }

    return value;
  }
}