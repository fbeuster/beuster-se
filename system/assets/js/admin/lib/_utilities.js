/** beuster{se} | (c) 2010-2015 **/

var KEY_ESCAPE = 27;

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