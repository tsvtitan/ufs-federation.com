(function(root){
  var htmlEscapes = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;',
    '`': '&#96;'
  };

  var reEscapedHtml = /&(?:amp|lt|gt|quot|#39|#96);/g,
      reUnescapedHtml = /[&<>"'`]/g;

  function escapeHtmlChar(chr) {
    return htmlEscapes[chr];
  }

  function escape(string) {
    // reset `lastIndex` because in IE < 9 `String#replace` does not
    string = (string == null) ? '' : String(string);
    return string && (reUnescapedHtml.lastIndex = 0, reUnescapedHtml.test(string))
      ? string.replace(reUnescapedHtml, escapeHtmlChar)
      : string;
  }

  var _ = root._ || {};
  _.escape = _.escape || escape;
  root._ = _;
})(this);