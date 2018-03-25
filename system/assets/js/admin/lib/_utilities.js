/** beuster{se} | (c) 2010-2015 **/

var KEYS = {
  ALT     : 18,
  CTRL    : 17,
  ESCAPE  : 27,
  SHIFT   : 16,
  TWO     : 50,
  THREE   : 51,
  A       : 65,
  B       : 66,
  C       : 67,
  D       : 68,
  I       : 73,
  M       : 77,
  O       : 79,
  Q       : 81,
  T       : 84,
  U       : 85,
  Y       : 89
};

admin.utilities = {
};

$.fn.selectRange = function(start, end) {
  if (end === undefined) {
    end = start;
  }

  return this.each(function() {
    if ('selectionStart' in this) {
      this.selectionStart = start;
      this.selectionEnd = end;

    } else if (this.setSelectionRange) {
      this.setSelectionRange(start, end);

    } else if (this.createTextRange) {
      var range = this.createTextRange();
      range.collapse(true);
      range.moveEnd('character', end);
      range.moveStart('character', start);
      range.select();
    }
  });
};